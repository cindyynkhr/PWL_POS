<!DOCTYPE html>
 <html lang="en">
 
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Register Pengguna</title>
 
     <!-- Google Font -->
     <link rel="stylesheet"
         href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
     <!-- Font Awesome -->
     <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
     <!-- icheck bootstrap -->
     <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
     <!-- Theme style -->
     <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
 </head>
 
 <body class="hold-transition register-page">
     <div class="register-box">
         <div class="card card-outline card-primary">
             <div class="card-header text-center">
                 <a href="{{ url('/') }}" class="h1"><b>Admin</b>LTE</a>
             </div>
             <div class="card-body">
                 <p class="login-box-msg">Daftar akun baru</p>
 
                 <form action="{{ url('register') }}" method="POST">
                     @csrf
                     <div class="input-group mb-3">
                         <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                         <div class="input-group-append">
                             <div class="input-group-text"><span class="fas fa-user"></span></div>
                         </div>
                     </div>
                     <div class="input-group mb-3">
                         <input type="text" name="user_kode" class="form-control" placeholder="Username" required>
                         <div class="input-group-append">
                             <div class="input-group-text"><span class="fas fa-user-tag"></span></div>
                         </div>
                     </div>
                     <div class="input-group mb-3">
                         <select name="id_level" class="form-control" required>
                             <option value="">-- Pilih Level --</option>
                             @foreach($levels as $level)
                                 <option value="{{ $level->id_level }}">{{ $level->level_nama }}</option>
                             @endforeach
                         </select>
                     </div>
                     <div class="input-group mb-3">
                         <input type="password" name="password" class="form-control" placeholder="Password" required>
                         <div class="input-group-append">
                             <div class="input-group-text"><span class="fas fa-lock"></span></div>
                         </div>
                     </div>
                     <div class="input-group mb-3">
                         <input type="password" name="password_confirmation" class="form-control"
                             placeholder="Konfirmasi Password" required>
                         <div class="input-group-append">
                             <div class="input-group-text"><span class="fas fa-lock"></span></div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-8">
                             <a href="{{ url('login') }}">Sudah punya akun?</a>
                         </div>
                         <div class="col-4">
                             <button type="submit" class="btn btn-primary btn-block">Register</button>
                         </div>
                     </div>
                 </form>
 
             </div>
         </div>
     </div>
 
     <!-- Scripts -->
     <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
     <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
     <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
 </body>
 </html>