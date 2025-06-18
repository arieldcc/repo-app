@extends('frontLayouts.main')

@section('title', $setting->site_name ?? 'Repository UNISAN')

@section('slidebar')
    @include('frontLayouts.sliderbar')
@endsection

@section('css')
    <style>
        .download-btn {
            position: absolute;
            top: 0;
            right: 0;
            display: none;
            z-index: 10;
        }

        .hover-show-download:hover .download-btn {
            display: inline-block;
        }

        .download-btn {
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .hover-show-download:hover .download-btn {
            opacity: 1;
        }

        .skripsi-item,
        .skripsi-item * {
            text-align: left !important;
        }

    </style>
@endsection

@section('content')
<div class="col-md-12 offset-md-0 text-center">
    <h1 class="text-success">{{ $setting->site_name ?? 'Repository UNISAN' }}</h1>
    @if(!empty($setting->site_tagline))
        <p class="text-muted">{{ $setting->site_tagline }}</p>
    @endif

    <hr>

    @if(count($customPages))
        @foreach($customPages as $page)
            <div class="custom-page-section mb-4 text-start">
                <h3 class="text-primary">{{ $page->title }}</h3>
                <div class="page-content">
                    {!! $page->content !!}
                </div>
                <hr>
            </div>
        @endforeach
    @endif

    <!-- TIGA KOLOM INFORMASI -->
    <div class="row mt-5">
        <!-- Berkas Terbaru -->
        <div class="col-md-4">
            <h4 class="text-success">Berkas Terbaru</h4>
            @foreach($latestDocuments as $doc)
                <div class="skripsi-item d-flex align-items-start mb-3 hover-show-download" style="position: relative;">
                    <img src="{{ asset('public/repo/img/' . ($doc->icon ?? 'empty.png')) }}" alt="File Icon" class="me-2" style="width: 32px;">
                    <div>
                        <p class="skripsi-item-title mb-1">
                            <a href="{{ $doc->type === 'penelitian'
                                ? url('/penelitian/detail/penelitian/' . $doc->document_id)
                                : url('doc/detail/' . strtolower($doc->type) . '/' . $doc->document_id)
                            }}" class="text-decoration-none text-dark">
                                {{ Str::limit($doc->title, 60) }}
                            </a>
                        </p>
                        <small class="text-muted">Tahun: {{ $doc->tahun_akademik }}</small>
                    </div>

                    @if ($doc->file_path)
                        <a href="{{ url('doc/download/' . strtolower($doc->type) . '/' . $doc->document_id) }}"
                        class="btn btn-sm btn-success download-btn"
                        target="_blank">
                            ⬇ Download
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Berkas Sering Dilihat -->
        <div class="col-md-4">
            <h4 class="text-warning">Berkas Sering Dilihat</h4>
            @foreach($mostViewed as $doc)
            <div class="skripsi-item d-flex align-items-start mb-3 hover-show-download text-start" style="position: relative;">
                <img src="{{ asset('public/repo/img/' . ($doc->icon ?? 'empty.png')) }}" alt="File Icon" class="me-2" style="width: 32px;">
                <div>
                    <p class="skripsi-item-title mb-1">
                        <a href="{{ $doc->type === 'penelitian'
                            ? url('/penelitian/detail/penelitian/' . $doc->document_id)
                            : url('doc/detail/' . strtolower($doc->type) . '/' . $doc->document_id)
                        }}" class="text-decoration-none text-dark">
                            {{ Str::limit($doc->title, 60) }}
                        </a>
                    </p>
                    <small class="text-muted d-block">Tahun: {{ $doc->tahun_akademik }} | Dilihat: {{ $doc->view_count }}</small>
                </div>

                @if ($doc->file_path)
                    <a href="{{ url('doc/download/' . strtolower($doc->type) . '/' . $doc->document_id) }}"
                    class="btn btn-sm btn-success download-btn"
                    target="_blank">
                        ⬇ Download
                    </a>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Berkas Sering Diunduh -->
        <div class="col-md-4">
            <h4 class="text-primary">Berkas Sering Diunduh</h4>
            @foreach($mostDownloaded as $doc)
            <div class="skripsi-item d-flex align-items-start mb-3 hover-show-download text-start" style="position: relative;">
                <img src="{{ asset('public/repo/img/' . ($doc->icon ?? 'empty.png')) }}" alt="File Icon" class="me-2" style="width: 32px;">
                <div>
                    <p class="skripsi-item-title mb-1">
                        <a href="{{ $doc->type === 'penelitian'
                            ? url('/penelitian/detail/penelitian/' . $doc->document_id)
                            : url('doc/detail/' . strtolower($doc->type) . '/' . $doc->document_id)
                        }}" class="text-decoration-none text-dark">
                            {{ Str::limit($doc->title, 60) }}
                        </a>
                    </p>
                    <small class="text-muted d-block">Tahun: {{ $doc->tahun_akademik }} | ⬇ Diunduh: {{ $doc->download_count }}</small>
                </div>

                @if ($doc->file_path)
                    <a href="{{ url('doc/download/' . strtolower($doc->type) . '/' . $doc->document_id) }}"
                    class="btn btn-sm btn-success download-btn"
                    target="_blank">
                        ⬇ Download
                    </a>
                @endif
            </div>
            @endforeach
        </div>

</div>
@endsection
