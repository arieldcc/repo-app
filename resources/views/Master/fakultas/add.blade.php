@extends('layouts.main')

@section('title') Fakultas @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/fontawesome-free/css/all.min.css">
@endsection

@section('headcontent') Add Fakultas @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Fakultas</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ url('master/fakultas/add') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                    <label for="nama_fakultas">Nama Fakultas</label>
                    <input type="text" name="nama_fakultas" value="{{ old('nama_fakultas') }}" class="form-control" required id="nama_fakultas" placeholder="Input Nama Fakultas">
                </div>

                <div class="form-group clearfix">
                    <label>Status Fakultas</label><br>

                    <!-- Aktif Radio Button -->
                    <div class="icheck-success d-inline">
                        <input type="radio" name="status" value="A" id="radioSuccess1"
                               {{ old('status', 'A') == 'A' ? 'checked' : '' }}>
                        <label for="radioSuccess1">Aktif</label>
                    </div>

                    <!-- Non-Aktif Radio Button -->
                    <div class="icheck-success d-inline">
                        <input type="radio" name="status" value="N" id="radioSuccess2"
                               {{ old('status') == 'N' ? 'checked' : '' }}>
                        <label for="radioSuccess2">Non-Aktif</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="jenjang_didik_id">Jenjang Pendidikan</label>
                    <select required name="jenjang_didik_id" id="jenjang_didik_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($getJenjangPendidikan as $value)
                            <option {{ old('jenjang_didik_id') == $value->id_jenjang_didik ? 'selected' : '' }} value="{{ $value->id_jenjang_didik }}">
                                {{ $value->nama_jenjang_didik }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="singkatan">Singkatan</label>
                    <input type="text" name="singkatan" value="{{ old('singkatan') }}" class="form-control" required id="singkatan" placeholder="Input Nama Jenis Jabatan">
                </div>

                <div class="form-group">
                    <label for="logo_fakultas">File input logo (Gambar, max: 600KB)</label>
                    <div class="input-group">
                        <div class="custom-file">
                        <input type="file" name="logo_fakultas" class="custom-file-input @error('logo_fakultas') is-invalid @enderror" accept="image/*" id="logo_fakultas">
                        <label class="custom-file-label" for="logo_fakultas">Pilih file</label>
                        </div>
                        @error('logo_fakultas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fas fa-check"></i> Submit</button>
                <a href="{{ url('master/fakultas') }}" class="btn btn-secondary float-right" style="margin-right: 10px;"><i class="fas fa-times"></i> Batal</a>
              </div>
            </form>
          </div>
        <!-- /.card -->
    </div>
  </div>
  <!-- /.row -->
@endsection

@section('js')
<script src="{{ url('') }}/public/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            // Initialize Select2 on the select element with Bootstrap 4 theme
            $('#jenjang_didik_id').select2({
                theme: 'bootstrap4',  // Use the Bootstrap 4 theme
                width: '100%'         // Ensure the dropdown takes up the full width
            });

            bsCustomFileInput.init();
        });
    </script>
@endsection
