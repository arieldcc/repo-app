@extends('frontLayouts.main')

@section('title', $setting->site_name ?? 'Repository UNISAN')

@section('slidebar')
    @include('frontLayouts.sliderbar')
@endsection

@section('content')
<div class="col-md-8 offset-md-2 text-center">
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
</div>
@endsection
