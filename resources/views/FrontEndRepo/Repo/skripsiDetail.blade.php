@extends('frontLayouts.main')

@section('content')
<div class="col-md-10 offset-md-1">
    <h2 class="mb-4 text-success">{{ $type }}</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Header judul dan ikon --}}
            <div class="d-flex align-items-start mb-4 border-bottom pb-3">
                <img src="{{ asset('public/repo/' . $ikon_file) }}" alt="File Icon" class="me-3" style="width: 64px;">
                <div>
                    <h4 class="mb-1">{{ $skripsi_list->title }}</h4>
                    <p class="mb-0 text-muted"><small><strong>Tanggal Upload:</strong> {{ date('d/m/Y', strtotime($skripsi_list->upload_date)) }}</small></p>
                </div>
            </div>

            {{-- Info Mahasiswa --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Penulis / NIM:</strong><br>{{ $skripsi_list->nama_mahasiswa }} / {{ $skripsi_list->nim }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Program Studi:</strong><br>{{ $skripsi_list->nama_program_studi }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Tahun Akademik:</strong><br>{{ $skripsi_list->tahun_akademik }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Kata Kunci:</strong><br>{{ $skripsi_list->keywords }}</p>
                </div>
            </div>

            {{-- Abstrak --}}
            <div class="mb-4">
                <p><strong>Abstrak:</strong></p>
                <div class="bg-light p-3 rounded" style="white-space: pre-line;">
                    {{ $skripsi_list->abstract }}
                </div>
            </div>

            {{-- Preview dan Download --}}
            <div class="mt-4">
                <h5 class="mb-3">Berkas Lampiran</h5>
                @if ($skripsi_list->file_path)
                    @php
                        $ext = strtolower(pathinfo($skripsi_list->file_path, PATHINFO_EXTENSION));
                    @endphp

                    @if ($ext === 'pdf')
                        <iframe src="{{ url('/public/storage/uploads/'. strtolower($type) .'/'.$skripsi_list->file_path.'?preview=true') }}#toolbar=0"
                                frameborder="0" width="100%" height="500px"
                                class="mb-3 rounded border"></iframe>
                    @endif

                    <a href="{{ url('doc/download/' . strtolower($type) . '/' . $skripsi_list->document_id) }}"
                       class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-download"></i> Unduh File
                    </a>
                @else
                    <div class="text-danger">Berkas tidak tersedia.</div>
                @endif
            </div>

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
