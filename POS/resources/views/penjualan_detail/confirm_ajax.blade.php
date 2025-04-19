@empty($detail_penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data detail penjualan tidak ditemukan.
                </div>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan_detail/' . $detail_penjualan->detail_id.'/delete_ajax') }}" method="POST" id="form-delete-detail-penjualan">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Detail Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi Hapus !!!</h5>
                        Apakah Anda yakin ingin menghapus detail penjualan berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Barang :</th>
                            <td class="col-9">{{ $detail_penjualan->barang->barang_nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jumlah :</th>
                            <td class="col-9">{{ $detail_penjualan->jumlah }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Harga Satuan :</th>
                            <td class="col-9">{{ number_format($detail_penjualan->harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Subtotal :</th>
                            <td class="col-9">{{ number_format($detail_penjualan->harga * $detail_penjualan->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-delete-detail-penjualan").validate({
                rules: {}, // Tidak ada aturan validasi untuk form konfirmasi
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat menghapus data.'
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
        });
    </script>
@endempty