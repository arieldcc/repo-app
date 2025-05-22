@extends('layouts.main')

@section('title') Edit Halaman Kustom @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Summernote (Editor) -->
<link rel="stylesheet" href="{{ url('') }}/public/plugins/summernote/summernote-bs4.min.css">
@endsection

@section('headcontent') Form Edit Halaman Kustom @endsection

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <form action="{{ url('conf-repo/custom-pages/edit/' . $getRecord->id) }}" method="POST">
            @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Halaman</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="title">Judul Halaman</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $getRecord->title) }}" required>
                        @error('title')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Konten Halaman</label>
                        <textarea name="content" id="content" rows="6" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content', $getRecord->content) }}</textarea>
                        @error('content')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control select2bs4 @error('status') is-invalid @enderror" required>
                            <option value="Y" {{ old('status', $getRecord->status) == 'Y' ? 'selected' : '' }}>Aktif</option>
                            <option value="N" {{ old('status', $getRecord->status) == 'N' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ url('conf-repo/custom-pages') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ url('') }}/public/plugins/summernote/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2bs4').select2({ theme: 'bootstrap4' });
        $('.summernote').summernote({
            height: 200
        });
    });
</script>
@endsection
