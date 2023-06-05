@extends('themes::themeanimeh.layout')

@push('header')
    <style>
        .watching-movie #video-player {
            height: 580px !important;
        }
        @media only screen and (max-width: 700px) {
            .watching-movie #video-player {
                height: 210px !important;
            }
        }
    </style>
    <script>
        const ROUTE_REPORT_ERROR = '{{ route('episodes.report', ['movie' => $currentMovie->slug, 'episode' => $episode->slug, 'id' => $episode->id]) }}';
    </script>
@endpush

@section('content')
    <div id="modal" class="modal" style="display: block; visibility: hidden; top: 0px; transition: top 0.3s ease 0s;">
        <div>
            <div>{{$currentMovie->getRatingStar()}} sao / {{$currentMovie->getRatingCount()}} lượt đánh giá</div>
            <a id="close-modal-rated" href="javascript:;">
                <span class="material-icons-round margin-0-5"> close </span>
            </a>
        </div>
        <div>
            <div id="movies-rating-star" class="rated-star flex flex-hozi-center flex-ver-center">
            </div>
        </div>
    </div>

    <div class="watching-movie">
        <div class="ah-frame-bg fw-700 margin-10-0 bg-black">
            <a href="{{$currentMovie->getUrl()}}"
                class="fs-16 flex flex-hozi-center color-yellow border-style-1">
                <span class="material-icons-round margin-0-5"> movie </span>{{$currentMovie->name}} </a>
                <div class="flex flex-space-auto">
                    <span>Đang xem Tập {{$episode->name}} </span>
                </div>
        </div>
        <div class="control-bar flex flex-space-between bg-cod-gray">
            <div class="bg-black flex flex-hozi-center fw-500 fs-17 padding-0-10 height-50 border-l-b-t">
                <div class="margin-10-0 bg-gray-2">
                    <div
                        class="fs-17 fw-700 padding-0-20 color-gray inline-flex height-40 flex-hozi-center bg-black border-l-t">
                        Tập {{$episode->name}} </div>
                </div>
            </div>
            <div class="bg-black flex flex-hozi-center fs-17 padding-0-10 height-50 border-r-b-t">
                <a href="{{$currentMovie->getUrl()}}"
                    class="button-default padding-5 bg-brown fs-21" title="Thông tin phim">
                    <span class="material-icons-round"> info </span>
                </a>
                <button id="rated" class="button-default padding-5 bg-orange fs-21 color-white">
                    <span class="material-icons-round"> stars </span>
                </button>
                <button id="report_error" class="button-default padding-5 bg-red fs-21 color-white">
                    <span class="material-icons-round"> report_problem </span>
                </button>
            </div>
        </div>
        <center id="episode_error">
            <input type="text" name="error_message" placeholder="Điền chi tiết lỗi">
            <input type="button" id="error_send" value="Gửi">
        </center>
        <div id="list_sv" class="flex flex-ver-center margin-10">
            @foreach ($currentMovie->episodes->where('slug', $episode->slug)->where('server', $episode->server) as $server)
                <a onclick="chooseStreamingServer(this)" data-type="{{ $server->type }}" data-id="{{ $server->id }}" data-link="{{ $server->link }}" class="streaming-server button-default">
                    <span>Nguồn Phát <span>#{{ $loop->index + 1 }}</span></span>
                </a>
            @endforeach
        </div>
        <div id="video-player"></div>
        @if ($currentMovie->showtimes && $currentMovie->showtimes != '')
            <div class="ah-frame-bg">
                <div class="heading flex flex-hozi-center fw-700 color-red-2">
                    <span class="material-icons-round margin-0-5"> note </span>Lịch chiếu
                </div>
                <div>
                    <strong style="color:#FFA500">{!! $currentMovie->showtimes !!}</strong>
                </div>
            </div>
        @endif
        @if ($currentMovie->notify && $currentMovie->notify != '')
            <div class="ah-frame-bg">
                <div class="heading flex flex-hozi-center fw-700 color-red-2">
                    <span class="material-icons-round margin-0-5"> note </span>Ghi chú
                </div>
                <div>
                    <strong style="color:#FFA500">{!! $currentMovie->notify !!}</strong>
                </div>
            </div>
        @endif
        @foreach ($currentMovie->episodes->sortBy([['server', 'asc']])->groupBy('server') as $server => $data)
            <div class="list_episode ah-frame-bg" id="list-episode">
                <div class="heading flex flex-space-auto fw-700">
                    <span>Danh sách tập <span>{{ $server }}</span></span>
                    <span id="newest-ep-is-readed" class="fs-13"></span>
                </div>
                <div class="list-item-episode scroll-bar">
                    @foreach ($data->sortByDesc('name', SORT_NATURAL)->groupBy('name') as $name => $item)
                        <a href="{{ $item->sortByDesc('type')->first()->getUrl() }}" title="{{ $name }}"><span> {{ $name }} </span></a>
                    @endforeach
                </div>
            </div>
        @endforeach
        @include('themes::themeanimeh.inc.comment')
    </div>
    @include('themes::themeanimeh.inc.movie_related')
@endsection

@push('scripts')
<script src="/themes/animeh/player/js/p2p-media-loader-core.min.js"></script>
    <script src="/themes/animeh/player/js/p2p-media-loader-hlsjs.min.js"></script>

    <script src="/js/jwplayer-8.9.3.js"></script>
    <script src="/js/hls.min.js"></script>
    <script src="/js/jwplayer.hlsjs.min.js"></script>

    <script>
        var episode_id = {{$episode->id}};
        const wrapper = document.getElementById('video-player');
        const vastAds = "{{ Setting::get('jwplayer_advertising_file') }}";

        function chooseStreamingServer(el) {
            const type = el.dataset.type;
            const link = el.dataset.link.replace(/^http:\/\//i, 'https://');
            const id = el.dataset.id;

            const newUrl =
                location.protocol +
                "//" +
                location.host +
                location.pathname.replace(`-${episode_id}`, `-${id}`);

            history.pushState({
                path: newUrl
            }, "", newUrl);
            episode_id = id;

            Array.from(document.getElementsByClassName('streaming-server')).forEach(server => {
                server.classList.remove('bg-green');
            })
            el.classList.add('bg-green');
            renderPlayer(type, link, id);
        }

        function renderPlayer(type, link, id) {
            if (type == 'embed') {
                if (vastAds) {
                    wrapper.innerHTML = `<div id="fake_jwplayer"></div>`;
                    const fake_player = jwplayer("fake_jwplayer");
                    const objSetupFake = {
                        key: "{{ Setting::get('jwplayer_license') }}",
                        aspectratio: "16:9",
                        width: "100%",
                        file: "/themes/animeh/player/1s_blank.mp4",
                        volume: 100,
                        mute: false,
                        autostart: true,
                        advertising: {
                            tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                            client: "vast",
                            vpaidmode: "insecure",
                            skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                            skipmessage: "Bỏ qua sau xx giây",
                            skiptext: "Bỏ qua"
                        }
                    };
                    fake_player.setup(objSetupFake);
                    fake_player.on('complete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adSkipped', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adComplete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                } else {
                    if (wrapper) {
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                    }
                }
                return;
            }

            if (type == 'm3u8' || type == 'mp4') {
                wrapper.innerHTML = `<div id="jwplayer"></div>`;
                const player = jwplayer("jwplayer");
                const objSetup = {
                    key: "{{ Setting::get('jwplayer_license') }}",
                    aspectratio: "16:9",
                    width: "100%",
                    file: link,
                    playbackRateControls: true,
                    playbackRates: [0.25, 0.75, 1, 1.25],
                    sharing: {
                        sites: [
                            "reddit",
                            "facebook",
                            "twitter",
                            "googleplus",
                            "email",
                            "linkedin",
                        ],
                    },
                    volume: 100,
                    mute: false,
                    autostart: true,
                    logo: {
                        file: "{{ Setting::get('jwplayer_logo_file') }}",
                        link: "{{ Setting::get('jwplayer_logo_link') }}",
                        position: "{{ Setting::get('jwplayer_logo_position') }}",
                    },
                    advertising: {
                        tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                        client: "vast",
                        vpaidmode: "insecure",
                        skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                        skipmessage: "Bỏ qua sau xx giây",
                        skiptext: "Bỏ qua"
                    }
                };

                if (type == 'm3u8') {
                    const segments_in_queue = 50;

                    var engine_config = {
                        debug: !1,
                        segments: {
                            forwardSegmentCount: 50,
                        },
                        loader: {
                            cachedSegmentExpiration: 864e5,
                            cachedSegmentsCount: 1e3,
                            requiredSegmentsPriority: segments_in_queue,
                            httpDownloadMaxPriority: 9,
                            httpDownloadProbability: 0.06,
                            httpDownloadProbabilityInterval: 1e3,
                            httpDownloadProbabilitySkipIfNoPeers: !0,
                            p2pDownloadMaxPriority: 50,
                            httpFailedSegmentTimeout: 500,
                            simultaneousP2PDownloads: 20,
                            simultaneousHttpDownloads: 2,
                            // httpDownloadInitialTimeout: 12e4,
                            // httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpDownloadInitialTimeout: 0,
                            httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpUseRanges: !0,
                            maxBufferLength: 300,
                            // useP2P: false,
                        },
                    };
                    if (Hls.isSupported() && p2pml.hlsjs.Engine.isSupported()) {
                        var engine = new p2pml.hlsjs.Engine(engine_config);
                        player.setup(objSetup);
                        jwplayer_hls_provider.attach();
                        p2pml.hlsjs.initJwPlayer(player, {
                            liveSyncDurationCount: segments_in_queue, // To have at least 7 segments in queue
                            maxBufferLength: 300,
                            loader: engine.createLoaderClass(),
                        });
                    } else {
                        player.setup(objSetup);
                    }
                } else {
                    player.setup(objSetup);
                }

                const resumeData = 'OPCMS-PlayerPosition-' + id;

                player.on('ready', function() {
                    if (typeof(Storage) !== 'undefined') {
                        if (localStorage[resumeData] == '' || localStorage[resumeData] == 'undefined') {
                            console.log("No cookie for position found");
                            var currentPosition = 0;
                        } else {
                            if (localStorage[resumeData] == "null") {
                                localStorage[resumeData] = 0;
                            } else {
                                var currentPosition = localStorage[resumeData];
                            }
                            console.log("Position cookie found: " + localStorage[resumeData]);
                        }
                        player.once('play', function() {
                            console.log('Checking position cookie!');
                            console.log(Math.abs(player.getDuration() - currentPosition));
                            if (currentPosition > 180 && Math.abs(player.getDuration() - currentPosition) >
                                5) {
                                player.seek(currentPosition);
                            }
                        });
                        window.onunload = function() {
                            localStorage[resumeData] = player.getPosition();
                        }
                    } else {
                        console.log('Your browser is too old!');
                    }
                });

                player.on('complete', function() {
                    if (typeof(Storage) !== 'undefined') {
                        localStorage.removeItem(resumeData);
                    } else {
                        console.log('Your browser is too old!');
                    }
                })

                function formatSeconds(seconds) {
                    var date = new Date(1970, 0, 1);
                    date.setSeconds(seconds);
                    return date.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                }
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const episode = '{{$episode->id}}';
            let playing = document.querySelector(`[data-id="${episode}"]`);
            if (playing) {
                playing.click();
                return;
            }

            const servers = document.getElementsByClassName('streaming-server');
            if (servers[0]) {
                servers[0].click();
            }
        });
    </script>


    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
