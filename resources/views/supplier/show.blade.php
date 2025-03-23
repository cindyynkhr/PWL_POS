@extends('layouts.tamplate')
  @section('content')
      <div class="card card-outline card-primary">
          <div class="card-header">
              <h3 class="card-title">{{ $page->title }}</h3>
              <div class="card-tools"></div>
          </div>
          <div class="card-body">
              @empty($supplier)
                  <div class="alert alert-danger alert-dismissible">
                      <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                      Data yang Anda cari tidak ditemukan.
                  </div>
              @else
                  <table class="table table-bordered table-striped table-hover table-sm">
                      <tr>
                          <th>ID Supplier</th>
                          <td>{{ $supplier->supplier_id }}</td>
                      </tr>
                      <tr>
                          <th>Kode Supplier</th>
                          <td>{{ $supplier->supplier_kode }}</td>
                      </tr>
                      <tr>
                          <th>Nama</th>
                          <td>{{ $supplier->nama }}</td>
                      </tr>
                      <tr>
                          <th>Nama PT</th>
                          <td>{{ $supplier->nama_pt }}</td>
                      </tr>
                      <tr>
                          <th>Alamat</th>
                          <td>{{ $supplier->alamat }}</td>
                      </tr>
                      <tr>
                          <th>Dibuat Pada</th>
                          <td>{{ $supplier->created_at ? date('d-m-Y H:i', strtotime($supplier->created_at)) : '-' }}</td>
                      </tr>
                      <tr>
                          <th>Terakhir Diperbarui</th>
                          <td>{{ $supplier->updated_at ? date('d-m-Y H:i', strtotime($supplier->updated_at)) : '-' }}</td>
                      </tr>
                  </table>
              @endempty
              <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
          </div>
      </div>
  @endsection
  @push('css')
  @endpush
  @push('js')
  @endpush