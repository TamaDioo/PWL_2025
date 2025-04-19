<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Penjualan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if(empty($penjualan))
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data penjualan yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th>Kode Penjualan</th>
                        <td>{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $penjualan->pembeli ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $penjualan->user->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Penjualan</th>
                        <td>{{ \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    @if(isset($penjualan->total_harga))
                    <tr>
                        <th>Total Harga</th>
                        <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }},00</td>
                    </tr>
                    @endif
                </table>
            @endif
        </div>
        <div class="modal-footer">
            <a href="{{ route('penjualan.struk', $penjualan->penjualan_id) }}" class="btn btn-primary" target="_blank">Cetak Struk</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>