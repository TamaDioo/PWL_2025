@extends('layouts.template')

@section('content')
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/penjualan_detail/import') }}')" class="btn btn-info">Import Data</button>
                <a href="{{ url('/penjualan_detail/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Data (PDF)</a>
                <a href="{{ url('/penjualan_detail/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Data</a>
                <button onclick="modalAction('{{ url('/penjualan_detail/create_ajax') }}')" class="btn btn-success">Tambah Ajax</button>
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_penjualan" class="col-md-3 col-form-label">Penjualan</label>
                            <div class="col-md-9">
                                <select name="filter_penjualan" class="form-control form-control-sm filter_penjualan">
                                    <option value="">- Semua -</option>
                                    @foreach($penjualans as $penjualan)
                                        <option value="{{ $penjualan->penjualan_id }}">{{ $penjualan->penjualan_kode }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Filter Berdasarkan kode penjualan</small>
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
                                <small class="form-text text-muted">Filter Berdasarkan barang</small>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_detail_penjualan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Penjualan</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var detailPenjualan;
        $(document).ready(function() {
            detailPenjualan = $('#table_detail_penjualan').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan_detail/list') }}", // Route untuk menampilkan semua detail
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.penjualan_id = $('.filter_penjualan').val();
                        d.barang_id = $('.filter_barang').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "kode_penjualan", name: 'penjualan.penjualan_kode', orderable: true, searchable: true },
                    { data: "nama_barang", name: 'barang.barang_nama', orderable: true, searchable: true },
                    { data: function(row) {
                        return parseFloat(row.harga).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                    }, className: "", orderable: false, searchable: false },
                    { data: "jumlah", className: "text-center", orderable: true, searchable: false },
                    { data: function(row) {
                        return parseFloat(row.harga * row.jumlah).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                    }, className: "", orderable: false, searchable: false },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ]
            });

            $('.filter_penjualan, .filter_barang').change(function(){
                detailPenjualan.draw();
            });

            $('#table_detail_penjualan_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    detailPenjualan.search(this.value).draw();
                }
            });

        });
    </script>
@endpush