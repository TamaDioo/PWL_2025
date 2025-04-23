@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Detail Penjualan</h3>
    </div>
    <div class="card-body">
        @empty($penjualan)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data yang Anda cari tidak ditemukan.
        </div>
        @else
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID Penjualan</th>
                <td>{{ $penjualan->penjualan_id }}</td>
            </tr>
            <tr>
                <th>Kode Penjualan</th>
                <td>{{ $penjualan->penjualan_kode }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $penjualan->penjualan_tanggal }}</td>
            </tr>
            <tr>
                <th>Pengguna</th>
                <td>{{ $penjualan->user->nama ?? '-' }}</td>
            </tr>
        </table>

        <h5 class="mt-4">Detail Barang Terjual:</h5>
        <table class="table table-bordered table-striped table-hover table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penjualan->penjualanDetail as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->barang->barang_nama ?? '-' }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data detail penjualan.</td>
                </tr>
                @endforelse
            </tbody>
            @if($penjualan->penjualanDetail->count() > 0)
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total Harga</th>
                    <th>
                        Rp {{ number_format($penjualan->penjualanDetail->sum(fn($d) => $d->harga * $d->jumlah), 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
            @endif
        </table>
        @endempty

        <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush