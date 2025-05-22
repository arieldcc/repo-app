@extends('layouts.main')

@section('title') Edit Skripsi @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Edit Skripsi @endsection

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <form action="{{ url('doc/skripsi/edit/'.$getRecord->document_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card card-warning">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title text-white mb-0 flex-grow-1">Form Edit Skripsi</h4>
                    <a href="{{ url('doc/skripsi') }}" class="btn btn-sm btn-warning text-dark">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="nim">Mahasiswa</label>
                        <select name="penulis" id="nim" class="form-control select2bs4" required>
                            <option value="{{ $getRecord->penulis }}" selected>{{ $getRecord->nim }} - {{ $getRecord->nama_mahasiswa }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Judul Skripsi</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $getRecord->title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="abstract">Abstrak</label>
                        <textarea name="abstract" id="abstract" rows="4" class="form-control">{{ $getRecord->abstract }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="keywords">Kata Kunci</label>
                        <input type="text" name="keywords" id="keywords" class="form-control" value="{{ $getRecord->keywords }}">
                    </div>

                    <div class="form-group">
                        <label for="tahun_akademik">Tahun</label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" value="{{ $getRecord->tahun_akademik }}" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="form-group">
                        <label for="file_path">Upload File (Kosongkan jika tidak ingin mengganti)</label>
                        <input type="file" name="file_path" id="file_path" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.png">
                        @if ($getRecord->file_path)
                            <small class="text-muted d-block mt-2">File saat ini: {{ $getRecord->file_path }}</small>

                            @php
                                $filePath = $getRecord->file_path;
                                $fileUrl = $filePath ? url('/public/storage/uploads/skripsi/' . $filePath) : null;
                                $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                            @endphp

                            @if ($ext === 'pdf')
                                <h5 class="mt-4">Pratinjau PDF:</h5>
                                <iframe src="{{ $fileUrl }}#toolbar=0" type="application/pdf" style="width:100%; height:600px; border:1px solid #ccc;"></iframe>
                            @elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                                <h5 class="mt-4">Pratinjau Gambar:</h5>
                                <img src="{{ $fileUrl }}" alt="Preview Gambar" class="preview-img" style="max-width:100%; max-height:600px; border:1px solid #ccc; margin-top:10px;">
                            @elseif (in_array($ext, ['doc', 'docx']))
                                <h5 class="mt-4">Pratinjau Word (via Office Online):</h5>
                                <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode($fileUrl) }}" frameborder="0" style="width:100%; height:600px; border:1px solid #ccc;"></iframe>
                            @else
                                <p class="text-muted mt-2">Format file tidak didukung untuk pratinjau langsung.</p>
                            @endif
                        @endif
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
                    <a href="{{ url('doc/skripsi') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
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
    const selectedVal = "{{ $getRecord->nim }}";
    const selectedText = "{{ $getRecord->nim }} - {{ $getRecord->nama_mahasiswa }}";

    // Sisipkan opsi secara manual terlebih dahulu sebelum Select2 diaktifkan
    const option = new Option(selectedText, selectedVal, true, true);
    $('#nim').append(option);

    // Baru inisialisasi select2 setelah option manual masuk
    $('#nim').select2({
        theme: 'bootstrap4',
        placeholder: "Ketik minimal 3 huruf NIM/Nama Mahasiswa...",
        minimumInputLength: 3,
        ajax: {
            url: "{{ url('api/cari-mahasiswa/S1') }}",
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
