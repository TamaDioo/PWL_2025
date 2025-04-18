@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Penjualan</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('penjualan') }}" class="form-horizontal">
            @csrf
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">User</label>
                    <div class="col-10">
                        <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">- Pilih User -</option>
                            @foreach($user as $item)
                                <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Pembeli</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="pembeli" name="pembeli" value="{{ old('pembeli') }}" required>
                        @error('pembeli')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Kode Penjualan</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode" value="{{ old('penjualan_kode') }}" required>
                        @error('penjualan_kode')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Tanggal Penjualan</label>
                    <div class="col-10">
                        <input type="datetime-local" class="form-control" id="penjualan_tanggal" name="penjualan_tanggal" value="{{ old('penjualan_tanggal') }}" required>
                        @error('penjualan_tanggal')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Detail Penjualan --}}
                <div>
                    <h5 class="mt-4" style="font-weight: 400">Detail Penjualan</h5>
                </div>
                <div id="detailBarang">
                    <div class="barang-detail">
                        <div class="form-group row">
                            <label class="col-2 control-label col-form-label">Barang</label>
                            <div class="col-10">
                            <select name="barang_id[]" class="form-control" required>
                                <option value="">- Pilih Barang -</option>
                                    @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}" data-harga="{{ $item->harga_jual }}">{{ $item->barang_nama }}</option>
                                    @endforeach
                            </select>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label class="col-2 control-label col-form-label">Harga</label>
                            <div class="col-10">
                                <input type="number" class="form-control" name="harga[]" required>
                            </div>
                        </div> --}}
                        <div class="form-group row">
                            <label class="col-2 control-label col-form-label">Jumlah</label>
                            <div class="col-10">
                                <input type="number" class="form-control" name="jumlah[]" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-10 offset-2">
                        <button type="button" class="btn btn-success btn-sm" id="tambahBarang">Tambah Barang</button>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-2 control-label col-form-label"></label>
                    <div class="col-10">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('penjualan') }}">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('#barang_id').on('change', function () {
                var selected = $(this).find('option:selected');
                var harga = selected.data('harga');
                $('#harga').val(harga);
            });
            $('#tambahBarang').on('click', function () {
                var barangDetail = $('.barang-detail').first().clone();
                barangDetail.find('input').val('');  
                $('#detailBarang').append(barangDetail);  
            });
        });
    </script>
@endpush