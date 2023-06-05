<div class="ah-frame-bg">
    <div class="heading flex flex-space-auto fw-700">
        <span>Có thể bạn muốn xem!</span>
    </div>
    <div class="movies-list">
        @foreach ($movie_related as $movie)
            @include('themes::themeanimeh.inc.section_home_item')
        @endforeach
    </div>
</div>
