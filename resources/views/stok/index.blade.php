@extends('layouts.tamplate')
 @section('content')
 <div class="card card-outline card-primary">
     <div class="card-header">
         <h3 class="card-title">{{ $page->title }}</h3>
         <div class="card-tools">
             <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a>
         </div>
     </div>
     <div class="card-body">
         <!-- Alert Messages -->
         @if(session('success'))
             <div class="alert alert-success alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                 {{ session('success') }}
             </div>
         @endif
         
         @if(session('error'))
             <div class="alert alert-danger alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <h5><i class="icon fas fa-ban"></i> Error!</h5>
                 {{ session('error') }}
             </div>
         @endif
         
         <!-- Filter -->
         <div class="row mb-3">
             <div class="col-md-3">
                 <select id="filter_barang" class="form-control">
                     <option value="">-- Pilih Barang --</option>
                     @foreach($barang as $item)
                     <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                     @endforeach
                 </select>
             </div>
         </div>
         
         <!-- Table -->
         <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
             <thead>
                 <tr>
                     <th>ID</th>
                     <th>Nama Barang</th>
                     <th>Jumlah</th>
                     <th>Tanggal</th>
                     <th>Aksi</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>
 @endsection
 
 @push('css')
 @endpush
 
 @push('js')
 <script>
     $(document).ready(function() {
         var dataStok = $('#table_stok').DataTable({
             serverSide: true,
             ajax: {
                 "url": "{{ url('stok/list') }}",
                 "dataType": "json",
                 "type": "POST",
                 "data": function(d) {
                     d._token = "{{ csrf_token() }}";
                     d.barang_id = $('#filter_barang').val();
                 }
             },
             columns: [{
                 data: "DT_RowIndex",
                 className: "text-center",
                 orderable: false,
                 searchable: false
             },{
                 data: "barang.barang_nama",
                 className: "",
                 orderable: true,
                 searchable: true
             },{
                 data: "stok_jumlah",
                 className: "text-right",
                 orderable: true,
                 searchable: true
             },{
                 data: "stok_tanggal",
                 className: "text-center",
                 orderable: true,
                 searchable: true
             },{
                 data: "aksi",
                 className: "text-center",
                 orderable: false,
                 searchable: false
             }]
         });
         
         $('#filter_barang').on('change', function() {
             dataStok.ajax.reload();
         });
     });
 </script>
 @endpush