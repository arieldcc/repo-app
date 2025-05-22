@extends('layouts.main')

@section('title') Edit Pengaturan Frontend @endsection

@section('css')
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Edit Pengaturan Frontend @endsection

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <form action="{{ url('conf-repo/frontend-setting/edit/' . $getRecord->id) }}" method="POST">
            @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Pengaturan Frontend</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="site_name">Nama Situs</label>
                        <input type="text" name="site_name" id="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $getRecord->site_name) }}" required>
                        @error('site_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="site_tagline">Tagline Situs</label>
                        <input type="text" name="site_tagline" id="site_tagline" class="form-control @error('site_tagline') is-invalid @enderror" value="{{ old('site_tagline', $getRecord->site_tagline) }}">
                        @error('site_tagline')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="version">Versi</label>
                        <input type="text" name="version" id="version" class="form-control @error('version') is-invalid @enderror" value="{{ old('version', $getRecord->version) }}" required>
                        @error('version')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="footer_text">Teks Footer</label>
                        <textarea name="footer_text" id="footer_text" rows="4" class="form-control @error('footer_text') is-invalid @enderror">{!! old('footer_text', $getRecord->footer_text) !!}</textarea>
                        @error('footer_text')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control select2bs4 @error('status') is-invalid @enderror" required>
                            <option value="Y" {{ old('status', $getRecord->status) == 'Y' ? 'selected' : '' }}>Aktif</option>
                            <option value="N" {{ old('status', $getRecord->status) == 'N' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ url('conf-repo/frontend-setting') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2bs4').select2({ theme: 'bootstrap4' });
    });

    ClassicEditor
        .create(document.querySelector('#footer_text'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo' ]
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
