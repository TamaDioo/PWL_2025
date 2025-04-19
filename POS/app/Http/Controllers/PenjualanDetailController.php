<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Semua Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Semua Detail']
        ];

        $page = (object) [
            'title' => 'Daftar Semua Detail Penjualan'
        ];

        $activeMenu = 'detail_penjualan';

        $penjualans = PenjualanModel::all();
        $barangs = BarangModel::all(); // ambil data barang untuk filter

        return view('penjualan_detail.index', compact('breadcrumb', 'page', 'activeMenu', 'barangs', 'penjualans'));
    }

    public function list(Request $request)
    {
        $detail_penjualans = PenjualanDetailModel::with(['penjualan', 'barang']); // Load relasi penjualan juga

        // Filter data penjualan berdasarkan kode penjualan
        if ($request->penjualan_id) {
            $detail_penjualans->where('penjualan_id', $request->penjualan_id);
        }

        // Filter data penjualan berdasarkan barang
        if ($request->barang_id) {
            $detail_penjualans->where('barang_id', $request->barang_id);
        }

        return DataTables::of($detail_penjualans)
            ->addIndexColumn()
            ->addColumn('kode_penjualan', function ($detail) {
                return $detail->penjualan->penjualan_kode;
            })
            ->addColumn('nama_barang', function ($detail) {
                return $detail->barang->barang_nama;
            })
            ->addColumn('aksi', function ($detail) {
                $btn = '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $detail->detail_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $detail->detail_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan_detail/' . $detail->detail_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();
        $barangs = BarangModel::select('barang_id', 'barang_nama', 'harga_jual') // Ambil harga_jual
            ->get()
            ->keyBy('barang_id');
        $stoks = StokModel::select('barang_id', DB::raw('SUM(stok_jumlah) as total_stok'))
            ->groupBy('barang_id')
            ->get()
            ->keyBy('barang_id'); // Index stok berdasarkan barang_id

        return view('penjualan_detail.create_ajax', compact('penjualans', 'barangs', 'stoks'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'penjualan_id' => 'required|integer|exists:t_penjualan,penjualan_id',
                'barang_id' => 'required|integer|exists:m_barang,barang_id',
                'jumlah' => 'required|integer|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            DB::beginTransaction();
            try {
                $barang = BarangModel::findOrFail($request->barang_id);
                $hargaJual = $barang->harga_jual; // Ambil harga jual dari database

                // Ambil total stok barang
                $totalStok = StokModel::where('barang_id', $request->barang_id)
                    ->sum('stok_jumlah');

                if ($totalStok < $request->jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Stok barang tidak mencukupi',
                        'msgField' => ['jumlah' => ['Stok yang tersedia: ' . $totalStok]],
                    ]);
                }

                // Tambahkan harga jual ke dalam request sebelum membuat detail penjualan
                $request->merge(['harga' => $hargaJual]);
                PenjualanDetailModel::create($request->all());

                // Kurangi stok dari tabel t_stok 
                $stoksToReduce = StokModel::where('barang_id', $request->barang_id)
                    ->orderBy('stok_tanggal') // Menggunakan FIFO
                    ->get();

                $jumlahDikurangi = 0;
                foreach ($stoksToReduce as $stok) {
                    $kurangi = min($request->jumlah - $jumlahDikurangi, $stok->stok_jumlah);
                    $stok->decrement('stok_jumlah', $kurangi);
                    $jumlahDikurangi += $kurangi;
                    if ($jumlahDikurangi == $request->jumlah) {
                        break;
                    }
                }

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Detail penjualan berhasil ditambahkan'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan detail penjualan',
                    'error' => $e->getMessage(),
                ]);
            }
        }
        redirect('/penjualan_detail');
    }

    public function show_ajax(string $detail_id)
    {
        $detail_penjualan = PenjualanDetailModel::findOrFail($detail_id);
        return view('penjualan_detail.show_ajax', compact('detail_penjualan'));
    }

    public function edit_ajax(string $detail_id)
    {
        $detail_penjualan = PenjualanDetailModel::findOrFail($detail_id);
        $barangs = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')
            ->get()
            ->keyBy('barang_id');
        $stoks = StokModel::select('barang_id', DB::raw('SUM(stok_jumlah) as total_stok'))
            ->groupBy('barang_id')
            ->get()
            ->keyBy('barang_id');

        return view('penjualan_detail.edit_ajax', compact('detail_penjualan', 'barangs', 'stoks'));
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id' => 'required|integer|exists:m_barang,barang_id',
                'jumlah' => 'required|integer|min:1',
                'penjualan_id' => 'required|integer|exists:t_penjualan,penjualan_id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ], 422);
            }

            DB::beginTransaction();
            try {
                $detail_penjualan = PenjualanDetailModel::findOrFail($id);
                $barangBaru = BarangModel::findOrFail($request->barang_id);
                $hargaJualBaru = $barangBaru->harga_jual;
                $selisihJumlah = $request->jumlah - $detail_penjualan->jumlah;

                // Kembalikan stok barang lama
                $stokLama = StokModel::where('barang_id', $detail_penjualan->barang_id)->first();
                if ($stokLama) {
                    $stokLama->increment('stok_jumlah', $detail_penjualan->jumlah);
                }

                // Kurangi stok barang baru
                $stokBaru = StokModel::where('barang_id', $request->barang_id)->first();
                if (!$stokBaru || $stokBaru->stok_jumlah < $request->jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Stok ' . $barangBaru->barang_nama . ' tidak mencukupi.',
                    ], 422);
                }
                $stokBaru->decrement('stok_jumlah', $request->jumlah);

                $request->merge(['harga' => $hargaJualBaru]);
                $detail_penjualan->update($request->all());

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Detail penjualan berhasil diupdate'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate detail penjualan',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
        return redirect('/penjualan_detail');
    }

    public function confirm_ajax(string $detail_id)
    {
        $detail_penjualan = PenjualanDetailModel::with('barang')->findOrFail($detail_id);
        return view('penjualan_detail.confirm_ajax', compact('detail_penjualan'));
    }

    public function delete_ajax(string $detail_id)
    {
        if (request()->ajax() || request()->wantsJson()) {
            DB::beginTransaction();
            try {
                $detail_penjualan = PenjualanDetailModel::findOrFail($detail_id);
                $barangId = $detail_penjualan->barang_id;
                $jumlahDihapus = $detail_penjualan->jumlah;

                // Cari catatan stok yang sudah ada untuk barang terkait
                $stokSekarang = StokModel::where('barang_id', $barangId)->first();

                if ($stokSekarang) {
                    // Jika catatan stok ditemukan, tambahkan jumlah yang dihapus
                    $stokSekarang->increment('stok_jumlah', $jumlahDihapus);
                    $stokSekarang->save();
                }

                $detail_penjualan->delete();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Detail penjualan berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menghapus detail penjualan',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
        return redirect('/penjualan_detail');
    }
}
