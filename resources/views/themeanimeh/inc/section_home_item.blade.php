<div class="movie-item" id="movie-id-{{ $movie->id }}">
    <a href="{{ $movie->getUrl() }}" title="{{ $movie->name }} - {{ $movie->origin_name }} ({{ $movie->publish_year }})">
        <div class="episode-latest">
            <span>{{ $movie->episode_current }}</span>
        </div>
        <div>
            <img src="{{ $movie->getThumbUrl() }}"
                alt="{{ $movie->name }} - {{ $movie->origin_name }} ({{ $movie->publish_year }})" />
        </div>
        <div class="score"> {{ $movie->getRatingStar() }} </div>
        <div class="name-movie"> {{ $movie->name }} </div>
    </a>
</div>
