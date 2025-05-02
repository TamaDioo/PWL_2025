@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Penjualan</h3>
        </div>
        <div class="card-body">
            @empty($penjualan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('/penjualan/' . $penjualan->penjualan_id) }}" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">User</label>
                    <div class="col-10">
                        <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">- Pilih User -</option>
                            @foreach($user as $item)
                                <option value="{{ $item->user_id }}" @if($item->user_id == $penjualan->user_id) selected @endif>{{ $item->nama }}</option>
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
                        <input type="text" class="form-control" id="pembeli" name="pembeli" value="{{ old('pembeli', $penjualan->pembeli) }}" required>
                        @error('pembeli')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Kode Penjualan</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode" value="{{ old('penjualan_kode', $penjualan->penjualan_kode) }}" required>
                        @error('penjualan_kode')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Tanggal Penjualan</label>
                    <div class="col-10">
                        <input type="text" name="penjualan_tanggal" class="form-control" value="{{ old('penjualan_tanggal', $penjualan->penjualan_tanggal) }}">
                        @error('penjualan_tanggal')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Detail Penjualan --}}
                <div>
                    <h5 class="mt-4" style="font-weight: 400">Detail Penjualan</h5>
                </div>

                @foreach ($penjualan->penjualanDetail as $index => $detail)
                    <div class="border p-3 mb-3">
                        <div class="form-group row">
                            <label class="col-2 control-label col-form-label">Barang</label>
                            <div class="col-10">
                                <select class="form-control" name="detail[{{ $index }}][barang_id]" required>
                                <option value="">- Pilih Barang -</option>
                                    @foreach ($barang as $item)
                                        <option value="{{ $item->barang_id }}" @if($item->barang_id == $detail->barang_id) selected @endif>{{ $item->barang_nama }}</option>
                                    @endforeach
                                </select>
                                @error("detail.$index.barang_id")
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 control-label col-form-label">Jumlah</label>
                            <div class="col-10">
                                <input type="number" class="form-control" name="detail[{{ $index }}][jumlah]" value="{{ $detail->jumlah }}"
                                required min="1">
                                @error("detail.$index.jumlah")
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="form-group row">
                    <label class="col-2 control-label col-form-label"></label>
                    <div class="col-10">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('penjualan') }}">Kembali</a>
                    </div>
                </div>
                </form>
            @endempty
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush