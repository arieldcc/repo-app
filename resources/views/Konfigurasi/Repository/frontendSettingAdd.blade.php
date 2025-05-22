@extends('layouts.main')

@section('title') Tambah Frontend Setting @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Tambah Frontend Setting @endsection

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <form action="{{ url('conf-repo/frontend-setting/add') }}" method="POST">
            @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Frontend Setting</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="site_name">Nama Website</label>
                        <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="site_tagline">Tagline Website</label>
                        <input type="text" name="site_tagline" id="site_tagline" class="form-control" value="{{ old('site_tagline') }}">
                    </div>

                    <div class="form-group">
                        <label for="version">Versi</label>
                        <input type="text" name="version" id="version" class="form-control" value="{{ old('version') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="footer_text">Teks Footer</label>
                        <textarea name="footer_text" id="footer_text" rows="15" class="form-control">{!! old('footer_text') !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Aktif</label>
                        <select name="status" id="status" class="form-control select2bs4" required>
                            <option value="Y" {{ old('status') == 'Y' ? 'selected' : '' }}>Aktif</option>
                            <option value="N" {{ old('status') == 'N' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ url('conf-repo/frontend-setting') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
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
