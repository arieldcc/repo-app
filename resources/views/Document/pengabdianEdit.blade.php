@extends('layouts.main')

@section('title') Edit Pengabdian @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Edit Pengabdian @endsection

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <form action="{{ url('doc/pengabdian/edit/' . $getPengabdian->document_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header bg-warning d-flex align-items-center">
                    <h3 class="card-title text-dark mb-0 flex-grow-1">Form Edit Pengabdian</h3>
                    <a href="{{ url('doc/pengabdian') }}" class="btn btn-sm btn-light text-dark">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">

                    {{-- Penulis Utama --}}
                    <div class="card border border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Penulis Utama</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="penulis">Nama Penulis</label>
                                <select name="penulis" id="penulis" class="form-control select2bs4" required>
                                    <option selected value="{{ $getPengabdian->penulis }}">{{ $getPengabdian->penulis }}</option>
                                </select>
                                <small class="form-text text-muted">Jika tidak ditemukan, ketik manual</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $getPengabdian->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="afiliasi">Afiliasi</label>
                                <input type="text" name="afiliasi" id="afiliasi" class="form-control" value="{{ $getPengabdian->afiliasi }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Co-Authors --}}
                    <div class="card border border-secondary mb-4">
                        <div class="card-header bg-secondary d-flex align-items-center">
                            <h5 class="text-white mb-0 flex-grow-1">Co-Authors</h5>
                            <button type="button" class="btn btn-light btn-sm" id="add-author"><i class="fas fa-plus"></i> Tambah</button>
                        </div>
                        <div class="card-body" id="authors-wrapper">
                            @foreach ($getAuthors as $index => $author)
                            <div class="author-group border rounded p-3 mb-3 bg-light">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <select name="authors[{{ $index }}][author_name]" class="form-control author-name-select" required>
                                        <option selected value="{{ $author->author_name }}">{{ $author->author_name }}</option>
                                    </select>
                                    <small class="form-text text-muted">Cari atau ketik manual nama</small>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="authors[{{ $index }}][author_email]" class="form-control" value="{{ $author->author_email }}">
                                </div>
                                <div class="form-group">
                                    <label>Afiliasi</label>
                                    <input type="text" name="authors[{{ $index }}][author_affiliation]" class="form-control" value="{{ $author->author_affiliation }}">
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Judul Pengabdian</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $getPengabdian->title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="abstract">Abstrak</label>
                        <textarea name="abstract" id="abstract" rows="4" class="form-control">{{ $getPengabdian->abstract }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="keywords">Kata Kunci</label>
                        <input type="text" name="keywords" id="keywords" class="form-control" value="{{ $getPengabdian->keywords }}">
                    </div>

                    <div class="form-group">
                        <label for="biaya_penelitian">Biaya Pengabdian</label>
                        <select name="biaya_penelitian" id="biaya_penelitian" class="form-control">
                            <option value="Mandiri" {{ $getPengabdian->biaya_penelitian == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                            <option value="Dibiayai" {{ $getPengabdian->biaya_penelitian == 'Dibiayai' ? 'selected' : '' }}>Dibiayai</option>
                        </select>
                    </div>

                    <div class="form-group" id="lembaga_biaya_group" style="display: {{ $getPengabdian->biaya_penelitian == 'Dibiayai' ? 'block' : 'none' }};">
                        <label for="lembaga_biaya">Lembaga Pembiaya</label>
                        <input type="text" name="lembaga_biaya" id="lembaga_biaya" class="form-control" value="{{ $getPengabdian->lembaga_biaya }}">
                    </div>

                    <div class="form-group">
                        <label for="terbit">Terbit</label>
                        <select name="terbit" id="terbit" class="form-control">
                            <option value="N" {{ $getPengabdian->terbit == 'N' ? 'selected' : '' }}>Tidak</option>
                            <option value="Y" {{ $getPengabdian->terbit == 'Y' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>

                    <div id="indeks_fields" style="display: {{ $getPengabdian->terbit == 'Y' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="indeks_nasional">Terindeks Nasional</label>
                            <select name="indeks_nasional" id="indeks_nasional" class="form-control">
                                <option value="N" {{ $getPengabdian->indeks_nasional == 'N' ? 'selected' : '' }}>Tidak</option>
                                <option value="Y" {{ $getPengabdian->indeks_nasional == 'Y' ? 'selected' : '' }}>Ya</option>
                            </select>
                        </div>
                        <div class="form-group" id="peringkat_nasional_group" style="display: {{ $getPengabdian->indeks_nasional == 'Y' ? 'block' : 'none' }};">
                            <label for="peringkat_nasional">Peringkat Nasional</label>
                            <input type="text" name="peringkat_nasional" id="peringkat_nasional" class="form-control" value="{{ $getPengabdian->peringkat_nasional }}">
                        </div>
                        <div class="form-group">
                            <label for="indeks_lainnya">Indeks Lainnya</label>
                            <input type="text" name="indeks_lainnya" id="indeks_lainnya" class="form-control" value="{{ $getPengabdian->indeks_lainnya }}">
                        </div>
                        <div class="form-group">
                            <label for="link_jurnal">Link Jurnal</label>
                            <input type="url" name="link_jurnal" id="link_jurnal" class="form-control" value="{{ $getPengabdian->link_jurnal }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tahun_akademik">Tahun</label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" maxlength="4" value="{{ $getPengabdian->tahun_akademik }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="form-group">
                        <label for="file_path">Upload File Jurnal</label>
                        <input type="file" name="file_path" id="file_path" class="form-control-file" accept=".pdf">
                        <small class="text-muted">Maks 5MB. Format PDF.</small>
                    </div>

                    @if (!empty($getPengabdian->file_path))
                    <div class="form-group">
                        <label>Lihat File Jurnal Saat Ini</label>
                        <iframe src="{{ url('/public/storage/uploads/pengabdian/'.$getPengabdian->file_path) }}" frameborder="0" width="100%" height="500px"></iframe>
                    </div>
                @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
                    <a href="{{ url('doc/pengabdian') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
<script>
$(document).ready(function () {
    function initSelect2(selector) {
        $(selector).select2({
            theme: 'bootstrap4',
            placeholder: "Cari atau ketik nama...",
            tags: true,
            minimumInputLength: 3,
            ajax: {
                url: "{{ url('api/cari-penulis') }}",
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });
    }

    initSelect2('#penulis');
    $('.author-name-select').each(function() {
        initSelect2(this);
    });

    let authorIndex = {{ count($getAuthors) }};
    $('#add-author').on('click', function () {
        const newAuthor = `
        <div class="author-group border rounded p-3 mb-2 bg-light">
            <div class="form-group">
                <label>Nama</label>
                <select name="authors[${authorIndex}][author_name]" class="form-control author-name-select" required></select>
                <small class="form-text text-muted">Cari atau ketik manual</small>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="authors[${authorIndex}][author_email]" class="form-control">
            </div>
            <div class="form-group">
                <label>Afiliasi</label>
                <input type="text" name="authors[${authorIndex}][author_affiliation]" class="form-control">
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
        </div>`;

        $('#authors-wrapper').append(newAuthor);
        initSelect2(`select[name="authors[${authorIndex}][author_name]"]`);
        authorIndex++;
    });

    $(document).on('click', '.remove-author', function () {
        $(this).closest('.author-group').remove();
    });

    $('#biaya_penelitian').on('change', function () {
        $('#lembaga_biaya_group').toggle(this.value === 'Dibiayai');
    });

    $('#terbit').on('change', function () {
        $('#indeks_fields').toggle(this.value === 'Y');
    });

    $('#indeks_nasional').on('change', function () {
        $('#peringkat_nasional_group').toggle(this.value === 'Y');
    });
});
</script>
@endsection
