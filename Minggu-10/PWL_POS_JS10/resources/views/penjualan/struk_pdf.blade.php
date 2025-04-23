<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: monospace; 
            margin: 5px;
            font-size: 12pt;
            line-height: 1.2;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .underline {
            text-decoration: underline;
        }
        .dashed-line {
            border-bottom: 1px dashed #000;
            margin-bottom: 5px;
            padding-bottom: 5px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
        }
        .item-name {
            flex-grow: 1;
        }
        .item-qty, .item-price, .item-subtotal {
            width: auto;
            padding-left: 5px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="text-center bold">Tama Dio Store</div>
    <div class="text-center">Kidal - Tumpang</div>
    <div class="text-center">085704024108</div>
    <div class="dashed-line"></div>

    <div class="item-row">
        <div class="item-name">Kode Penjualan:</div>
        <div class="item-value">{{ $penjualan->penjualan_kode }}</div>
    </div>
    <div class="item-row">
        <div class="item-name">Tanggal:</div>
        <div class="item-value">{{ $penjualan->penjualan_tanggal->format('d-m-Y H:i:s') }}</div>
    </div>
    <div class="item-row">
        <div class="item-name">Kasir:</div>
        <div class="item-value">{{ $penjualan->user->nama ?? '-' }}</div>
    </div>
    <div class="dashed-line"></div>

    <div class="bold">Detail Barang:</div>
    @forelse ($penjualan->penjualanDetail as $item)
        <div class="item-row">
            <div class="item-name">{{ $item->barang->barang_nama ?? '-' }}</div>
            <div class="item-qty">{{ $item->jumlah }} x</div>
            <div class="item-price text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
        </div>
        <div class="item-row">
            <div class="item-name"></div>
            <div class="item-subtotal text-right">Subtotal: Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</div>
        </div>
    @empty
        <div>Tidak ada detail barang.</div>
    @endforelse

    <div class="dashed-line"></div>

    <div class="total-row bold">
        <div>Total:</div>
        <div class="text-right">Rp {{ number_format($penjualan->penjualanDetail->sum(fn($d) => $d->harga * $d->jumlah), 0, ',', '.') }}</div>
    </div>

    <div class="dashed-line"></div>
    <div class="text-center">Terima Kasih Atas Kunjungan Anda!</div>
    <div class="text-center">{{ date('d-m-Y H:i:s') }}</div>
</body>
</html>