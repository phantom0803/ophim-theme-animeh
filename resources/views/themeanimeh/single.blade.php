@extends('themes::themeanimeh.layout')

@php
    $watch_url = '';
    if (!$currentMovie->is_copyright && count($currentMovie->episodes) && $currentMovie->episodes[0]['link'] != '') {
        $watch_url = $currentMovie->episodes
            ->sortBy([['server', 'asc']])
            ->groupBy('server')
            ->first()
            ->sortByDesc('name', SORT_NATURAL)
            ->groupBy('name')
            ->last()
            ->sortByDesc('type')
            ->first()
            ->getUrl();
    }
@endphp

@push('header')
    <style>
        .single-button-text {
            font-size: 28px !important;
        }

        @media only screen and (max-width: 600px) {
            .single-button-text {
                font-size: 14px !important;
            }

            .hidden-mobile {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="info-movie">

        <div id="modal" class="modal" style="display: block; visibility: hidden; top: 0px; transition: top 0.3s ease 0s;">
            <div>
                <div>{{ $currentMovie->getRatingStar() }} sao / {{ $currentMovie->getRatingCount() }} lượt đánh giá</div>
                <a id="close-modal-rated" href="javascript:;">
                    <span class="material-icons-round margin-0-5"> close </span>
                </a>
            </div>
            <div>
                <div id="movies-rating-star" class="rated-star flex flex-hozi-center flex-ver-center">
                </div>
            </div>
        </div>

        @if (strpos($currentMovie->trailer_url, 'youtube'))
            <div id="modal-trailer" class="modal"
                style="display: block; visibility: hidden; top: 0px; transition: top 0.3s ease 0s;">
                <div>
                    <div>Trailer {{ $currentMovie->name }}</div>
                    <a id="close-modal-trailer" href="javascript:;">
                        <span class="material-icons-round margin-0-5"> close </span>
                    </a>
                </div>
                <div>
                    @php
                        try {
                            parse_str(parse_url($currentMovie->trailer_url, PHP_URL_QUERY), $parse_url);
                            $trailer_id = $parse_url['v'];
                        } catch (\Throwable $th) {
                            $trailer_id = '';
                        }
                    @endphp
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{ $trailer_id }}"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        frameborder="0" scrolling="no" allowfullscreen></iframe>
                </div>
            </div>
        @endif

        <h1 class="heading_movie">{{ $currentMovie->name }}</h1>
        <div class="head ah-frame-bg">
            <div class="first">
                <img src="{{ $currentMovie->getThumbUrl() }}" alt="{{ $currentMovie->name }}" />
            </div>
            <div class="last">
                <div class="name_other">
                    <div>Tên khác</div>
                    <div>{{ $currentMovie->origin_name }} </div>
                </div>
                <div class="list_cate">
                    <div>Thể loại</div>
                    <div>
                        {!! $currentMovie->categories->map(function ($category) {
                                return '<a href="' . $category->getUrl() . '">' . $category->name . '</a>';
                            })->implode('') !!}
                    </div>
                </div>
                <div class="list_cate">
                    <div>Quốc gia</div>
                    <div>
                        {!! $currentMovie->regions->map(function ($region) {
                                return '<a href="' . $region->getUrl() . '">' . $region->name . '</a>';
                            })->implode('') !!}
                    </div>
                </div>
                <div class="status">
                    <div>Trạng thái</div>
                    <div> {{ $currentMovie->episode_current }} {{ $currentMovie->language }} {{ $currentMovie->quality }}
                    </div>
                </div>
                <div class="duration">
                    <div>Thời lượng</div>
                    <div> {{ $currentMovie->episode_time }} </div>
                </div>
                <div class="update_time">
                    <div>Phát hành</div>
                    <div> {{ $currentMovie->publish_year }} </div>
                </div>
            </div>
        </div>
        <div class="flex ah-frame-bg flex-wrap">
            <div class="flex flex-wrap flex-1">
                @if ($watch_url != '')
                    <a href="{{ $watch_url }}"
                        class="padding-5-15 fs-35 button-default fw-500 flex flex-hozi-center bg-lochinvar" title="Xem Ngay">
                        <span class="material-icons-round">play_circle_outline</span> <span class="single-button-text"> XEM PHIM
                        </span>
                    </a>
                @endif
                @if (strpos($currentMovie->trailer_url, 'youtube'))
                    <a href="javascript:void(0)" id="toggle_trailer"
                        class="bg-green padding-5-15 fs-35 button-default fw-500 fs-15 flex flex-hozi-center"
                        title="Theo dõi phim này" style="">
                        <span class="material-icons-round"> play_circle_filled </span> <span
                            class="single-button-text hidden-mobile"> TRAILER </span>
                    </a>
                @endif
            </div>
            <div class="last">
                <div id="rated" class="bg-orange padding-5-15 fs-35 button-default fw-500 fs-15 flex flex-hozi-center">
                    <span class="material-icons-round"> stars </span> <span class="single-button-text hidden-mobile"> CHẤM
                        ĐIỂM </span>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="list_episode ah-frame-bg">
                <div class="heading flex flex-space-auto fw-700">
                    <span>Danh sách tập</span>
                    <span id="newest-ep-is-readed" class="fs-13"></span>
                </div>
                <div class="list-item-episode scroll-bar">
                    @if ($watch_url != '')
                        @foreach ($currentMovie->episodes->sortBy([['server', 'asc']])->groupBy('server') as $server => $data)
                            @foreach ($data->sortByDesc('name', SORT_NATURAL)->groupBy('name') as $name => $item)
                                <a href="{{ $item->sortByDesc('type')->first()->getUrl() }}"
                                    title="{{ $name }}"><span>{{ $name }}</span></a>
                            @endforeach
                        @endforeach
                    @else
                        Phim đang được cập nhật...
                    @endif
                </div>
            </div>
            <div class="desc ah-frame-bg">
                <div>
                    <h2 class="heading"> Nội dung </h2>
                </div>
                <div>

                    @if ($currentMovie->showtimes && $currentMovie->showtimes != '')
                        <p>
                            <strong>
                                <p>
                                    <span style="color:#FFA500">{!! $currentMovie->showtimes !!} <p>
                            </strong>
                        </p>
                    @endif

                    @if ($currentMovie->notify && $currentMovie->notify != '')
                        <p>
                            <strong>
                                <p>
                                    <span style="color:#FFA500">{!! $currentMovie->notify !!} <p>
                            </strong>
                        </p>
                    @endif
                    <p class="Director">
                        <strong>Đạo diễn:</strong>
                        @if (count($currentMovie->directors))
                            {!! $currentMovie->directors->map(function ($director) {
                                    return '<span class="tt-at"><a href="' . $director->getUrl() . '">' . $director->name . '</a></span>';
                                })->implode(',') !!}
                        @else
                            N/A
                        @endif
                    </p>
                    <p class="Cast">
                        <strong>Diễn viên:</strong>
                        @if (count($currentMovie->actors))
                            {!! $currentMovie->actors->map(function ($actor) {
                                    return '<a href="' . $actor->getUrl() . '">' . $actor->name . '</a>';
                                })->implode('<span class="dot-sh">,</span> ') !!}
                        @else
                            N/A
                        @endif
                    </p>
                    <p class="heading"></p>
                    <div>
                        <p>{!! strip_tags($currentMovie->content) !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="ah-frame-bg">
            <div>
                <h2 class="heading"> Tags </h2>
            </div>
            <div class="">
                {!! $currentMovie->tags->map(function ($tag) {
                        return '<a href="' . $tag->getUrl() . '">' . $tag->name . '</a>';
                    })->implode(', ') !!}
            </div>
        </div>
        @include('themes::themeanimeh.inc.comment')
        @include('themes::themeanimeh.inc.movie_related')
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"
        integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/themes/animeh/plugins/jquery-raty/jquery.raty.js"></script>
    <link href="/themes/animeh/plugins/jquery-raty/jquery.raty.css" rel="stylesheet" type="text/css" />

    <script>
        var rated = false;
        $('#movies-rating-star').raty({
            score: {{ $currentMovie->getRatingStar() }},
            number: 10,
            numberMax: 10,
            hints: ['quá tệ', 'tệ', 'không hay', 'không hay lắm', 'bình thường', 'xem được', 'có vẻ hay', 'hay',
                'rất hay', 'siêu phẩm'
            ],
            starOff: '/themes/animeh/plugins/jquery-raty/images/star-off.png',
            starOn: '/themes/animeh/plugins/jquery-raty/images/star-on.png',
            starHalf: '/themes/animeh/plugins/jquery-raty/images/star-half.png',
            click: function(score, evt) {
                if (rated) return
                fetch("{{ route('movie.rating', ['movie' => $currentMovie->slug]) }}", {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')
                            .getAttribute(
                                'content')
                    },
                    body: JSON.stringify({
                        rating: score
                    })
                });
                rated = true;
                $('#movies-rating-star').data('raty').readOnly(true);
                $.toast({
                    heading: 'Thông báo',
                    text: 'Đánh giá của bạn đã được gửi đi!',
                    position: 'bottom-right',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#9EC600',
                    bgColor: '#212121',
                    textColor: 'white'
                })
            }
        });
    </script>

    {!! setting('site_scripts_facebook_sdk') !!}
@endpush
