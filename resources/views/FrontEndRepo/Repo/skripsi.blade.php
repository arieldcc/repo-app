@extends('frontLayouts.main')

@section('css')
    <link href="{{ url('public/repo/css/style-skripsi.css') }}" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-8">
    <h2>SKRIPSI</h2>
    <p>Halaman {{ $page }} dari {{ $total_pages }} &mdash; Total Data: {{ $jum_data }}</p>

    <div class="filter-box">
        <div class="row">
            <div class="col-md-6">
                <label for="prodi">Program Studi:</label>
                <select id="prodi" class="form-control" onchange="filterByTitle(event)">
                    <option value="">- Semua -</option>
                    @foreach($prodi_list as $prodi)
                        <option value="{{ $prodi->nama_program_studi }}" {{ ($selected_prodi == $prodi->nama_program_studi) ? 'selected' : '' }}>
                            {{ $prodi->nama_program_studi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="tahun">Tahun:</label>
                <select id="tahun" class="form-control" onchange="filterByTitle(event)">
                    <option value="">- Semua -</option>
                    @foreach($tahun_list as $tahun)
                    <option value="{{ $tahun->tahun_akademik }}" {{ request('tahun') == $tahun->tahun_akademik ? 'selected' : '' }}>
                        {{ $tahun->tahun_akademik }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <label for="pencarian_">Pencarian Berdasarkan:</label>
                <select name="pencarian" id="pencarian_" class="form-control">
                    <option value="judul">Judul Penelitian</option>
                    <option value="abstrak">Abstrak</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                {{-- <label for="search_title">Kata Pencarian:</label> --}}
                <div class="input-group">
                    <input name="search_title" id="search_title" type="text" class="form-control" placeholder="Masukkan kata kunci pencarian anda..." onkeydown="filterByTitle(event)">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="filterByTitle(event)">Cari..</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="skripsi-list">
        @foreach($skripsi_data as $skripsi)
        <div class="skripsi-item d-flex align-items-start">
            <img src="{{ asset('public/repo/img/' . $skripsi->icon) }}" alt="File Icon">
            <div>
                <p class="skripsi-item-title position-relative">
                    <a href="{{ url('doc/detail/skripsi/' . $skripsi->document_id) }}" class="text-decoration-none text-dark">
                        {{ $skripsi->title }}
                    </a>

                    @if ($skripsi->file_path)
                        <a href="{{ url('doc/download/skripsi/' . $skripsi->document_id) }}" class="download-btn btn btn-success position-absolute" target="_blank">
                            <img src="{{ asset('public/repo/img/' . $skripsi->icon) }}" alt="File Icon" class="file-icon"> Download Berkas
                        </a>
                    @else
                        &nbsp;<span class="text-danger position-absolute">Berkas kosong</span>
                    @endif
                </p>
                <p class="skripsi-item-meta">Oleh: {{ $skripsi->nama_mahasiswa }} &nbsp;&nbsp; Tanggal: {{ date('d/m/Y', strtotime($skripsi->upload_date)) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="?page={{ $page - 1 }}&judul={{ request('judul') }}&tahun={{ request('tahun') }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @if($page > 3)
                <li class="page-item"><a class="page-link" href="?page=1&judul={{ request('judul') }}&tahun={{ request('tahun') }}">1</a></li>
                @if($page > 4)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            @for ($i = max($page - 2, 1); $i <= min($page + 2, $total_pages); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $i }}&judul={{ request('judul') }}&tahun={{ request('tahun') }}">{{ $i }}</a>
                </li>
            @endfor

            @if($page < $total_pages - 2)
                @if($page < $total_pages - 3)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="?page={{ $total_pages }}&judul={{ request('judul') }}&tahun={{ request('tahun') }}">{{ $total_pages }}</a></li>
            @endif

            <li class="page-item {{ $page == $total_pages ? 'disabled' : '' }}">
                <a class="page-link" href="?page={{ $page + 1 }}&judul={{ request('judul') }}&tahun={{ request('tahun') }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="col-md-4">
    <h4>ARSIP</h4>
    <div class="arsip-box">
        @foreach($tahun_list as $tahun)
        <div class="arsip-item d-flex align-items-center">
            <img src="{{ asset('public/repo/img/item.png') }}" alt="Check Icon" class="me-2">
            <div>
                <p class="m-0">
                    <a href="?tahun={{ $tahun->tahun_akademik }}" class="text-decoration-none text-dark">
                        {{ $tahun->tahun_akademik }}
                    </a>
                    <br>
                    <small>Skripsi tahun {{ $tahun->tahun_akademik }}: {{ $tahun->jumlah_data }} data</small>
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        $("#search_title").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ url('api/autocomplete-skripsi') }}",
                    dataType: "json",
                    data: {
                        term: request.term,
                        pencarian: $("#pencarian_").val(),
                        prodi: $("#prodi").val(),          // tambahkan prodi
                        tahun: $("#tahun").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                $('#search_title').val(ui.item.value);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + item.label + "<br><small>Oleh: " + item.penulis + " - Tahun: " + item.tahun + "</small></div>")
                .appendTo(ul);
        };

        $.ui.autocomplete.prototype._resizeMenu = function () {
            var ul = this.menu.element;
            ul.outerWidth(this.element.outerWidth());
            ul.css({
                "max-height": "500px",
                "overflow-y": "auto",
                "overflow-x": "hidden"
            });
        };
    });
    function filterByTitle(event) {
        if (event.type === 'keydown' && event.key !== 'Enter') return;

        const searchTitle = document.getElementById('search_title').value;
        const searchYear = document.getElementById('tahun').value;
        const searchType = document.getElementById('pencarian_').value;
        const searchProdi = document.getElementById('prodi').value;
        const baseUrl = "{{ url()->current() }}";

        let query = '';
        if (searchType === 'judul') query = `judul=${encodeURIComponent(searchTitle)}`;
        else if (searchType === 'abstrak') query = `abstrak=${encodeURIComponent(searchTitle)}`;

        if (searchYear) query += `&tahun=${encodeURIComponent(searchYear)}`;
        if (searchProdi) query += `&prodi=${encodeURIComponent(searchProdi)}`;

        window.location.href = `${baseUrl}?${query}&page=1`;
    }

</script>
@endsection
