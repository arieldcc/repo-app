@if(count($sliders))
<div id="carouselExampleIndicators" class="carousel slide custom-carousel mt-3" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($sliders as $index => $slider)
            <button type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="{{ $index }}"
                    class="{{ $index == 0 ? 'active' : '' }}"
                    aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
            <img src="{{ url('/public/storage/uploads/reposlider/' . $slider->image_path) }}"
                 class="d-block w-100 slider-img" alt="Slide {{ $index + 1 }}">
            <div class="overlay"></div>
            <div class="carousel-caption d-none d-md-block">
                <h5>{{ $slider->title }}</h5>
                <p>{{ $slider->subtitle }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
@endif
