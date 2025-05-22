@extends('layouts.main')

@section('title') Edit Slider @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Edit Sliderbar @endsection

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <form action="{{ url('conf-repo/sliderbar/edit/' . $getRecord->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Slider</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="image_path">Gambar Slider</label>
                        <input type="file" name="image_path" id="image_path" class="form-control-file" accept="image/*">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti. Maksimal 2MB. Format: jpg, png, jpeg.</small>

                        @if ($getRecord->image_path)
                            <div class="mt-2">
                                <label>Gambar Saat Ini:</label><br>
                                <img src="{{ url('/public/storage/uploads/reposlider/' . $getRecord->image_path) }}" alt="Slider Image" style="max-width: 500px; height: auto;">
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="title">Judul Slider</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $getRecord->title) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="subtitle">Subjudul / Deskripsi</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $getRecord->subtitle) }}">
                    </div>

                    <div class="form-group">
                        <label for="order">Urutan Tampil</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $getRecord->order) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Aktif</label>
                        <select name="status" id="status" class="form-control select2bs4" required>
                            <option value="Y" {{ old('status', $getRecord->status) == 'Y' ? 'selected' : '' }}>Aktif</option>
                            <option value="N" {{ old('status', $getRecord->status) == 'N' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ url('conf-repo/sliderbar') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
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
        $('.select2bs4').select2({ theme: 'bootstrap4' });
    });
</script>
@endsection
