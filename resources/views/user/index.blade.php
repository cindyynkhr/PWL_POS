@extends('layouts.tamplate') 

@section('content') 
<div class="card"> 
  <div class="card-header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h3 class="card-title mb-0">Daftar User</h3>
        </div>
        <div class="d-flex flex-wrap gap-2 justify-content-md-end justify-content-start">
          <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-info btn-sm btn-action">
              Import user
          </button>
          <button onclick="modalAction('{{ url('user/create_ajax') }}')" class="btn btn-danger btn-sm btn-action">
              Tambah Ajax
          </button>
          <a href="{{ url('/user/export_excel') }}" class="btn btn-primary btn-sm btn-action">
              <i class="fa fa-file-excel"></i> Export user
          </a>
          <a href="{{ url('/user/export_pdf') }}" class="btn btn-warning btn-sm btn-action">
              <i class="fa fa-file-pdf"></i> Export user
          </a>
      </div>      
      </div>
    </div>
  </div>

  <div class="card-body"> 
    {{-- Filter --}}
    <div class="row mb-3">
      <div class="col-md-3">
        <div class="form-group">
          <label for="id_level">Filter Level Pengguna</label>
          <select class="form-control" id="id_level" name="id_level">
            <option value="">- Semua -</option>
            @foreach ($level as $item)
              <option value="{{ $item->id_level }}">{{ $item->level_nama }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tabel --}}
    <table class="table table-bordered table-striped table-hover table-sm" id="table_user"> 
      <thead> 
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Nama</th>
          <th>Level Pengguna</th>
          <th>Aksi</th>
        </tr> 
      </thead> 
    </table> 
  </div> 
</div>

{{-- Modal --}}
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" 
    data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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
  function modalAction(url = '') {
    $('#myModal').load(url, function(){
      $('#myModal').modal('show');
    });
  }

  var dataUser;
  $(document).ready(function() { 
    dataUser = $('#table_user').DataTable({ 
        serverSide: true,      
        ajax: { 
            "url": "{{ url('user/list') }}", 
            "dataType": "json", 
            "type": "POST",
            "data": function (d) {
                d.id_level = $('#id_level').val();
            }
        }, 
        columns: [ 
          {
            data: "DT_RowIndex",             
            className: "text-center", 
            orderable: false, 
            searchable: false     
          },
          { 
            data: "user_kode",                
            orderable: true,     
            searchable: true     
          },
          { 
            data: "nama",                
            orderable: true,     
            searchable: true     
          },
          { 
            data: "level.level_nama",                
            orderable: false,     
            searchable: false     
          },
          { 
            data: "aksi",                
            orderable: false,     
            searchable: false     
          } 
        ] 
    }); 

    $('#id_level').on('change', function() {
      dataUser.ajax.reload();
    });

    // Optional: Search on Enter
    $('#table_user_filter input').unbind().bind('keyup', function(e) { 
      if(e.keyCode == 13) {
        dataUser.search(this.value).draw(); 
      } 
    }); 
  }); 
</script> 
@endpush
