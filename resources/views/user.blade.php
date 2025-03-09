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
                {{-- <th>ID</th>
                <th>username</th>
                <th>Nama</th> --}}
                {{-- <th>ID Level Pengguna</th> --}}
                <th>Jumlah Pengguna</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach($data as $d) --}}
            <tr>
                <td>{{ $data }}</td>
                {{-- <td>{{ $data->id_user }}</td>
                <td>{{ $data->user_kode }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->level_id }}</td> --}}
            </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
</body>
</html>