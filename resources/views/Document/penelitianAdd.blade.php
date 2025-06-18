@extends('layouts.main')

@section('title') Tambah Penelitian @endsection

@section('css')
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ url('') }}/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('headcontent') Tambah Penelitian @endsection

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <form action="{{ url('doc/penelitian/add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header bg-primary d-flex align-items-center">
                    <h3 class="card-title ext-white mb-0 flex-grow-1">Form Tambah Penelitian</h3>
                    <a href="{{ url('doc/penelitian') }}" class="btn btn-sm btn-primary text-dark">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">

                <div class="form-group">
                    <label for="title">Judul Penelitian</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="abstract">Abstrak</label>
                    <textarea name="abstract" id="abstract" rows="4" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="keywords">Kata Kunci</label>
                    <input type="text" name="keywords" id="keywords" class="form-control">
                </div>

                {{-- Frame: Penulis Utama --}}
                <div class="card border border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Penulis Utama</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="penulis">Nama Penulis</label>
                            <select name="penulis" id="penulis" class="form-control select2bs4" required></select>
                            <small class="form-text text-muted">Jika tidak ditemukan, tulis manual nama penulis</small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Penulis</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="afiliasi">Afiliasi</label>
                            <input type="text" name="afiliasi" id="afiliasi" class="form-control" required>
                        </div>
                    </div>
                </div>

                {{-- Frame: Penulis Tambahan --}}
                <div class="card border border-secondary mb-4">
                    <div class="card-header bg-secondary d-flex align-items-center">
                        <h5 class="card-title text-white mb-0 flex-grow-1">Penulis Lain (Co-Authors)</h5>
                        <button type="button" class="btn btn-secondary btn-sm" id="add-author"><i class="fas fa-plus"></i> Tambah Author</button>
                    </div>
                    <div class="card-body" id="authors-wrapper">
                        <div class="author-group border rounded p-3 mb-3 bg-light">
                            <div class="form-group">
                                <label>Nama</label>
                                <select name="authors[0][author_name]" class="form-control author-name-select" required></select>
                                <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="authors[0][author_email]" class="form-control" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label>Afiliasi</label>
                                <input type="text" name="authors[0][author_affiliation]" class="form-control" placeholder="Afiliasi">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
                        </div>
                    </div>
                </div>

                    <div class="form-group">
                        <label for="biaya_penelitian">Biaya Penelitian</label>
                        <select name="biaya_penelitian" id="biaya_penelitian" class="form-control">
                            <option value="Mandiri">Mandiri</option>
                            <option value="Dibiayai">Dibiayai</option>
                        </select>
                    </div>

                    <div class="form-group" id="lembaga_biaya_group" style="display:none;">
                        <label for="lembaga_biaya">Lembaga Pembiaya</label>
                        <input type="text" name="lembaga_biaya" id="lembaga_biaya" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="terbit">Terbit</label>
                        <select name="terbit" id="terbit" class="form-control">
                            <option value="N">Tidak</option>
                            <option value="Y">Ya</option>
                        </select>
                    </div>

                    <div id="indeks_fields" style="display:none;">
                        <div class="form-group">
                            <label for="indeks_nasional">Terindeks Nasional</label>
                            <select name="indeks_nasional" id="indeks_nasional" class="form-control">
                                <option value="N">Tidak</option>
                                <option value="Y">Ya</option>
                            </select>
                        </div>
                        <div class="form-group" id="peringkat_nasional_group" style="display:none;">
                            <label for="peringkat_nasional">Peringkat Nasional</label>
                            <input type="text" name="peringkat_nasional" id="peringkat_nasional" class="form-control" placeholder="Sinta 1 s/d 6">
                        </div>
                        <div class="form-group">
                            <label for="indeks_internasional">Terindeks Internasional</label>
                            <select name="indeks_internasional" id="indeks_internasional" class="form-control">
                                <option value="N">Tidak</option>
                                <option value="Y">Ya</option>
                            </select>
                        </div>
                        <div class="form-group" id="peringkat_internasional_group" style="display:none;">
                            <label for="peringkat_internasional">Peringkat Internasional</label>
                            <input type="text" name="peringkat_internasional" id="peringkat_internasional" class="form-control" placeholder="Quartil/Q 1 s/d 4">
                        </div>
                        <div class="form-group">
                            <label for="indeks_lainnya">Indeks Lainnya</label>
                            <input type="text" name="indeks_lainnya" id="indeks_lainnya" class="form-control" placeholder="bisa lebih dari 1 lembaga indeks">
                        </div>
                        <div class="form-group">
                            <label for="nama_jurnal">Nama Jurnal</label>
                            <input type="text" name="nama_jurnal" id="nama_jurnal" class="form-control" placeholder="ex. Jurnal Ilmiah Ilmu Komputer Banthayo Lo Komputer">
                        </div>
                        <div class="form-group">
                            <label for="doi">DOI</label>
                            <input type="text" name="doi" id="doi" class="form-control" placeholder="ex. 10.1109/ICOEI65986.2025.11013214">
                        </div>
                        <div class="form-group">
                            <label for="link_jurnal">Link Jurnal</label>
                            <input type="url" name="link_jurnal" id="link_jurnal" class="form-control" placeholder="ex. https://ejurnal.unisan.ac.id/index.php/balok/article/view/1192">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tahun_akademik">Tahun</label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" placeholder="ex. 2024" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="form-group">
                        <label for="file_path">Upload File Jurnal</label>
                        <input type="file" name="file_path" id="file_path" class="form-control-file" accept=".pdf">
                        <small class="text-muted">Maks 5MB. Hanya format PDF.</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ url('doc/penelitian') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ url('') }}/public/plugins/select2/js/select2.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // === AUTOLOAD METADATA DARI DOI ===
$('#doi').on('blur', function() {
    let doi = $(this).val().trim();
    if (doi.length > 5) {
        // Panggil Crossref API
        $.get('https://api.crossref.org/works/' + encodeURIComponent(doi), function(res) {
            if (res.status === "ok" && res.message) {
                let meta = res.message;

                // Judul
                if (meta.title && meta.title[0]) {
                    $('#title').val(meta.title[0]);
                }

                // Nama Jurnal
                if (meta['container-title'] && meta['container-title'][0]) {
                    $('#nama_jurnal').val(meta['container-title'][0]);
                }

                // Tahun Akademik
                if (meta.issued && meta.issued['date-parts'] && meta.issued['date-parts'][0][0]) {
                    $('#tahun_akademik').val(meta.issued['date-parts'][0][0]);
                }

                // Kata Kunci (jika ada subject)
                if (meta.subject && meta.subject.length > 0) {
                    $('#keywords').val(meta.subject.join(', '));
                }

                // Abstrak (jika ada)
                if (meta.abstract) {
                    // Strip HTML tag jika ada
                    let abstrak = meta.abstract.replace(/<[^>]*>?/gm, '');
                    $('#abstract').val(abstrak);
                }

                // Penulis utama & co-author
                if (meta.author && meta.author.length > 0) {
                    // Penulis utama (first author)
                    let namaUtama = [meta.author[0].given, meta.author[0].family].filter(Boolean).join(' ');
                    if (namaUtama) {
                        $('#penulis').append(new Option(namaUtama.toUpperCase(), namaUtama.toUpperCase(), true, true)).trigger('change');
                    }
                    $('#email').val(''); // Crossref tidak menyimpan email, user harus input manual
                    $('#afiliasi').val(meta.author[0].affiliation && meta.author[0].affiliation[0] ? meta.author[0].affiliation[0].name : '');

                    // Co-author
                    let coAuthors = [];
                    for(let i=1; i<meta.author.length; i++) {
                        let nama = [meta.author[i].given, meta.author[i].family].filter(Boolean).join(' ');
                        let aff = meta.author[i].affiliation && meta.author[i].affiliation[0] ? meta.author[i].affiliation[0].name : '';
                        coAuthors.push({
                            author_name: nama.toUpperCase(),
                            author_email: '', // Tidak ada dari Crossref
                            author_affiliation: aff
                        });
                    }
                    // Render co-author, jika ada
                    if (coAuthors.length > 0) {
                        $('#authors-wrapper').empty();
                        coAuthors.forEach(function(author, idx) {
                            let authorGroup = `
                                <div class="author-group border rounded p-3 mb-2 bg-light">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <select name="authors[${idx}][author_name]" class="form-control author-name-select" required>
                                            <option value="${author.author_name}" selected>${author.author_name}</option>
                                        </select>
                                        <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="authors[${idx}][author_email]" class="form-control" value="" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label>Afiliasi</label>
                                        <input type="text" name="authors[${idx}][author_affiliation]" class="form-control" value="${author.author_affiliation}" placeholder="Afiliasi">
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
                                </div>
                            `;
                            $('#authors-wrapper').append(authorGroup);
                            initAuthorSelect2(`select[name="authors[${idx}][author_name]"]`);
                        });
                        authorIndex = coAuthors.length;
                    }
                }
            }
        });
    }
});

// ---- FUNGSI GLOBAL, LETAKKAN DI LUAR $(document).ready() ----
let authorIndex = 1;

// Helper: isi semua field dari data penelitian
function fillPenelitianForm(data, authors) {
    // Field utama
    $('#penulis').append(new Option(data.penulis_nama, data.penulis_nama, true, true)).trigger('change');
    $('#email').val(data.email || '');
    $('#afiliasi').val(data.afiliasi || '');
    $('#title').val(data.title || '');
    $('#abstract').val(data.abstract || '');
    $('#keywords').val(data.keywords || '');
    $('#biaya_penelitian').val(data.biaya_penelitian || '').trigger('change');
    $('#lembaga_biaya').val(data.lembaga_biaya || '');
    $('#terbit').val(data.terbit || '').trigger('change');
    $('#indeks_nasional').val(data.indeks_nasional || '').trigger('change');
    $('#peringkat_nasional').val(data.peringkat_nasional || '');
    $('#indeks_internasional').val(data.indeks_internasional || '').trigger('change');
    $('#peringkat_internasional').val(data.peringkat_internasional || '');
    $('#indeks_lainnya').val(data.indeks_lainnya || '');
    $('#nama_jurnal').val(data.nama_jurnal || '');
    $('#doi').val(data.doi || '');
    $('#link_jurnal').val(data.link_jurnal || '');
    $('#tahun_akademik').val(data.tahun_akademik || '');

    // CO-AUTHORS
    $('#authors-wrapper').empty();
    if (authors && authors.length > 0) {
        authors.forEach(function(author, idx) {
            let authorGroup = `
                <div class="author-group border rounded p-3 mb-2 bg-light">
                    <div class="form-group">
                        <label>Nama</label>
                        <select name="authors[${idx}][author_name]" class="form-control author-name-select" required>
                            <option value="${author.author_name}" selected>${author.author_name}</option>
                        </select>
                        <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="authors[${idx}][author_email]" class="form-control" value="${author.author_email || ''}" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label>Afiliasi</label>
                        <input type="text" name="authors[${idx}][author_affiliation]" class="form-control" value="${author.author_affiliation || ''}" placeholder="Afiliasi">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
                </div>
            `;
            $('#authors-wrapper').append(authorGroup);
            initAuthorSelect2(`select[name="authors[${idx}][author_name]"]`);
        });
        authorIndex = authors.length;
    } else {
        // Tidak ada co-author, tambahkan satu form kosong default
        let defaultAuthor = `
            <div class="author-group border rounded p-3 mb-3 bg-light">
                <div class="form-group">
                    <label>Nama</label>
                    <select name="authors[0][author_name]" class="form-control author-name-select" required></select>
                    <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="authors[0][author_email]" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <label>Afiliasi</label>
                    <input type="text" name="authors[0][author_affiliation]" class="form-control" placeholder="Afiliasi">
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
            </div>
        `;
        $('#authors-wrapper').append(defaultAuthor);
        initAuthorSelect2('select[name="authors[0][author_name]"]');
        authorIndex = 1;
    }
}

// Helper: reset field, hanya pertahankan field tertentu (misal title)
function resetFormExcept(fieldKeep) {
    $('#penulis').val(null).trigger('change');
    $('#email').val('');
    $('#afiliasi').val('');
    if (fieldKeep !== 'title') $('#title').val('');
    $('#abstract').val('');
    $('#keywords').val('');
    $('#biaya_penelitian').val('Mandiri').trigger('change');
    $('#lembaga_biaya').val('');
    $('#terbit').val('N').trigger('change');
    $('#indeks_nasional').val('N').trigger('change');
    $('#peringkat_nasional').val('');
    $('#indeks_internasional').val('N').trigger('change');
    $('#peringkat_internasional').val('');
    $('#indeks_lainnya').val('');
    $('#nama_jurnal').val('');
    $('#doi').val('');
    $('#link_jurnal').val('');
    $('#tahun_akademik').val('');

    // Reset co-author
    $('#authors-wrapper').empty();
    authorIndex = 1;
    let defaultAuthor = `
        <div class="author-group border rounded p-3 mb-3 bg-light">
            <div class="form-group">
                <label>Nama</label>
                <select name="authors[0][author_name]" class="form-control author-name-select" required></select>
                <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="authors[0][author_email]" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
                <label>Afiliasi</label>
                <input type="text" name="authors[0][author_affiliation]" class="form-control" placeholder="Afiliasi">
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
        </div>
    `;
    $('#authors-wrapper').append(defaultAuthor);
    initAuthorSelect2('select[name="authors[0][author_name]"]');
}

// Helper select2 co-author (harus global supaya bisa dipakai di function lain)
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
    }).on('select2:select', function(e) {
        let val = e.params.data.text;
        let $select = $(this);
        if (e.params.data.id === val && /[a-z]/.test(val)) {
            let upper = val.toUpperCase();
            setTimeout(function() {
                $select.find('option[value="' + val + '"]').remove();
                let newOption = new Option(upper, upper, true, true);
                $select.append(newOption).val(upper).trigger('change.select2');
            }, 1);
        }
    });
}

$(document).ready(function() {
    // Inisialisasi select2 penulis utama
    $('#penulis').select2({
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
    }).on('select2:select', function(e) {
        let val = e.params.data.text;
        let $select = $(this);
        // Deteksi manual tag dan jika ada huruf kecil (bukan dari database)
        if (e.params.data.id === val && /[a-z]/.test(val)) {
            let upper = val.toUpperCase();
            // Hapus option lama, tambahkan yang baru (uppercase, tetap ada spasi)
            setTimeout(function() {
                $select.find('option[value="' + val + '"]').remove();
                let newOption = new Option(upper, upper, true, true);
                $select.append(newOption).val(upper).trigger('change.select2');
            }, 1);
        }
    });

    // Tampilkan input lembaga pembiaya jika Dibiayai
    $('#biaya_penelitian').on('change', function() {
        $('#lembaga_biaya_group').toggle(this.value === 'Dibiayai');
    });

    // Tampilkan field indeks jika terbit
    $('#terbit').on('change', function() {
        $('#indeks_fields').toggle(this.value === 'Y');
    });

    $('#indeks_nasional').on('change', function() {
        $('#peringkat_nasional_group').toggle(this.value === 'Y');
    });

    $('#indeks_internasional').on('change', function() {
        $('#peringkat_internasional_group').toggle(this.value === 'Y');
    });

    // Tambah co-author dinamis
    $('#add-author').on('click', function () {
        const newAuthor = `
            <div class="author-group border rounded p-3 mb-2 bg-light">
                <div class="form-group">
                    <label>Nama</label>
                    <select name="authors[${authorIndex}][author_name]" class="form-control author-name-select" required></select>
                    <small class="form-text text-muted">Cari atau ketik manual nama penulis</small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="authors[${authorIndex}][author_email]" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <label>Afiliasi</label>
                    <input type="text" name="authors[${authorIndex}][author_affiliation]" class="form-control" placeholder="Afiliasi">
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-author mt-2"><i class="fas fa-trash"></i> Hapus</button>
            </div>
        `;
        $('#authors-wrapper').append(newAuthor);
        initAuthorSelect2(`select[name="authors[${authorIndex}][author_name]"]`);
        authorIndex++;
    });

    // Hapus co-author
    $(document).on('click', '.remove-author', function () {
        $(this).closest('.author-group').remove();
    });

    // Inisialisasi awal untuk authors[0]
    initAuthorSelect2('select[name="authors[0][author_name]"]');

});
</script>
@endsection
