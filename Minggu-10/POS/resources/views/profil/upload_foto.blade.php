<form action="{{ url('/profil/save') }}" method="POST" id="uploadForm" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="foto_profile">Pilih Foto</label>
                    <input type="file" name="foto_profile" id="foto_profile" class="form-control" required>
                    <small id="error_foto_profile" class="text-danger form-text"></small>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>

<script>
        $(document).ready(function () {
        $("#uploadForm").validate({
            rules: {
                foto_profile: {
                    required: true,
                    extension: "jpg|jpeg|png"
                }
            },
            submitHandler: function(form) {
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ url('/profil/save') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide'); 
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });then(() => {
                                location.reload(); 
                            });
                        } else {
                            if (response.msgField && response.msgField.foto_profile) {
                                $('#error_foto_profile').text(response.msgField.foto_profile[0]);
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });

                return false;
            },
            errorElement: 'small',
            errorPlacement: function (error, element) {
                error.addClass('form-text text-danger');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
