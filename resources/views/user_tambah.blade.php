<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data User</title>
</head>
<body>
    <h1>Form Tambah Data User</h1>
    <a href="/user">Kembali</a>
    <br><br>
    
    <form method="POST" action="{{ url('/user/tambah_simpan') }}">
        {{ csrf_field() }}

        <label for="user_kode">Kode User</label>
        <input type="text" name="user_kode" placeholder="Masukkan Kode User">
        <br>
        <label for="nama">Nama User</label>
        <input type="text" name="nama" placeholder="Masukkan Nama">
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Masukkan Password">
        <br>
        <label for="level_id">Level Pengguna</label>
        <input type="number" name="level_id" placeholder="Masukkan ID Level Pengguna">
        <br>
        <input type="submit" class="btn btn-success" value="Simpan">
    </form>
</body>