@extends('layouts.main')

@section('title') Pejabat Fakultas @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/fontawesome-free/css/all.min.css">
<!-- CSS Tempus Dominus -->
<link rel="stylesheet" href="{{ url('') }}/public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endsection

@section('headcontent') Add Pejabat Fakultas @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Pejabat Fakultas</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ url('master/fakultas/addpejabat/'.$getFakultas->id_fakultas) }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                    <label for="nama_fakultas">Nama Fakultas</label>
                    <input type="text" name="nama_fakultas" value="{{ $getFakultas->nama_fakultas }}" class="form-control" readonly id="nama_fakultas" placeholder="Input Nama Fakultas">
                    <input type="hidden" name="id_fakultas" value="{{ $getFakultas->id_fakultas }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="jenis_jabatan_id">Jabatan</label>
                    <select required name="jenis_jabatan_id" id="jenis_jabatan_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($getJenisJabatan as $value)
                            <option {{ old('jenis_jabatan_id') == $value->id_jenis_jabatan ? 'selected' : '' }} value="{{ $value->id_jenis_jabatan }}">
                                {{ $value->nama_jenis_jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="dosen_id">Nama Pejabat</label>
                    <select required name="dosen_id" id="dosen_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach ($getDosen as $value)
                            <option {{ old('dosen_id') == $value->id_dosen ? 'selected' : '' }} value="{{ $value->id_dosen }}">
                                {{ $value->nidn.' - '.$value->nama_dosen }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="no_sk">Nomor SK Jabatan</label>
                    <input type="text" name="no_sk" value="{{ old('no_sk') }}" class="form-control" required id="no_sk" placeholder="Input Nomor SK Jabatan">
                </div>

                <div class="form-group">
                    <label>Tanggal Mulai:</label>
                    <div class="input-group date" id="tanggal_mulai" data-target-input="nearest">
                        <input name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required type="text" class="form-control datetimepicker-input" data-target="#tanggal_mulai" placeholder="Input tanggal mulai jabatan">
                        <div class="input-group-append" data-target="#tanggal_mulai" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai:</label>
                    <div class="input-group date" id="tanggal_selesai" data-target-input="nearest">
                        <input name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required type="text" class="form-control datetimepicker-input" data-target="#tanggal_selesai" placeholder="Input tanggal selesai jabatan">
                        <div class="input-group-append" data-target="#tanggal_selesai" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label>Status Jabatan</label><br>

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
                    <label for="file_sk">File SK Pejabat (pdf atau Gambar, max: 600KB)</label>
                    <div class="input-group">
                        <div class="custom-file">
                        <input type="file" name="file_sk" class="custom-file-input @error('file_sk') is-invalid @enderror" id="file_sk">
                        <label class="custom-file-label" for="file_sk">Pilih file</label>
                        </div>
                        @error('file_sk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fas fa-check"></i> Submit</button>
                <a href="{{ url('master/fakultas/detail/'.$getFakultas->id_fakultas) }}#pimpinan-fakultas" class="btn btn-secondary float-right" style="margin-right: 10px;"><i class="fas fa-times"></i> Batal</a>
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
<!-- JS Tempus Dominus -->
<script src="{{ url('') }}/public/plugins/moment/moment.min.js"></script>
<script src="{{ url('') }}/public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            // Initialize Select2 on the select element with Bootstrap 4 theme
            $('#jenis_jabatan_id').select2({
                theme: 'bootstrap4',  // Use the Bootstrap 4 theme
                width: '100%'         // Ensure the dropdown takes up the full width
            });

            $('#dosen_id').select2({
                theme: 'bootstrap4',  // Use the Bootstrap 4 theme
                width: '100%'         // Ensure the dropdown takes up the full width
            });

            bsCustomFileInput.init();

            // Initialize the datetime picker
            $('#tanggal_selesai').datetimepicker({
                format: 'YYYY-MM-DD', // Format tanggal
                locale: 'id', // Sesuaikan dengan bahasa jika diperlukan
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-arrow-up',
                    down: 'fa fa-arrow-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });

            // Initialize the datetime picker
            $('#tanggal_mulai').datetimepicker({
                format: 'YYYY-MM-DD', // Format tanggal
                locale: 'id', // Sesuaikan dengan bahasa jika diperlukan
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-arrow-up',
                    down: 'fa fa-arrow-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });
        });
    </script>
@endsection
