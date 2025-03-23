@extends('layouts.tamplate')
 @section('content')
     <div class="card card-outline card-primary">
         <div class="card-header">
             <h3 class="card-title">Tambah Supplier</h3>
             <div class="card-tools"></div>
         </div>
         <div class="card-body">
             <form method="POST" action="{{ url('supplier') }}" class="form-horizontal">
                 @csrf
                 <div class="form-group row">
                     <label class="col-2 control-label col-form-label">Kode Supplier</label>
                     <div class="col-10">
                         <input type="text" class="form-control" id="supplier_kode" name="supplier_kode"
                             value="{{ old('supplier_kode') }}" required>
                         @error('supplier_kode')
                             <small class="form-text text-danger">{{ $message }}</small>
                         @enderror
                     </div>
                 </div>
                 <div class="form-group row">
                     <label class="col-2 control-label col-form-label">Nama</label>
                     <div class="col-10">
                         <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
                             required>
                         @error('nama')
                             <small class="form-text text-danger">{{ $message }}</small>
                         @enderror
                     </div>
                 </div>
                 <div class="form-group row">
                     <label class="col-2 control-label col-form-label">Nama PT</label>
                     <div class="col-10">
                         <input type="text" class="form-control" id="nama_pt" name="nama_pt" value="{{ old('nama_pt') }}"
                             required>
                         @error('nama_pt')
                             <small class="form-text text-danger">{{ $message }}</small>
                         @enderror
                     </div>
                 </div>
                 <div class="form-group row">
                     <label class="col-2 control-label col-form-label">Alamat</label>
                     <div class="col-10">
                         <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}"
                             required>
                         @error('alamat')
                             <small class="form-text text-danger">{{ $message }}</small>
                         @enderror
                     </div>
                 </div>
                 <div class="form-group row">
                     <label class="col-2 control-label col-form-label"></label>
                     <div class="col-10">
                         <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                         <a class="btn btn-sm btn-default ml-1" href="{{ url('supplier') }}">Kembali</a>
                     </div>
                 </div>
             </form>
         </div>
     </div>
 @endsection
 
 @push('css')
 @endpush
 @push('js')
 @endpush