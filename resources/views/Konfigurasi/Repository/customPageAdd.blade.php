@extends('layouts.main')

@section('title') Tambah Halaman Kustom @endsection

@section('css')
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css">
@endsection

@section('headcontent') Tambah Halaman Kustom @endsection

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <form action="{{ url('conf-repo/custom-pages/add') }}" method="POST">
            @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Halaman Kustom</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="page_key">Key Halaman (Otomatis dari Judul)</label>
                        <input type="text" name="page_key" id="page_key" class="form-control @error('page_key') is-invalid @enderror" value="{{ old('page_key') }}" readonly>
                        @error('page_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="title">Judul Halaman</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Konten Halaman</label>
                        <textarea name="content" id="content" rows="6" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status Aktif</label>
                        <select name="status" id="status" class="form-control select2bs4 @error('status') is-invalid @enderror" required>
                            <option value="Y" {{ old('status') == 'Y' ? 'selected' : '' }}>Aktif</option>
                            <option value="N" {{ old('status') == 'N' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ url('conf-repo/custom-page') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2bs4').select2({ theme: 'bootstrap4' });
        $('.summernote').summernote({
            height: 250,
            placeholder: 'Tulis konten halaman di sini...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });

    // Fungsi untuk konversi title menjadi slug
    function generateSlug(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Ganti spasi dengan -
            .replace(/[^\w\-]+/g, '')       // Hapus karakter tak valid
            .replace(/\-\-+/g, '-')         // Ganti -- dengan -
            .replace(/^-+/, '')             // Hapus - di awal
            .replace(/-+$/, '');            // Hapus - di akhir
    }

    $('#title').on('input', function () {
        const title = $(this).val();
        const slug = generateSlug(title);
        $('#page_key').val(slug);
    });
</script>
@endsection
