<div class="ah-carousel">
    <div class="margin-10-0 bg-gray-2">
        <div
            class="fs-17 fw-700 padding-0-20 color-gray inline-flex height-40 flex-hozi-center bg-black border-l-t">
            Phim đề cử </div>
    </div>
    <div class="ah-frame-bg owl-carousel owl-theme">
        @foreach ($recommendations as $movie)
            <div>
                <a href="{{$movie->getUrl()}}">
                    <div>
                        <img src="{{$movie->getThumbUrl()}}"
                            alt="{{$movie->name}} - {{$movie->origin_name}} ({{$movie->publish_year}})" />
                    </div>
                    <div class="name">{{$movie->name}}</div>
                    <div class="episode_latest"> {{$movie->episode_current}} </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
