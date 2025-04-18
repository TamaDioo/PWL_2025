<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar seluruh transaksi penjualan yang tercatat dalam sistem'
        ];

        $activeMenu = 'penjualan'; // Set menu yang sedang aktif

        $barangs = BarangModel::all(); // Ambil data barang untuk filter
        $users = UserModel::all(); // Ambil data user untuk filter 

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barangs' => $barangs,
            'users' => $users,
            'activeMenu' => $activeMenu,
        ]);
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with(['user']);

        // Filter data penjualan berdasarkan tanggal penjualan
        if ($request->penjualan_tanggal) {
            $penjualans->whereDate('penjualan_tanggal', $request->penjualan_tanggal);
        }

        // Filter data penjualan berdasarkan nama pelanggan
        if ($request->pembeli) {
            $penjualans->where('pembeli', 'like', '%' . $request->pembeli . '%');
        }

        // Filter data penjualan berdasarkan user_id
        if ($request->user_id) {
            $penjualans->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->editColumn('penjualan_tanggal', function ($penjualan) {
                return Carbon::parse($penjualan->penjualan_tanggal)->format('d-m-Y H:i:s');
            })
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url('/penjualan/' . $penjualan->penjualan_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah data transaksi penjualan'
        ];

        $barang = BarangModel::all();
        $user = UserModel::all();
        $activeMenu = 'penjualan';

        return view('penjualan.create', compact('breadcrumb', 'page', 'barang', 'user', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:50',
            'penjualan_kode' => 'required|string|max:20',
            'penjualan_tanggal' => 'required|date',
            'barang_id' => 'required|array',
            'harga' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::create($request->all());

            foreach ($request->barang_id as $i => $barang_id) {
                $jumlah_beli = $request->jumlah[$i];
                $harga_satuan = $request->harga[$i];

                $barang = BarangModel::findOrFail($barang_id);
                $stok = StokModel::where('barang_id', $barang_id)->first();

                if (!$stok || $stok->stok_jumlah < $jumlah_beli) {
                    DB::rollBack();
                    return back()->withErrors(['stok' => 'Stok ' . $barang->barang_nama . ' tidak mencukupi.'])->withInput();
                }

                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_id,
                    'harga' => $harga_satuan,
                    'jumlah' => $jumlah_beli,
                ]);

                // Kurangi stok
                $stok->decrement('stok_jumlah', $jumlah_beli);
                $stok->save();
            }

            DB::commit();
            return redirect('/penjualan')->with('success', 'Data transaksi penjualan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data penjualan.'])->withInput();
        }
    }

    public function edit($id)
    {
        $penjualan = PenjualanModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit transaksi penjualan'
        ];

        $activeMenu = 'penjualan';

        $barang = BarangModel::all();
        $user = UserModel::all();

        return view('penjualan.edit', compact('breadcrumb', 'page', 'penjualan', 'user', 'activeMenu', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:50',
            'penjualan_kode' => 'required|string|max:20',
            'penjualan_tanggal' => 'required|date',
            'barang_id' => 'required|array',
            'harga' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Hitung total harga
            $total_harga = 0;
            foreach ($request->barang_id as $i => $barang_id) {
                $total_harga += $request->harga[$i] * $request->jumlah[$i];
            }

            // Update data penjualan
            $penjualan = PenjualanModel::find($id);
            $penjualan->update([
                'user_id' => $request->user_id,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
                'total_harga' => $total_harga,
            ]);

            // Ambil detail penjualan sebelumnya untuk mengembalikan stok
            $previousDetails = PenjualanDetailModel::where('penjualan_id', $id)->get();
            foreach ($previousDetails as $detail) {
                $stok = StokModel::where('barang_id', $detail->barang_id)->first();
                if ($stok) {
                    $stok->increment('stok_jumlah', $detail->jumlah);
                    $stok->save();
                }
            }

            // Menghapus semua detail sebelumnya
            PenjualanDetailModel::where('penjualan_id', $id)->delete();

            // Menyimpan detail yang baru dan mengurangi stok
            foreach ($request->barang_id as $i => $barang_id) {
                $jumlah_beli = $request->jumlah[$i];
                $harga_satuan = $request->harga[$i];

                $barang = BarangModel::findOrFail($barang_id);
                $stok = StokModel::where('barang_id', $barang_id)->first();

                if (!$stok || $stok->stok_jumlah < $jumlah_beli) {
                    DB::rollBack();
                    return back()->withErrors(['stok' => 'Stok ' . $barang->barang_nama . ' tidak mencukupi.'])->withInput();
                }

                PenjualanDetailModel::create([
                    'penjualan_id' => $id,
                    'barang_id' => $barang_id,
                    'harga' => $harga_satuan,
                    'jumlah' => $jumlah_beli,
                ]);

                // Kurangi stok
                $stok->decrement('stok_jumlah', $jumlah_beli);
                $stok->save();
            }

            DB::commit();
            return redirect('/penjualan')->with('success', 'Data transaksi penjualan berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengubah data penjualan.'])->withInput();
        }
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with(['user', 'penjualanDetail.barang'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail transaksi penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', compact('breadcrumb', 'page', 'penjualan', 'activeMenu'));
    }

    public function destroy($id)
    {
        $check = PenjualanModel::find($id);

        if (!$check) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok barang yang terkait dengan penjualan ini
            $details = PenjualanDetailModel::where('penjualan_id', $id)->get();
            foreach ($details as $detail) {
                $stok = StokModel::where('barang_id', $detail->barang_id)->first();
                if ($stok) {
                    $stok->increment('stok_jumlah', $detail->jumlah);
                    $stok->save();
                }
            }

            // Menghapus detail penjualan terlebih dahulu
            PenjualanDetailModel::where('penjualan_id', $id)->delete();

            // Lalu hapus data penjualan utama
            PenjualanModel::destroy($id);

            DB::commit();
            return redirect('/penjualan')->with('success', 'Data transaksi penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return redirect('/penjualan')->with('error', 'Gagal menghapus data penjualan karena masih terhubung dengan data lain');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/penjualan')->with('error', 'Terjadi kesalahan saat menghapus penjualan.');
        }
    }
}
