<!DOCTYPE html>
<html lang>
<head>
   <title>Data Level Pengguna</title>
</head>
<body>
    <h1>Data Level Pengguna</h1>
    <table border="1" cellspacing="0" cellpadding="2">
        <tr>
            <td>ID</td>
            <td>Kode Level</td>
            <td>Nama Level</td>
        </tr>
        @foreach($data as $row)
        <tr>
            <td>{{$row->id_level}}</td>
            <td>{{$row->level_kode}}</td>
            <td>{{$row->level_nama}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>