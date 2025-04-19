@empty($detail_penjualan)
    <div class="alert alert-danger">Data detail penjualan tidak ditemukan.</div>
@else
    <form action="{{ url('/penjualan_detail/' . $detail_penjualan->detail_id.'/update_ajax') }}" method="POST" id="form-edit-detail-penjualan">
        @csrf
        @method('PUT')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Detail Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="barang_id">Barang</label>
                        <select name="barang_id" id="barang_id" class="form-control" required>
                            <option value="">- Pilih Barang -</option>
                            @if(isset($barangs))
                                @foreach($barangs as $b)
                                    @php
                                        $totalStok = $stoks->has($b->barang_id) ? $stoks[$b->barang_id]->total_stok : 0;
                                        $selected = ($b->barang_id == $detail_penjualan->barang_id) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $b->barang_id }}" data-stok="{{ $totalStok }}" data-harga="{{ $b->harga_jual }}" {{ $selected }}>{{ $b->barang_nama }} (Stok: {{ $totalStok }}, Harga Jual: {{ number_format($b->harga_jual, 0, ',', '.') }})</option>
                                @endforeach
                            @endif
                        </select>
                        <small id="error-barang_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1" value="{{ $detail_penjualan->jumlah }}">
                        <small id="error-jumlah" class="error-text form-text text-danger"></small>
                        <small id="info-stok" class="form-text text-muted"></small>
                    </div>
                    <input type="hidden" name="harga" id="harga" value="{{ $detail_penjualan->harga }}">
                    <input type="hidden" name="penjualan_id" value="{{ $detail_penjualan->penjualan_id }}"> </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $('#barang_id').change(function() {
                var selectedOption = $(this).find(':selected');
                var stokTersedia = selectedOption.data('stok');
                var hargaJual = selectedOption.data('harga');
                $('#info-stok').text('Stok tersedia: ' + stokTersedia);
                $('#harga').val(hargaJual);
            });
            var selectedBarang = $('#barang_id').find(':selected');
            $('#info-stok').text('Stok tersedia: ' + selectedBarang.data('stok'));
        });
        $("#form-edit-detail-penjualan").validate({
            rules: {
                barang_id: {required: true, number: true},
                jumlah: {required: true, number: true, min: 1},
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json', // Harapkan respons dalam format JSON
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Tambahkan header ini
                    },
                    success: function(response) {
                        if(response.status) {
                            $('#myModal').modal('hide');
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
</script>
@endempty