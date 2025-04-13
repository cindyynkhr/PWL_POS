@extends('layouts.tamplate')
 @section('content')
 <div class="card card-outline card-primary">
    <div class="card-header">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h3 class="card-title mb-0">Daftar level</h3>
            </div>
            <div class="col-md-6 text-md-end text-start mt-2 mt-md-0">
              <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info btn-sm">Import level</button> 
              <button onclick="modalAction('{{ url('level/create_ajax') }}')" class="btn btn-danger btn-sm">Tambah Ajax</button>
              <a class="btn btn-primary btn-sm" href="{{ url('level/create') }}">Tambah Data</a>
            </div>
          </div>
        </div>
      </div>
     <div class="card-body">
         @if(session('success'))
             <div class="alert alert-success alert-dismissible">
                 <h5><i class="icon fas fa-check"></i> Success!</h5>
                 {{ session('success') }}
             </div>
         @endif
         
         @if(session('error'))
             <div class="alert alert-danger alert-dismissible">
                 <h5><i class="icon fas fa-ban"></i> Error!</h5>
                 {{ session('error') }}
             </div>
         @endif
         
         <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
             <thead>
                 <tr>
                     <th>ID</th>
                     <th>Kode</th>
                     <th>Nama</th>
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

    var dataLevel;
     $(document).ready(function() {
        dataLevel = $('#table_level').DataTable({
             serverSide: true,
             ajax: {
                 "url": "{{ url('level/list') }}",
                 "dataType": "json",
                 "type": "POST",
                 "data": function(d) {
                     d.id_level= $('#id_level').val();
                 }
             },
             columns: [ 
                 {
                     data: "DT_RowIndex",
                     className: "text-center",
                     orderable: false,
                     searchable: false
                 },{
                     data: "level_kode",
                     className: "",
                     orderable: true,
                     searchable: true
                 },{
                     data: "level_nama",
                     className: "",
                     orderable: true,
                     searchable: true
                 },{
                     data: "aksi",
                     className: "",
                     orderable: false,
                     searchable: false
                 }
             ]
         });
     });
 </script>
 @endpush