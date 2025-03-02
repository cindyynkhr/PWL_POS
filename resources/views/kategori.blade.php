<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori Barang</title>
</head>
<body>
    <h1>Data Kategori Barang</h1>
    <table border="1" cellspacing="0" cellpadding="2">
        <tr>
            <td>ID</td>
            <td>Kode Kategori</td>
            <td>Nama Kategori</td>
        </tr>
        @foreach($data as $row)
        <tr>
            <td>{{$row->id_kategori}}</td>
            <td>{{$row->kategori_kode}}</td>
            <td>{{$row->kategori_nama}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
