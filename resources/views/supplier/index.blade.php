@extends('layouts.tamplate')
  @section('content')
      <div class="card">
        <div class="card-header">
            <div class="container-fluid">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h3 class="card-title mb-0">Daftar supplier</h3>
                </div>
                <div class="col-md-6 text-md-end text-start mt-2 mt-md-0">
                  <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-info btn-sm">Import supplier</button> 
                  <button onclick="modalAction('{{ url('supplier/create_ajax') }}')" class="btn btn-danger btn-sm">Tambah Ajax</button>
                  <a class="btn btn-primary btn-sm" href="{{ url('supplier/create') }}">Tambah Data</a>
                </div>
              </div>
            </div>
          </div>
          
          <div class="card-body">
              <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Kode Supplier</th>
                          <th>Nama</th>
                          <th>Nama PT</th>
                          <th>Alamat</th>
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

            var dataSupplier;
          $(document).ready(function() {
            dataSupplier = $('#table_supplier').DataTable({
                  serverSide: true,
                  ajax: {
                      "url": "{{ url('supplier/list') }}",
                      "dataType": "json",
                      "type": "POST"
                  },
                  columns: [
                      {
                          data: "DT_RowIndex",
                          className: "text-center",
                          orderable: false,
                          searchable: false
                      },
                      {
                          data: "supplier_kode",
                          className: "",
                          orderable: true,
                          searchable: true
                      },
                      {
                          data: "nama",
                          className: "",
                          orderable: true,
                          searchable: true
                      },
                      {
                          data: "nama_pt",
                          className: "",
                          orderable: true,
                          searchable: true
                      },
                      {
                          data: "alamat",
                          className: "",
                          orderable: true,
                          searchable: true
                      },
                      {
                          data: "aksi",
                          className: "text-center",
                          orderable: false,
                          searchable: false
                      }
                  ]
              });
          });
      </script>
  @endpush