@extends('layouts.tamplate')
 @section('content')
 <div class="card card-outline card-primary">
    <div class="card-header">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h3 class="card-title mb-0">Daftar kategori</h3>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-md-end justify-content-start">
                <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-info btn-sm btn-action">
                    Import kategori
                </button>
                <button onclick="modalAction('{{ url('kategori/create_ajax') }}')" class="btn btn-danger btn-sm btn-action">
                    Tambah Ajax
                </button>
                <a href="{{ url('/kategori/export_excel') }}" class="btn btn-primary btn-sm btn-action">
                    <i class="fa fa-file-excel"></i> Export kategori
                </a>
                <a href="{{ url('/kategori/export_pdf') }}" class="btn btn-warning btn-sm btn-action">
                    <i class="fa fa-file-pdf"></i> Export kategori
                </a>
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
         
         <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
             <thead>
                 <tr>
                     <th>ID</th>
                     <th>Kode</th>
                     <th>Nama Kategori</th>
                     <th>Aksi</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>
 <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
 @endsection
 @push('css')
<style>
    .btn-action {
        min-width: 130px;
        text-align: center;
    }

    .gap-2 > * {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
</style>
@endpush
 @push('js')
 <script>
    function modalAction(url = ''){
        $('#myModal').load(url,function(){
            $('#myModal').modal('show');
        });
    }

    var dataKategori;
     $(document).ready(function() {
        dataKategori = $('#table_kategori').DataTable({
             serverSide: true,
             ajax: {
                 "url": "{{ url('kategori/list') }}",
                 "dataType": "json",
                 "type": "POST",
                 "data": function(d) {
                     d._token = "{{ csrf_token() }}";
                 }
             },
             columns: [{
                 data: "DT_RowIndex",
                 className: "text-center",
                 orderable: false,
                 searchable: false
             },
             {
                 data: "kategori_kode",
                 className: "",
                 orderable: true,
                 searchable: true
             },
             {
                 data: "kategori_nama",
                 className: "",
                 orderable: true,
                 searchable: true
             },
             {
                 data: "aksi",
                 className: "",
                 orderable: false,
                 searchable: false
             }]
         });
     });
 </script>
 @endpush