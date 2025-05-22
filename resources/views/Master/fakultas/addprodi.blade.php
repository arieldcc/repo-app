@extends('layouts.main')

@section('title') Fakultas @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Prodi per Fakultas @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Prodi per Fakultas</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
              <div class="card-body">

                @include('Master.fakultas.formdetailfakultas')

                <div class="form-group">
                    <label>Program Studi</label>
                    <select class="select2bs4" multiple="multiple" data-placeholder="Pilih Program Studi" style="width: 100%;">
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id_prodi }}"
                                @if(in_array($prodi->id_prodi, old('program_studi', $getProdiFakultas))) selected @endif>
                                {{ $prodi->nama_jenjang_pendidikan.' - '.$prodi->nama_program_studi }}
                            </option>
                        @endforeach
                    </select>
                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                {{-- <button type="submit" class="btn btn-primary float-right"><i class="fas fa-check"></i> Submit</button> --}}
                <a href="{{ url('master/fakultas/detail/'.$getRecord->id_fakultas) }}" class="btn btn-secondary float-right" style="margin-right: 10px;"><i class="fas fa-times"></i> Batal</a>
              </div>
            </form>
          </div>
        <!-- /.card -->
    </div>
  </div>
  <!-- /.row -->
@endsection

@section('js')
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var initialSelected = @json($getProdiFakultas);

            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: "Pilih Program Studi",
            });

            $('.select2bs4').on('change', function() {
                var selectedProdi = $(this).val() || []; // Hindari null jika tidak ada pilihan

                $.ajax({
                    url: "{{ url('master/fakultas/updateprodifakultas/'.$getRecord->id_fakultas) }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        selected_prodi: selectedProdi,
                        removed_prodi: getRemovedProdi(selectedProdi)
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Program Studi berhasil diperbarui!',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        // Update pilihan awal
                        initialSelected = selectedProdi;
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal memperbarui program studi.',
                            icon: 'error'
                        });
                    }
                });
            });

            function getRemovedProdi(currentSelected) {
                return initialSelected.filter(function(item) {
                    return !currentSelected.includes(item);
                });
            }
        });
    </script>
@endsection
