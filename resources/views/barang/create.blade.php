@extends('layouts.tamplate')
 @section('content')
 <div class="card card-outline card-primary">
     <div class="card-header">
         <h3 class="card-title">{{ $page->title }}</h3>
         <div class="card-tools"></div>
     </div>
     <div class="card-body">
         <form method="POST" action="{{ url('barang') }}" class="form-horizontal">
             @csrf
             <div class="form-group row">
                 <label class="col-2 control-label col-form-label">Kode Barang</label>
                 <div class="col-10">
                     <input type="text" class="form-control" id="barang_kode" name="barang_kode" value="{{ old('barang_kode') }}" required>
                     @error('barang_kode')
                         <small class="form-text text-danger">{{ $message }}</small>
                     @enderror
                 </div>
             </div>
             
             <div class="form-group row">
                 <label class="col-2 control-label col-form-label">Nama Barang</label>
                 <div class="col-10">
                     <input type="text" class="form-control" id="barang_nama" name="barang_nama" value="{{ old('barang_nama') }}" required>
                     @error('barang_nama')
                         <small class="form-text text-danger">{{ $message }}</small>
                     @enderror
                 </div>
             </div>
             
             <div class="form-group row">
                 <label class="col-2 control-label col-form-label">Kategori</label>
                 <div class="col-10">
                     <select class="form-control" id="id_kategori" name="id_kategori" required>
                         <option value="">- Pilih Kategori -</option>
                         @foreach($kategori as $item)
                             <option value="{{ $item->id_kategori }}" {{ old('id_kategori') == $item->id_kategori ? 'selected' : '' }}>{{ $item->kategori_nama }}</option>
                         @endforeach
                     </select>
                     @error('id_kategori')
                         <small class="form-text text-danger">{{ $message }}</small>
                     @enderror
                 </div>
             </div>
             
             <div class="form-group row">
                 <label class="col-2 control-label col-form-label">Harga</label>
                 <div class="col-10">
                     <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga') }}" required>
                     @error('harga')
                         <small class="form-text text-danger">{{ $message }}</small>
                     @enderror
                 </div>
             </div>
             
             <div class="form-group row">
                 <label class="col-2 control-label col-form-label">Stok</label>
                 <div class="col-10">
                     <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', 0) }}" required>
                     @error('stok')
                         <small class="form-text text-danger">{{ $message }}</small>
                     @enderror
                 </div>
             </div>
             
             <div class="form-group row">
                 <label class="col-2 control-label col-form-label"></label>
                 <div class="col-10">
                     <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                     <a class="btn btn-sm btn-default ml-1" href="{{ url('barang') }}">Kembali</a>
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