<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Penjualan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if(empty($detail_penjualan))
                <div class="alert alert-danger">Data detail penjualan tidak ditemukan.</div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID Detail</th>
                        <td>{{ $detail_penjualan->detail_id }}</td>
                    </tr>
                    <tr>
                        <th>Barang</th>
                        <td>{{ $detail_penjualan->barang->barang_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($detail_penjualan->harga, 0, ',', '.') }},00</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>{{ $detail_penjualan->jumlah }}</td>
                    </tr>
                    <tr>
                        <th>Subtotal</th>
                        <td>Rp {{ number_format($detail_penjualan->harga * $detail_penjualan->jumlah, 0, ',', '.') }},00</td>
                    </tr>
                    </table>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>