@extends('layouts.main')

@section('title') Detail Pengabdian @endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success d-flex align-items-center">
            <h4 class="card-title text-white mb-0 flex-grow-1">Detail Pengabdian</h4>
            <a href="{{ url('doc/pengabdian') }}" class="btn btn-sm btn-success text-dark">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-md-4">Judul</dt>
                <dd class="col-md-8">{{ $getPengabdian->title }}</dd>

                <dt class="col-md-4">Penulis Utama</dt>
                <dd class="col-md-8">
                    <strong>{{ $getPengabdian->penulis }}</strong><br>
                    <small class="text-muted">{{ $getPengabdian->email }}</small><br>
                    <small>{{ $getPengabdian->afiliasi }}</small>
                </dd>

                @if ($getAuthors->count() > 0)
                <dt class="col-md-4">Co-Authors</dt>
                <dd class="col-md-8">
                    <ul class="list-unstyled mb-0">
                        @foreach ($getAuthors as $author)
                            <li>
                                <strong>{{ $author->author_name }}</strong><br>
                                <small class="text-muted">{{ $author->author_email }}</small><br>
                                <small>{{ $author->author_affiliation }}</small>
                            </li>
                            <hr class="my-2">
                        @endforeach
                    </ul>
                </dd>
                @endif

                <dt class="col-md-4">Abstrak</dt>
                <dd class="col-md-8">{{ $getPengabdian->abstract ?? '-' }}</dd>

                <dt class="col-md-4">Kata Kunci</dt>
                <dd class="col-md-8">{{ $getPengabdian->keywords ?? '-' }}</dd>

                <dt class="col-md-4">Biaya Pengabdian</dt>
                <dd class="col-md-8">{{ $getPengabdian->biaya_penelitian }}
                    @if($getPengabdian->biaya_penelitian == 'Dibiayai') ({{ $getPengabdian->lembaga_biaya }}) @endif
                </dd>

                <dt class="col-md-4">Tahun</dt>
                <dd class="col-md-8">{{ $getPengabdian->tahun_akademik }}</dd>

                <dt class="col-md-4">Status Terbit</dt>
                <dd class="col-md-8">
                    @if($getPengabdian->terbit == 'Y')
                        <span class="badge badge-success">Terbit</span><br>
                        <small>
                            Indeks: <br>
                            Nasional: {{ $getPengabdian->indeks_nasional }}
                            @if($getPengabdian->indeks_nasional == 'Y') ({{ $getPengabdian->peringkat_nasional }}) @endif <br>
                            Lainnya: {{ $getPengabdian->indeks_lainnya ?? '-' }}
                        </small>
                        @if ($getPengabdian->link_jurnal)
                            <br><a href="{{ $getPengabdian->link_jurnal }}" target="_blank" class="btn btn-sm btn-info mt-2">Lihat Jurnal</a>
                        @endif
                    @else
                        <span class="badge badge-secondary">Belum Terbit</span>
                    @endif
                </dd>

                <dt class="col-md-4">File Jurnal</dt>
                <dd class="col-md-8">
                    @if ($getPengabdian->file_path)
                        <iframe src="{{ url('/public/storage/uploads/pengabdian/'.$getPengabdian->file_path) }}" frameborder="0" width="100%" height="500px"></iframe>
                        <br><a href="{{ url('/public/storage/uploads/pengabdian/'.$getPengabdian->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success mt-2">Unduh File</a>
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
</div>
@endsection
