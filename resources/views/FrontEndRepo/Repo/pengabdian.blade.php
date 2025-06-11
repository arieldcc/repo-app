@extends('frontLayouts.main')

@section('css')
<link href="{{ url('public/repo/css/style-skripsi.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-8">
    <h2>PENGABDIAN</h2>
    <p>Halaman {{ $page }} dari {{ $total_pages }} &mdash; Total Data: {{ $jum_data }}</p>

    <div class="filter-box">
        <div class="row">
            <div class="col-md-6">
                <label for="pencarian_">Pencarian Berdasarkan:</label>
                <select name="pencarian" id="pencarian_" class="form-control">
                    <option value="judul">Judul Pengabdian</option>
                    <option value="abstrak">Abstrak</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tahun">Tahun Akademik:</label>
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

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="input-group">
                    <input name="search_title" id="search_title" type="text" class="form-control" placeholder="Masukkan kata kunci..." onkeydown="filterByTitle(event)">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="filterByTitle(event)">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="skripsi-list mt-4">
        @forelse($pengabdian_data as $item)
        <div class="skripsi-item d-flex align-items-start">
            <img src="{{ asset('public/repo/img/' . $item->icon) }}" alt="File Icon">
            <div>
                <p class="skripsi-item-title position-relative">
                    <a href="{{ url('/penelitian/detail/pengabdian/' . $item->document_id) }}" class="text-decoration-none text-dark">
                        {{ $item->title }}
                    </a>

                    @if ($item->file_path)
                        <a href="{{ url('doc/download/pengabdian/' . $item->document_id) }}" class="download-btn btn btn-success position-absolute" target="_blank">
                            <img src="{{ asset('public/repo/img/' . $item->icon) }}" alt="File Icon" class="file-icon"> Download
                        </a>
                    @else
                        &nbsp;<span class="text-danger position-absolute">Berkas kosong</span>
                    @endif
                </p>
                <p class="skripsi-item-meta">Oleh: {{ $item->penulis_nama }} &nbsp;&nbsp; Tanggal: {{ date('d/m/Y', strtotime($item->upload_date)) }}</p>
            </div>
        </div>
        @empty
        <p>Tidak ada data pengabdian.</p>
        @endforelse
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="?page={{ $page - 1 }}&judul={{ request('judul') }}&tahun={{ request('tahun') }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $i }}&judul={{ request('judul') }}&tahun={{ request('tahun') }}">{{ $i }}</a>
                </li>
            @endfor

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
                    <small>Pengabdian tahun {{ $tahun->tahun_akademik }}: {{ $tahun->jumlah_data }} data</small>
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('js')
<script>
function filterByTitle(event) {
    if (event.type === 'keydown' && event.key !== 'Enter') return;

    const searchTitle = document.getElementById('search_title').value;
    const searchYear = document.getElementById('tahun').value;
    const searchType = document.getElementById('pencarian_').value;
    const baseUrl = "{{ url()->current() }}";

    let query = '';
    if (searchType === 'judul') query = `judul=${encodeURIComponent(searchTitle)}`;
    else if (searchType === 'abstrak') query = `abstrak=${encodeURIComponent(searchTitle)}`;

    if (searchYear) query += `&tahun=${encodeURIComponent(searchYear)}`;

    window.location.href = `${baseUrl}?${query}&page=1`;
}
</script>
@endsection
