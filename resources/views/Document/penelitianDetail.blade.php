@extends('layouts.main')

@section('title') Detail Penelitian @endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex align-items-center">
            <h4 class="card-title text-white mb-0 flex-grow-1">Detail Penelitian</h4>
            <a href="{{ url('doc/penelitian') }}" class="btn btn-sm btn-primary text-dark">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-md-4">Judul</dt>
                <dd class="col-md-8">{{ $getPenelitian->title }}</dd>

                <dt class="col-md-4">Penulis Utama</dt>
                <dd class="col-md-8"><strong>{{ $getPenelitian->penulis_nama }}</strong><br>
                    <small class="text-muted">{{ $getPenelitian->email }}</small><br>
                    <small>{{ $getPenelitian->afiliasi }}</small>
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
                <dd class="col-md-8">{{ $getPenelitian->abstract ?? '-' }}</dd>

                <dt class="col-md-4">Kata Kunci</dt>
                <dd class="col-md-8">{{ $getPenelitian->keywords ?? '-' }}</dd>

                <dt class="col-md-4">Biaya Penelitian</dt>
                <dd class="col-md-8">{{ $getPenelitian->biaya_penelitian }} @if($getPenelitian->biaya_penelitian == 'Dibiayai') ({{ $getPenelitian->lembaga_biaya }}) @endif</dd>

                <dt class="col-md-4">Tahun</dt>
                <dd class="col-md-8">{{ $getPenelitian->tahun_akademik }}</dd>

                <dt class="col-md-4">Status Terbit</dt>
                <dd class="col-md-8">
                    @if($getPenelitian->terbit == 'Y')
                        <span class="badge badge-success">Terbit</span>
                        <br>
                        <small>
                            Indeks: <br>
                            Nasional: {{ $getPenelitian->indeks_nasional }} @if($getPenelitian->indeks_nasional == 'Y') ({{ $getPenelitian->peringkat_nasional }}) @endif <br>
                            Internasional: {{ $getPenelitian->indeks_internasional }} @if($getPenelitian->indeks_internasional == 'Y') ({{ $getPenelitian->peringkat_internasional }}) @endif <br>
                            Lainnya: {{ $getPenelitian->indeks_lainnya ?? '-' }} <br>
                            Nama Jurnal: {{ $getPenelitian->nama_jurnal ?? '-' }} <br>
                            DOI: {{ $getPenelitian->doi ?? '-' }}
                        </small>
                        @if ($getPenelitian->link_jurnal)
                            <br><a href="{{ $getPenelitian->link_jurnal }}" target="_blank" class="btn btn-sm btn-info mt-2">Lihat Jurnal</a>
                        @endif
                    @else
                        <span class="badge badge-secondary">Belum Terbit</span>
                    @endif
                </dd>

                <dt class="col-md-4">File Jurnal</dt>
                <dd class="col-md-8">
                    @if ($getPenelitian->file_path)
                        <iframe src="{{ url('/public/storage/uploads/penelitian/'.$getPenelitian->file_path) }}" frameborder="0" width="100%" height="500px"></iframe>
                        <br><a href="{{ url('/public/storage/uploads/penelitian/'.$getPenelitian->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Unduh File</a>
                    @else
                        <span class="text-muted">Belum diunggah</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
</div>
@endsection
