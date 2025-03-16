@extends('layouts.tamplate') 
  @section('content')
      <div class="card card-outline card-primary">
          <div class="card-header">
              <h3 class="card-title">{{ $page->title }}</h3>
              <div class="card-tools"></div>
          </div>
          <div class="card-body">
              @empty($user)
                  <div class="alert alert-danger alert-dismissible">
                      <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                      Data yang Anda cari tidak ditemukan.
                  </div>
                  <a href="{{ url('user') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
              @else
                  <form method="POST" action="{{ url('/user/' . $user->id_user) }}" class="formhorizontal">
                      @csrf
                      {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh
              method PUT -->
                      <div class="form-group row">
                          <label class="col-1 control-label col-form-label">Level</label>
                          <div class="col-11">
                              <select class="form-control" id="id_level" name="id_level" required>
                                  <option value="">- Pilih Level -</option>
                                  @foreach ($level as $item)
                                      <option value="{{ $item->id_level }}" @if ($item->id_level == $user->id_level) selected @endif>
                                          {{ $item->level_nama }}</option>
                                  @endforeach
                              </select>
                              @error('id_level')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @enderror
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-1 control-label col-form-label">Username</label>
                          <div class="col-11">
                              <input type="text" class="form-control" id="user_kode" name="user_kode"
                                  value="{{ old('user_kode', $user->user_kode) }}" required>
                              @error('user_kode')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @enderror
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-1 control-label col-form-label">Nama</label>
                          <div class="col-11">
                              <input type="text" class="form-control" id="nama" name="nama"
                                  value="{{ old('nama', $user->nama) }}" required>
                              @error('nama')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @enderror
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-1 control-label col-form-label">Password</label>
                          <div class="col-11">
                              <input type="password" class="form-control" id="password" name="password">
                              @error('password')
                                  <small class="form-text text-danger">{{ $message }}</small>
                              @else
                                  <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin
                                      mengganti password user.</small>
                              @enderror
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-1 control-label col-form-label"></label>
                          <div class="col-11">
                              <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                              <a class="btn btn-sm btn-default ml-1" href="{{ url('user') }}">Kembali</a>
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