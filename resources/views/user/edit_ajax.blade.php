@empty($user)
     <div id="modal-master" class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="alert alert-danger">
                     <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                     Data yang Anda cari tidak ditemukan.
                 </div>
                 <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
             </div>
         </div>
     </div>
 @else
     <form action="{{ url('/user/' . $user->id_user . '/update_ajax') }}" method="POST" id="form-edit">
         @csrf
         @method('PUT')
         <div id="modal-master" class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="form-group">
                         <label>Level Pengguna</label>
                         <select name="id_level" id="id_level" class="form-control" required>
                             <option value="">- Pilih Level -</option>
                             @foreach($level as $l)
                                 <option {{ ($l->id_level == $user->id_level) ? 'selected' : '' }}
                                     value="{{ $l->id_level }}">{{ $l->level_nama }}
                                 </option>
                             @endforeach
                         </select>
                         <small id="error-id_level" class="error-text form-text text-danger"></small>
                     </div>
                     <div class="form-group">
                         <label>username</label>
                         <input value="{{ $user->user_kode }}" type="text" name="user_kode" id="user_kode" class="form-control" required>
                         <small id="error-user_kode" class="error-text form-text text-danger"></small>
                     </div>
                     <div class="form-group">
                         <label>Nama</label>
                         <input value="{{ $user->name }}" type="text" name="nama" id="nama" class="form-control" required>
                         <small id="error-nama" class="error-text form-text text-danger"></small>
                     </div>
                     <div class="form-group">
                         <label>Password</label>
                         <input type="password" name="password" id="password" class="form-control">
                         <small class="form-text text-muted">Abaikan jika tidak ingin mengubah password.</small>
                         <small id="error-password" class="error-text form-text text-danger"></small>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                     <button type="submit" class="btn btn-primary">Simpan</button>
                 </div>
             </div>
         </div>
     </form>
 
     <script>
         $(document).ready(function() {
             $("#form-edit").validate({
                 rules: {
                     id_level: { required: true, number: true },
                     user_kode: { required: true, minlength: 3, maxlength: 20 },
                     name: { required: true, minlength: 3, maxlength: 100 },
                     password: { minlength: 6, maxlength: 20 }
                 },
                 submitHandler: function(form) {
                     $.ajax({
                         url: form.action,
                         type: form.method,
                         data: $(form).serialize(),
                         success: function(response) {
                             if (response.status) {
                                 $('#myModal').modal('hide');
                                 Swal.fire({
                                     icon: 'success',
                                     title: 'Berhasil',
                                     text: response.message
                                 });
                                 dataUser.ajax.reload();
                             } else {
                                 $('.error-text').text('');
                                 $.each(response.msgField, function(prefix, val) {
                                     $('#error-' + prefix).text(val[0]);
                                 });
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
                 errorPlacement: function(error, element) {
                     error.addClass('invalid-feedback');
                     element.closest('.form-group').append(error);
                 },
                 highlight: function(element, errorClass, validClass) {
                     $(element).addClass('is-invalid');
                 },
                 unhighlight: function(element, errorClass, validClass) {
                     $(element).removeClass('is-invalid');
                 }
             });
         });
     </script>
 @endempty