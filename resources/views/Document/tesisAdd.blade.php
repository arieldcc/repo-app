@extends('layouts.main')

@section('title') Tambah Tesis @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Tambah Tesis @endsection

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <form action="{{ url('doc/tesis/add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Tesis</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="nim">Mahasiswa</label>
                        <select name="penulis" id="nim" class="form-control select2bs4" required>
                            @if(old('penulis'))
                                <option value="{{ old('penulis') }}" selected>{{ old('penulis') }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Judul Tesis</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                    </div>


                    <div class="form-group">
                        <label for="abstract">Abstrak</label>
                        <textarea name="abstract" id="abstract" rows="4" class="form-control">{{ old('abstract') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="keywords">Kata Kunci</label>
                        <input type="text" name="keywords" id="keywords" class="form-control" value="{{ old('keywords') }}">
                    </div>

                    <div class="form-group">
                        <label for="tahun_akademik">Tahun</label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" placeholder="misal: 2024" value="{{ old('tahun_akademik') }}" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="form-group">
                        <label for="file_path">Upload File</label>
                        <input type="file" name="file_path" id="file_path" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.png">
                        <small class="text-muted">Maks 5MB. Format: pdf, doc, docx, jpg, png.</small>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ url('doc/tesis') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(document).ready(function() {
        $('#nim').select2({
            theme: 'bootstrap4',
            placeholder: "Ketik minimal 3 huruf NIM/Nama Mahasiswa...",
            minimumInputLength: 3,
            ajax: {
                url: "{{ url('api/cari-mahasiswa/S2') }}", // Sesuaikan dengan route Anda
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.nim_mahasiswa,
                                text: item.nim_mahasiswa + ' - ' + item.nama_mahasiswa
                            };
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>
@endsection
