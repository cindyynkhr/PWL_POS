<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>
</head>
<body>
    <h1>Data User</h1>
    <table border="1" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th>ID</th>
                <th>user_kode</th>
                <th>Nama</th>
                <th>ID Level Pengguna</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $d)
            <tr>
                <td>{{ $d->id_user }}</td>
                <td>{{ $d->user_kode }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->level_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>