<!DOCTYPE html>
<html>
<head>
    <title>Ubah Data User</title>
</head>
<body>
    <h1>Form Ubah Data User</h1>
    <a href="/user">Kembali</a>
    <br><br>
    <form method="POST" action="{{ url('user/ubah_simpan/' . $data->id_user) }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <label>user_kode</label>
        <input type="text" name="user_kode" placeholder="Masukkan Kode User" value="{{$data->user_kode}}">
        <br>
        <label>Nama User</label>
        <input type="text" name="nama" placeholder="Masukkan Nama" value="{{$data->nama}}">
        <br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan Password" value="{{$data->password}}">
        <br>
        <label>Level ID</label>
        <input type="number" name="level_id" placeholder="Masukkan ID Level" value="{{$data->level_id}}">
        <br>
        <input type="submit" class="btn btn-success" value="Ubah">
    </form>
</body>