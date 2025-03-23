@extends('layouts.tamplate')
 @section('content')
 <div class="card card-outline card-primary">
     <div class="card-header">
         <h3 class="card-title">{{ $page->title }}</h3>
         <div class="card-tools">
             <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
             <button onclick="modalAction('{{ url('barang/create_ajax') }}')" class="btn btn-sm btn-danger mt-1">Tambah Ajax</button>
         </div>
     </div>
     <div class="card-body">
         @if(session('success'))
             <div class="alert alert-success alert-dismissible">
                 <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                 {{ session('success') }}
             </div>
         @endif
         @if(session('error'))
             <div class="alert alert-danger alert-dismissible">
                 <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                 {{ session('error') }}
             </div>
         @endif
 
         <div class="form-group">
             <label>Filter Kategori:</label>
             <select id="filter_kategori" class="form-control">
                 <option value="">-- Semua Kategori --</option>
                 @foreach($kategori as $item)
                     <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                 @endforeach
             </select>
         </div>
 
         <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
             <thead>
                 <tr>
                     <th>ID</th>
                     <th>Kode</th>
                     <th>Nama Barang</th>
                     <th>Kategori</th>
                     <th>Harga Beli</th>
                     <th>Harga Jual</th>
                     <th>Aksi</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>

 <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
 @endsection
 @push('css')
 @endpush
 @push('js')
 <script>
    function modalAction(url=''){
        $('#myModal').load(url,function(){
            $('#myModal').modal('show');
        });
    }

    var dataBarang;
     $(document).ready(function() {
        dataBarang = $('#table_barang').DataTable({
             serverSide: true,
             ajax: {
                 "url": "{{ url('barang/list') }}",
                 "dataType": "json",
                 "type": "POST",
                 "data": function(d) {
                     d._token = "{{ csrf_token() }}";
                     d.kategori_id = $('#filter_kategori').val();
                 }
             },
             columns: [{
                 data: "DT_RowIndex",
                 className: "text-center",
                 orderable: false,
                 searchable: false
             },{
                 data: "barang_kode",
                 className: "",
                 orderable: true,
                 searchable: true
             },{
                 data: "barang_nama",
                 className: "",
                 orderable: true,
                 searchable: true
             },{
                 data: "kategori.kategori_nama",
                 className: "",
                 orderable: false,
                 searchable: false
             },{
                 data: "harga_beli",
                 className: "text-right",
                 orderable: true,
                 searchable: false,
                 render: function(data, type, row) {
                     return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                 }
             },{
                 data: "harga_jual",
                 className: "text-right",
                 orderable: true,
                 searchable: false,
                 render: function(data, type, row) {
                     return 'Rp ' + parseFloat(data).toLocaleString('id-ID');
                 }
             },{
                 data: "aksi",
                 className: "text-center",
                 orderable: false,
                 searchable: false
             }]
         });
 
         $('#filter_kategori').on('change', function() {
             dataBarang.ajax.reload();
         });
     });
 </script>
 @endpush