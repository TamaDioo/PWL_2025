@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Profil Saya</h3>
            <div class="card-tools">
                    <button onclick="modalAction('{{ url('profil/upload') }}')" class="btn btn-primary">Ubah Foto Profil</button>
            </div>
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                @if ($user->foto_profile)
                    <img src="{{ asset('storage/' . $user->foto_profile) }}" class="img-thumbnail rounded-circle" width="150">
                @else
                    <img src="{{ asset('adminlte/dist/img/avatar.png') }}" class="img-thumbnail rounded-circle" width="150">
                @endif
            </div>
            
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>ID</th>
                    <td>{{ $user->user_id }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>{{ $user->level->level_nama }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $user->nama }}</td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>********</td>
                </tr>
            </table>
        </div>
    </div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

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
                    url: "{{ url('profil/save') }}",
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
                            }).then(() => {
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
    });
</script>
@endpush
