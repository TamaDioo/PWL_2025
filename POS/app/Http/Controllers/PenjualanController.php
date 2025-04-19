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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

        // Filter data penjualan berdasarkan nama pembeli
        if ($request->pembeli) {
            $penjualans->where('pembeli', 'like', '%' . $request->pembeli . '%');
        }

        // Filter data penjualan berdasarkan user_id
        if ($request->user_id) {
            $penjualans->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('total_harga', function ($penjualan) {
                $total = 0;
                foreach ($penjualan->penjualanDetail as $detail) {
                    $total += $detail->harga * $detail->jumlah;
                }
                return $total;
            })
            ->editColumn('penjualan_tanggal', function ($penjualan) {
                return Carbon::parse($penjualan->penjualan_tanggal)->format('d-m-Y H:i:s');
            })
            ->addColumn('aksi', function ($penjualan) {
                // $btn = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' .
                //     url('/penjualan/' . $penjualan->penjualan_id) . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
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
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::create($request->all());

            foreach ($request->barang_id as $i => $barang_id) {
                $jumlah_beli = $request->jumlah[$i];

                $barang = BarangModel::findOrFail($barang_id);
                $stok = StokModel::where('barang_id', $barang_id)->first();

                if (!$stok || $stok->stok_jumlah < $jumlah_beli) {
                    DB::rollBack();
                    return back()->withErrors(['stok' => 'Stok ' . $barang->barang_nama . ' tidak mencukupi.'])->withInput();
                }

                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_id,
                    'harga' => $barang->harga_jual, // Ambil harga dari harga_jual barang
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
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Hitung total harga
            $total_harga = 0;
            foreach ($request->barang_id as $i => $barang_id) {
                $barang = BarangModel::findOrFail($barang_id);
                $total_harga += $barang->harga_jual * $request->jumlah[$i];
            }

            // Update data penjualan
            $penjualan = PenjualanModel::find($id);
            $penjualan->update([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
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

                $barang = BarangModel::findOrFail($barang_id);
                $stok = StokModel::where('barang_id', $barang_id)->first();

                if (!$stok || $stok->stok_jumlah < $jumlah_beli) {
                    DB::rollBack();
                    return back()->withErrors(['stok' => 'Stok ' . $barang->barang_nama . ' tidak mencukupi.'])->withInput();
                }

                PenjualanDetailModel::create([
                    'penjualan_id' => $id,
                    'barang_id' => $barang_id,
                    'harga' => $barang->harga_jual, // Ambil harga dari harga_jual barang
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

    public function create_ajax()
    {
        $users = UserModel::select('user_id', 'nama')->get();
        return view('penjualan.create_ajax', compact('users'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:50',
                'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            PenjualanModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        }
        redirect('/penjualan');
    }

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail')->find($id);

        if ($penjualan) {
            $totalHarga = $penjualan->penjualanDetail->sum(function ($detail) {
                return $detail->harga * $detail->jumlah;
            });
            $penjualan->total_harga = $totalHarga; // Menambahkan atribut secara manual
        }

        return view('penjualan.show_ajax', compact('penjualan'));
    }

    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $users = UserModel::select('user_id', 'nama')->get();
        return view('penjualan.edit_ajax', compact('penjualan', 'users'));
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:50',
                'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
                'penjualan_tanggal' => 'required|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $check = PenjualanModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/penjualan');
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        return view('penjualan.confirm_ajax', compact('penjualan'));
    }

    public function delete_ajax(string $id)
    {
        if (request()->ajax() || request()->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                try {
                    $penjualan->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data penjualan berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data penjualan gagal dihapus karena masih terdapat detail penjualan terkait'
                    ], 422);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
        }
        return redirect('/penjualan');
    }

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 2MB
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:2048']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_penjualan'); // ambil file dari request

            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel

            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'user_id' => $value['A'],
                            'penjualan_kode' => $value['B'],
                            'pembeli' => $value['C'],
                            'penjualan_tanggal' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    PenjualanModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        // ambil data penjualan yang akan di export
        $penjualan = PenjualanModel::select('user_id', 'penjualan_kode', 'pembeli', 'penjualan_tanggal')
            ->orderBy('penjualan_kode')
            ->with('user')
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Tanggal Penjualan');
        $sheet->setCellValue('E1', 'Kasir');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true); // bold header

        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke-2
        foreach ($penjualan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->penjualan_kode);
            $sheet->setCellValue('C' . $baris, $value->pembeli);
            $sheet->setCellValue('D' . $baris, $value->penjualan_tanggal);
            $sheet->setCellValue('E' . $baris, $value->user->nama); // ambil nama user
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Penjualan'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d H:i:s') . '.xlsx';

        // Menyiapkan header untuk file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    } // end function export_excel

    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('user_id', 'penjualan_kode', 'pembeli', 'penjualan_tanggal')
            ->orderBy('penjualan_kode')
            ->orderBy('penjualan_tanggal')
            ->with('user')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf PDF
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi 
        $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari URL
        $pdf->render();

        return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
