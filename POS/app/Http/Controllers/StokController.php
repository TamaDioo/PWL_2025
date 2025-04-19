<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    // Menampilkan halaman awal daftar stok
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok barang yang tercatat dalam sistem'
        ];

        $activeMenu = 'stok'; // set menu yang sedang aktif

        $suppliers = SupplierModel::all(); // ambil data supplier untuk filter
        $users = UserModel::all(); // ambil data user untuk filter
        $barangs = BarangModel::all(); // ambil data barang untuk filter

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'suppliers' => $suppliers,
            'users' => $users,
            'barangs' => $barangs,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data stok dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with(['supplier', 'barang', 'user']);

        // Filter data stok berdasarkan supplier_id
        if ($request->supplier_id) {
            $stoks->where('supplier_id', $request->supplier_id);
        }

        // Filter data stok berdasarkan user_id
        if ($request->user_id) {
            $stoks->where('user_id', $request->user_id);
        }

        // Filter data stok berdasarkan barang_id
        if ($request->barang_id) {
            $stoks->where('barang_id', $request->barang_id);
        }

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->editColumn('stok_tanggal', function ($stok) {
                return Carbon::parse($stok->stok_tanggal)->format('d-m-Y H:i:s');
            })
            ->addColumn('aksi', function ($stok) {
                // $btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="' . url('/stok/' . $stok->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' .
                //     url('/stok/' . $stok->stok_id) . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah stok
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah catatan stok baru'
        ];

        $suppliers = SupplierModel::all();
        $users = UserModel::all();
        $barangs = BarangModel::all();
        $activeMenu = 'stok'; // set menu yang sedang aktif

        return view('stok.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'suppliers' => $suppliers,
            'users' => $users,
            'barangs' => $barangs,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data stok baru
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:0',
        ]);

        StokModel::create($request->all());

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    // Menampilkan detail stok
    public function show(string $id)
    {
        $stok = StokModel::with(['supplier', 'barang', 'user'])->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Stok'
        ];

        $activeMenu = 'stok'; // Set menu yang sedang aktif

        return view('stok.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'stok' => $stok,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit stok
    public function edit(string $id)
    {
        $stok = StokModel::find($id);
        $suppliers = SupplierModel::all();
        $users = UserModel::all();
        $barangs = BarangModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Stok'
        ];

        $activeMenu = 'stok'; // set menu yang sedang aktif

        return view('stok.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'stok' => $stok,
            'suppliers' => $suppliers,
            'users' => $users,
            'barangs' => $barangs,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data stok
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:0',
        ]);

        StokModel::find($id)->update($request->all());

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    // Menghapus data stok
    public function destroy(string $id)
    {
        $check = StokModel::find($id);
        if (!$check) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id);
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax()
    {
        $users = UserModel::all();
        $barangs = BarangModel::all();

        return view('penjualan.create_ajax')
            ->with('users', $users)
            ->with('barangs', $barangs);
    }

    // Menyimpan data stok baru via Ajax
    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'user_id' => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            StokModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }
        redirect('/stok');
    }

    // Menampilkan detail stok via Ajax
    public function show_ajax(string $id)
    {
        $stok = StokModel::with(['supplier', 'barang', 'user'])->find($id);

        return view('stok.show_ajax', ['stok' => $stok]);
    }

    // Menampilkan form edit stok via Ajax
    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $suppliers = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $users = UserModel::select('user_id', 'nama')->get();
        $barangs = BarangModel::select('barang_id', 'barang_nama')->get();

        return view('stok.edit_ajax', [
            'stok' => $stok,
            'suppliers' => $suppliers,
            'users' => $users,
            'barangs' => $barangs,
        ]);
    }

    // Menyimpan perubahan data stok via Ajax
    public function update_ajax(Request $request, string $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'user_id' => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer|min:0',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = StokModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/stok');
    }

    // Menampilkan konfirmasi hapus stok via Ajax
    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    // Menghapus data stok via Ajax
    public function delete_ajax(string $id)
    {
        // cek apakah request dari ajax
        if (request()->ajax() || request()->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                try {
                    $stok->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                    ], 422);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
        }
        return redirect('/stok');
    }
}
