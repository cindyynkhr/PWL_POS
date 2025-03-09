<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>
</head>
<body>
    <h1>Data User</h1>
    <a href="{{ url('/user/tambah') }}">+ Tambah User</a>
    <table border="1" cellspacing="2" cellpadding="0">
        <thead>
            <tr>
                <td>ID</td>
                <td>username</td>
                <td>Nama</td> 
                <td>ID Level Pengguna</td>
                <td>Aksi</td>
                {{-- <th>Jumlah Pengguna</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
            <tr>
                {{-- <td>{{ $data }}</td> --}}
                <td>{{ $d->id_user }}</td>
                <td>{{ $d->user_kode }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->level_id }}</td>
                <td><a href="user/ubah/{{ $d->id_user }}">Ubah</a> | <a href="user/hapus/{{ $d->id_user }}">Hapus</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>