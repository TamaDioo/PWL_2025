<form action="{{ url('/profil/update_ajax') }}" method="POST" id="editProfileForm">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ $user->nama }}" required>
                    <span id="error_nama" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" required>
                    <span id="error_username" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small class="form-text text-muted">Abaikan jika tidak ingin ubah password</small>
                    <span id="error_password" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    <span id="error_password_confirmation" class="text-danger"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#editProfileForm").validate({
            rules: {
                nama: {
                    required: true,
                    maxlength: 100
                },
                username: {
                    required: true,
                    maxlength: 20,
                    remote: {
                        url: "{{ url('profil/check-username') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            username: function() {
                                return $("#username").val();
                            },
                            old_username: "{{ $user->username }}"
                        }
                    }
                },
                password: {
                    minlength: 8
                },
                password_confirmation: {
                    minlength: 8,
                    equalTo: "#password"
                }
            },
            messages: {
                username: {
                    remote: "Username sudah digunakan."
                },
                password_confirmation: {
                    equalTo: "Konfirmasi password tidak sesuai."
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });

        $("#editProfileForm").submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            if ($("#editProfileForm").valid()) { // Check if the form is valid
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ url('profil/update_ajax') }}",
                    method: "POST",
                    data: formData,
                    dataType: "json", // **Pastikan ini ada**
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                            if (response.msgField) {
                                $.each(response.msgField, function(field, errors) {
                                    $('#error_' + field).text(errors[0]);
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) { // Tambahkan ini
                        console.error("AJAX Error:", status, error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Jaringan',
                            text: 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.',
                        });
                    }
                });
            }
        });
    });
</script>