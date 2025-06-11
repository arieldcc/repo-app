@extends('frontLayouts.main')

@section('title') Detail Penelitian @endsection

@section('content')
<div class="col-md-10 offset-md-1">
    <h2 class="mb-4 text-success">Detail Penelitian</h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="mb-3 text-primary">{{ $penelitian->title }}</h4>

            <p><strong>Penulis Utama:</strong> {{ $penelitian->penulis_nama }} <br>
               <small class="text-muted">{{ $penelitian->email }}</small> <br>
               <small>{{ $penelitian->afiliasi }}</small>
            </p>

            @if($coAuthors->count())
                <hr>
                <h5>Co-Authors</h5>
                @foreach($coAuthors as $author)
                    <p>
                        <strong>{{ $author->author_name }}</strong><br>
                        <small class="text-muted">{{ $author->author_email }}</small><br>
                        <small>{{ $author->author_affiliation }}</small>
                    </p>
                @endforeach
            @endif

            <hr>
            <p><strong>Abstrak:</strong><br>{{ $penelitian->abstract ?? '-' }}</p>
            <p><strong>Kata Kunci:</strong> {{ $penelitian->keywords ?? '-' }}</p>
            <p><strong>Biaya Penelitian:</strong> {{ $penelitian->biaya_penelitian }}
                @if($penelitian->biaya_penelitian == 'Dibiayai') ({{ $penelitian->lembaga_biaya }}) @endif
            </p>
            <p><strong>Tahun Akademik:</strong> {{ $penelitian->tahun_akademik }}</p>

            <p><strong>Status Terbit:</strong>
                @if($penelitian->terbit == 'Y')
                    <span class="badge bg-success">Terbit</span>
                    <br>
                    <small>
                        Indeks:
                        <ul>
                            <li>Nasional: {{ $penelitian->indeks_nasional }} @if($penelitian->indeks_nasional == 'Y') ({{ $penelitian->peringkat_nasional }}) @endif</li>
                            <li>Internasional: {{ $penelitian->indeks_internasional }} @if($penelitian->indeks_internasional == 'Y') ({{ $penelitian->peringkat_internasional }}) @endif</li>
                            <li>Lainnya: {{ $penelitian->indeks_lainnya ?? '-' }}</li>
                        </ul>
                    </small>
                    @if($penelitian->link_jurnal)
                        <a href="{{ $penelitian->link_jurnal }}" target="_blank" class="btn btn-sm btn-info">Lihat Jurnal</a>
                    @endif
                @else
                    <span class="badge bg-secondary">Belum Terbit</span>
                @endif
            </p>

            <hr>
            <p><strong>File Jurnal:</strong></p>
            @if($penelitian->file_path)
                <iframe src="{{ url('doc/download/penelitian/'.$penelitian->document_id.'?preview=true') }}#toolbar=0" frameborder="0" width="100%" height="500px"></iframe>
                <br>
                <a href="{{ url('doc/download/penelitian/'.$penelitian->document_id) }}" class="btn btn-outline-primary mt-3" target="_blank">Unduh File</a>
            @else
                <span class="text-muted">File belum tersedia</span>
            @endif

        </div>

        <div class="card-footer">
            {{-- Tombol kembali --}}
            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
