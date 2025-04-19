@if(empty($penjualans))
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data penjualan belum tersedia.
                </div>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan_detail/ajax') }}" method="POST" id="form-tambah-detail-penjualan">
        @csrf
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Detail Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="penjualan_id">Kode Penjualan</label>
                        <select name="penjualan_id" id="penjualan_id" class="form-control" required>
                            <option value="">- Pilih Kode Penjualan -</option>
                            @foreach($penjualans as $p)
                                <option value="{{ $p->penjualan_id }}">{{ $p->penjualan_kode }}</option>
                            @endforeach
                        </select>
                        <small id="error-penjualan_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="barang_id">Barang</label>
                        <select name="barang_id" id="barang_id" class="form-control" required>
                            <option value="">- Pilih Barang -</option>
                            @if(isset($barangs))
                                @foreach($barangs as $b)
                                    @php
                                        $totalStok = $stoks->has($b->barang_id) ? $stoks[$b->barang_id]->total_stok : 0;
                                    @endphp
                                    <option value="{{ $b->barang_id }}" data-stok="{{ $totalStok }}" data-harga="{{ $b->harga_jual }}">{{ $b->barang_nama }} (Stok: {{ $totalStok }}, Harga Jual: {{ number_format($b->harga_jual, 0, ',', '.') }})</option>
                                @endforeach
                            @endif
                        </select>
                        <small id="error-barang_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1">
                        <small id="error-jumlah" class="error-text form-text text-danger"></small>
                        <small id="info-stok" class="form-text text-muted"></small>
                    </div>
                    <input type="hidden" name="harga" id="harga" value="">
                    <script>
                        $(document).ready(function() {
                            $('#barang_id').change(function() {
                                var selectedOption = $(this).find(':selected');
                                var stokTersedia = selectedOption.data('stok');
                                var hargaJual = selectedOption.data('harga');
                                $('#info-stok').text('Stok tersedia: ' + stokTersedia);
                                $('#harga').val(hargaJual); // Set nilai hidden input harga
                            });
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#form-tambah-detail-penjualan").validate({
                rules: {
                    penjualan_id: {required: true, number: true},
                    barang_id: {required: true, number: true},
                    jumlah: {required: true, number: true, min: 1},
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if(response.status) {
                                $('#myModal').modal('hide'); // Sesuaikan dengan ID modal Anda
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        if (typeof detailPenjualan !== 'undefined') {
                                            detailPenjualan.ajax.reload();
                                        } else {
                                            location.reload();
                                        }
                                    }
                                });
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-'+prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat mengirim data.'
                            });
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('#barang_id').change(function() {
                var selectedOption = $(this).find(':selected');
                var stokTersedia = selectedOption.data('stok');
                $('#info-stok').text('Stok tersedia: ' + stokTersedia);
            });
        });
    </script>
@endempty