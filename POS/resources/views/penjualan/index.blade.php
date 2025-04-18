@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Transaksi Penjualan</h3>
            <div class="card-tools">
                <a href="{{ url('/penjualan/create') }}" class="btn btn-success">Tambah Penjualan</a>
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_tanggal" class="col-md-3 col-form-label">Tanggal</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control form-control-sm filter_tanggal" id="filter_tanggal">
                                <small class="form-text text-muted">Filter Berdasarkan Tanggal Penjualan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_pelanggan" class="col-md-3 col-form-label">Pembeli</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-sm filter_pelanggan" id="filter_pelanggan" placeholder="Cari nama pembeli">
                                <small class="form-text text-muted">Filter Berdasarkan Pembeli</small>
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
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Penjualan</th>
                        <th>Pembeli</th>
                        <th>User</th>
                        <th>Tanggal Penjualan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        var dataPenjualan;
        $(document).ready(function() {
            dataPenjualan = $('#table_penjualan').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.user_id = $('.filter_user').val();
                        d.penjualan_tanggal = $('.filter_tanggal').val();
                        d.pembeli = $('.filter_pelanggan').val();    
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "penjualan_kode", className: "", orderable: true, searchable: false },
                    { data: "pembeli", className: "", orderable: true, searchable: false },
                    { data: "user.nama", className: "", orderable: true, searchable: true },
                    { data: "penjualan_tanggal", className: "", orderable: true, searchable: false },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ]
            });

            $('.filter_user, .filter_pelanggan, .filter_tanggal').change(function(){
                dataPenjualan.draw();
            });

            $('#table_penjualan_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    dataPenjualan.search(this.value).draw();
                }
            });
        });
    </script>
@endpush