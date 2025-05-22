@extends('layouts.main')

@section('title') Detail Skripsi @endsection

@section('css')
    <style>
        .label-detail {
            font-weight: bold;
        }
        .value-detail {
            margin-bottom: 15px;
        }
        iframe, .file-preview-frame {
            width: 100%;
            border: 1px solid #ccc;
            margin-top: 15px;
        }
        iframe {
            height: 600px;
        }
        img.preview-img {
            max-width: 100%;
            max-height: 600px;
            display: block;
            margin-top: 15px;
            border: 1px solid #ddd;
            padding: 5px;
        }
    </style>
@endsection

@section('headcontent') Detail Skripsi @endsection

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-info d-flex align-items-center">
                <h4 class="card-title text-white mb-0 flex-grow-1">Informasi Skripsi</h4>
                <a href="{{ url('doc/skripsi') }}" class="btn btn-sm btn-info text-dark">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">

                {{-- Data Umum --}}
                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">NIM</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->nim ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Nama Mahasiswa</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->nama_mahasiswa ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Program Studi</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->nama_program_studi ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Judul Skripsi</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->title ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Abstrak</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->abstract ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Kata Kunci</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->keywords ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Tahun Akademik</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->tahun_akademik ?? '-' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Status</div>
                    <div class="col-sm-8 value-detail">{{ ucfirst($getRecord->status ?? '-') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">Tanggal Upload</div>
                    <div class="col-sm-8 value-detail">{{ $getRecord->upload_date ?? '-' }}</div>
                </div>

                {{-- File Dokumen --}}
                <div class="row mb-3">
                    <div class="col-sm-4 label-detail">File Dokumen</div>
                    <div class="col-sm-8 value-detail">
                        @php
                            $filePath = $getRecord->file_path ?? '';
                            $fileUrl = $filePath ? url('/public/storage/uploads/skripsi/' . $filePath) : null;
                            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        @endphp

                        @if ($fileUrl)
                            <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-success" target="_blank"><i class="fas fa-download"></i> Download</a>
                            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i> Cetak Halaman</button>
                        @else
                            <span class="text-muted">Tidak tersedia</span>
                        @endif
                    </div>
                </div>

                {{-- Preview File --}}
                @if ($fileUrl)
                    @if ($ext === 'pdf')
                        <h5 class="mt-4">Pratinjau PDF:</h5>
                        <iframe src="{{ $fileUrl }}#toolbar=0" type="application/pdf"></iframe>

                    @elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                        <h5 class="mt-4">Pratinjau Gambar:</h5>
                        <img src="{{ $fileUrl }}" alt="Preview Gambar" class="preview-img">

                    @elseif (in_array($ext, ['doc', 'docx']))
                        <h5 class="mt-4">Pratinjau Word (via Office Online):</h5>
                        <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode($fileUrl) }}" frameborder="0" class="file-preview-frame" height="600px"></iframe>

                    @else
                        <p class="text-muted mt-2">Format file tidak didukung untuk pratinjau langsung.</p>
                    @endif
                @endif

                <div class="text-right mt-4">
                    <a href="{{ url('doc/skripsi') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
