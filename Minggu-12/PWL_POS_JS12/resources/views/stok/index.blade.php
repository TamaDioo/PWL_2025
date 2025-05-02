@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Stok Barang</h3>
            <div class="card-tools">
                {{-- <a href="{{ url('/stok/create') }}" class="btn btn-primary">Tambah Stok</a> --}}
                <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">Import Stok</button>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Stok (PDF)</a>
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Stok</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_supplier" class="col-md-3 col-form-label">Supplier</label>
                            <div class="col-md-9">
                                <select name="filter_supplier" class="form-control form-control-sm filter_supplier">
                                    <option value="">- Semua -</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Filter Berdasarkan Supplier</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_user" class="col-md-3 col-form-label">User</label>
                            <div class="col-md-9">
                                <select name="filter_user" class="form-control form-control-sm filter_user">
                                    <option value="">- Semua -</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Filter Berdasarkan User</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_barang" class="col-md-3 col-form-label">Barang</label>
                            <div class="col-md-9">
                                <select name="filter_barang" class="form-control form-control-sm filter_barang">
                                    <option value="">- Semua -</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->barang_id }}">{{ $barang->barang_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Filter Berdasarkan Barang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>Supplier</th>
                        <th>User</th>
                        <th>Tanggal Stok</th>
                        <th>Jumlah Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '')  {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataStok;
        $(document).ready(function() {
            dataStok = $('#table_stok').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.supplier_id = $('.filter_supplier').val();
                        d.user_id = $('.filter_user').val();
                        d.barang_id = $('.filter_barang').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "barang.barang_nama", className: "", orderable: true, searchable: true },
                    { data: "supplier.supplier_nama", className: "", orderable: true, searchable: true },
                    { data: "user.nama", className: "", orderable: true, searchable: true },
                    { data: "stok_tanggal", className: "", orderable: true, searchable: false },
                    { data: "stok_jumlah", className: "", orderable: true, searchable: false },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ]
            });

            $('.filter_supplier, .filter_user, .filter_barang').change(function(){
                dataStok.draw();
            });

            $('#table_stok_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    dataStok.search(this.value).draw();
                }
            });
        });
    </script>
@endpush

{{-- Implementasi JS 5 - view barang.index
@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option value="">- Semua -</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Barang</small>
                        </div>
                    </div>
                </div>
            </div>            
            <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
                <thead>
                    <tr><th>ID</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Beli</th><th>Harga Jual</th><th>Kategori Barang</th><th>Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            var dataBarang = $('#table_barang').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('barang/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) { 
                        d.kategori_id = $('#kategori_id').val();
                     }
                },
                columns: [
                    {
                        data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "barang_kode",
                        className: "",
                        // orderable: true, jika ingin kolom ini bisa diurutkan
                        orderable: true,
                        // searchable: true, jika ingin kolom ini bisa dicari
                        searchable: true
                    },{
                        data: "barang_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "harga_beli",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "harga_jual",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        // mengambil data kategori hasil dari ORM berelasi
                        data: "kategori.kategori_nama",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "aksi",
                        className: "",
                        orderable: false,   // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false   // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });

            $('#kategori_id').on('change', function () { 
                dataBarang.ajax.reload();
            });
            
        });
    </script>
@endpush --}}