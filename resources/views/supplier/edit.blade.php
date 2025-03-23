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
                  <a href="{{ url('supplier') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
              @else
                  <form method="POST" action="{{ url('/supplier/' . $supplier->supplier_id) }}" class="form-horizontal">
                      @csrf
                      {!! method_field('PUT') !!} <!-- Untuk metode update -->
  
                      <!-- Kode Supplier -->
                      <div class="form-group row">
                          <label class="col-2 control-label col-form-label">Kode Supplier</label>
                          <div class="col-10">
                              <input type="text" class="form-control" id="supplier_kode" name="supplier_kode"
                                  value="{{ old('supplier_kode', $supplier->supplier_kode) }}" required>
                              @error('supplier_kode')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @enderror
                          </div>
                      </div>
  
                      <!-- Nama Supplier -->
                      <div class="form-group row">
                          <label class="col-2 control-label col-form-label">Nama</label>
                          <div class="col-10">
                              <input type="text" class="form-control" id="nama" name="nama"
                                  value="{{ old('nama', $supplier->nama) }}" required>
                              @error('nama')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @enderror
                          </div>
                      </div>
  
                      <!-- Nama PT -->
                      <div class="form-group row">
                          <label class="col-2 control-label col-form-label">Nama PT</label>
                          <div class="col-10">
                              <input type="text" class="form-control" id="nama_pt" name="nama_pt"
                                  value="{{ old('nama_pt', $supplier->nama_pt) }}" required>
                              @error('nama_pt')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @enderror
                          </div>
                      </div>
  
                      <!-- Alamat Supplier -->
                      <div class="form-group row">
                          <label class="col-2 control-label col-form-label">Alamat</label>
                          <div class="col-10">
                              <textarea class="form-control" id="alamat" name="alamat" required>{{ old('alamat', $supplier->alamat) }}</textarea>
                              @error('alamat')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @enderror
                          </div>
                      </div>
  
                      <!-- Tombol Simpan -->
                      <div class="form-group row">
                          <label class="col-2 control-label col-form-label"></label>
                          <div class="col-10">
                              <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                              <a class="btn btn-sm btn-default ml-1" href="{{ url('supplier') }}">Kembali</a>
                          </div>
                      </div>
                  </form>
              @endempty
          </div>
      </div>
  @endsection
  @push('css')
  @endpush
  @push('js')
  @endpush