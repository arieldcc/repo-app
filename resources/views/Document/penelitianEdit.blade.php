@extends('layouts.main')

@section('title') Edit Penelitian @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Edit Penelitian @endsection

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <form action="{{ url('doc/penelitian/edit/'.$getPenelitian->document_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header bg-primary d-flex align-items-center">
                    <h3 class="card-title text-white mb-0 flex-grow-1">Form Edit Penelitian</h3>
                    <a href="{{ url('doc/penelitian') }}" class="btn btn-sm btn-primary text-dark">
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
                                <option value="{{ $getPenelitian->penulis }}" selected>{{ $getPenelitian->penulis }}</option>
                            </select>
                            <small class="form-text text-muted">Jika tidak ditemukan, tulis manual nama penulis</small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Penulis</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $getPenelitian->email }}" required>
                        </div>

                        <div class="form-group">
                            <label for="afiliasi">Afiliasi</label>
                            <input type="text" name="afiliasi" id="afiliasi" class="form-control" value="{{ $getPenelitian->afiliasi }}" required>
                        </div>
                    </div>
                </div>

                {{-- Co-Authors --}}
                <div class="card border border-secondary mb-4">
                    <div class="card-header bg-secondary d-flex align-items-center">
                        <h5 class="card-title text-white mb-0 flex-grow-1">Penulis Lain (Co-Authors)</h5>
                        <button type="button" class="btn btn-secondary btn-sm" id="add-author"><i class="fas fa-plus"></i> Tambah Author</button>
                    </div>
                    <div class="card-body" id="authors-wrapper">
                        @foreach ($getAuthors as $index => $author)
                        <div class="author-group border rounded p-3 mb-3 bg-light">
                            <div class="form-group">
                                <label>Nama</label>
                                <select name="authors[{{ $index }}][author_name]" class="form-control author-name-select" required>
                                    <option value="{{ $author->author_name }}" selected>{{ $author->author_name }}</option>
                                </select>
                                <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
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

                {{-- Tambahan Form Field --}}
                <div class="form-group">
                    <label for="title">Judul Penelitian</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $getPenelitian->title }}" required>
                </div>

                <div class="form-group">
                    <label for="abstract">Abstrak</label>
                    <textarea name="abstract" id="abstract" rows="4" class="form-control">{{ $getPenelitian->abstract }}</textarea>
                </div>

                <div class="form-group">
                    <label for="keywords">Kata Kunci</label>
                    <input type="text" name="keywords" id="keywords" class="form-control" value="{{ $getPenelitian->keywords }}">
                </div>

                <div class="form-group">
                    <label for="biaya_penelitian">Biaya Penelitian</label>
                    <select name="biaya_penelitian" id="biaya_penelitian" class="form-control">
                        <option value="Mandiri" {{ $getPenelitian->biaya_penelitian == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="Dibiayai" {{ $getPenelitian->biaya_penelitian == 'Dibiayai' ? 'selected' : '' }}>Dibiayai</option>
                    </select>
                </div>

                <div class="form-group" id="lembaga_biaya_group" style="display:none;">
                    <label for="lembaga_biaya">Lembaga Pembiaya</label>
                    <input type="text" name="lembaga_biaya" id="lembaga_biaya" class="form-control" value="{{ $getPenelitian->lembaga_biaya }}">
                </div>

                <div class="form-group">
                    <label for="terbit">Terbit</label>
                    <select name="terbit" id="terbit" class="form-control">
                        <option value="N" {{ $getPenelitian->terbit == 'N' ? 'selected' : '' }}>Tidak</option>
                        <option value="Y" {{ $getPenelitian->terbit == 'Y' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>

                <div id="indeks_fields" style="display:none;">
                    <div class="form-group">
                        <label for="indeks_nasional">Terindeks Nasional</label>
                        <select name="indeks_nasional" id="indeks_nasional" class="form-control">
                            <option value="N" {{ $getPenelitian->indeks_nasional == 'N' ? 'selected' : '' }}>Tidak</option>
                            <option value="Y" {{ $getPenelitian->indeks_nasional == 'Y' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="form-group" id="peringkat_nasional_group" style="display:none;">
                        <label for="peringkat_nasional">Peringkat Nasional</label>
                        <input type="text" name="peringkat_nasional" id="peringkat_nasional" class="form-control" value="{{ $getPenelitian->peringkat_nasional }}" placeholder="Sinta 1 s/d 6">
                    </div>
                    <div class="form-group">
                        <label for="indeks_internasional">Terindeks Internasional</label>
                        <select name="indeks_internasional" id="indeks_internasional" class="form-control">
                            <option value="N" {{ $getPenelitian->indeks_internasional == 'N' ? 'selected' : '' }}>Tidak</option>
                            <option value="Y" {{ $getPenelitian->indeks_internasional == 'Y' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="form-group" id="peringkat_internasional_group" style="display:none;">
                        <label for="peringkat_internasional">Peringkat Internasional</label>
                        <input type="text" name="peringkat_internasional" id="peringkat_internasional" class="form-control" value="{{ $getPenelitian->peringkat_internasional }}" placeholder="Quartil/Q 1 s/d 4">
                    </div>
                    <div class="form-group">
                        <label for="indeks_lainnya">Indeks Lainnya</label>
                        <input type="text" name="indeks_lainnya" id="indeks_lainnya" class="form-control" value="{{ $getPenelitian->indeks_lainnya }}" placeholder="bisa lebih dari 1 lembaga indeks">
                    </div>
                    <div class="form-group">
                        <label for="link_jurnal">Link Jurnal</label>
                        <input type="url" name="link_jurnal" id="link_jurnal" class="form-control" value="{{ $getPenelitian->link_jurnal }}" placeholder="ex. https://ejurnal.unisan.ac.id/index.php/balok/article/view/1192">
                    </div>
                </div>

                <div class="form-group">
                    <label for="tahun_akademik">Tahun</label>
                    <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" value="{{ $getPenelitian->tahun_akademik }}" placeholder="ex. 2024" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="form-group">
                    <label for="file_path">Upload File Jurnal</label>
                    <input type="file" name="file_path" id="file_path" class="form-control-file" accept=".pdf">
                    <small class="text-muted">Maks 5MB. Hanya format PDF.</small>
                </div>

                @if (!empty($getPenelitian->file_path))
                    <div class="form-group">
                        <label>Lihat File Jurnal Saat Ini</label>
                        <iframe src="{{ url('/public/storage/uploads/penelitian/'.$getPenelitian->file_path) }}" frameborder="0" width="100%" height="500px"></iframe>
                    </div>
                @endif

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ url('doc/penelitian') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
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
    function toggleFields() {
        $('#lembaga_biaya_group').toggle($('#biaya_penelitian').val() === 'Dibiayai');
        const terbit = $('#terbit').val() === 'Y';
        $('#indeks_fields').toggle(terbit);
        $('#peringkat_nasional_group').toggle($('#indeks_nasional').val() === 'Y');
        $('#peringkat_internasional_group').toggle($('#indeks_internasional').val() === 'Y');
    }

    function initPenulisUtama(selector) {
        $(selector).select2({
            theme: 'bootstrap4',
            placeholder: "Cari atau ketik nama penulis utama...",
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
        }).on('change', function() {
            const val = $(this).val();
            const currentOption = $(this).find('option[value="' + val + '"]');
            const isManual = currentOption.length === 0 || currentOption.text().toLowerCase() === val.toLowerCase();
            if (isManual && val) {
                const upper = val.toUpperCase();
                $(this).find('option[value="' + val + '"]').remove();
                const newOption = new Option(upper, upper, true, true);
                $(this).append(newOption).trigger('change');
            }
        });
    }

    function initAuthorSelect2(selector) {
        $(selector).select2({
            theme: 'bootstrap4',
            placeholder: "Cari atau ketik nama penulis...",
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
        }).on('change', function () {
            const val = $(this).val();
            const currentOption = $(this).find('option[value="' + val + '"]');
            const isManual = currentOption.length === 0 || currentOption.text().toLowerCase() === val.toLowerCase();
            if (isManual && val) {
                const upper = val.toUpperCase();
                $(this).find('option[value="' + val + '"]').remove();
                const newOption = new Option(upper, upper, true, true);
                $(this).append(newOption).trigger('change');
            }
        });
    }

    initPenulisUtama('#penulis');
    $('.author-name-select').each(function() {
        initAuthorSelect2(this);
    });


    toggleFields();

    $('#biaya_penelitian, #terbit, #indeks_nasional, #indeks_internasional').on('change', toggleFields);

    let authorIndex = {{ count($getAuthors) }};
    $('#add-author').on('click', function () {
        const html = `
        <div class="author-group border rounded p-3 mb-3 bg-light">
            <div class="form-group">
                <label>Nama</label>
                <select name="authors[${authorIndex}][author_name]" class="form-control author-name-select" required></select>
                <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
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
        </div>
        `;
        $('#authors-wrapper').append(html);
        initAuthorSelect2(`select[name="authors[${authorIndex}][author_name]"]`);
        authorIndex++;
    });

    $(document).on('click', '.remove-author', function () {
        $(this).closest('.author-group').remove();
    });
});
</script>
@endsection
