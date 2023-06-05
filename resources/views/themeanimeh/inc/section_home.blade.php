<div class="margin-10-0 bg-gray-2 flex flex-space-auto">
    <div class="fs-17 fw-700 padding-0-20 color-gray inline-flex height-40 flex-hozi-center bg-black border-l-t">{{$item['label']}}</div>
    @if ($item['link'] != "" && $item['link'] != "#")
        <div class="margin-r-5 fw-500">
            <a href="{{$item['link']}}" class="bg-blue padding-5-10 border-default">Toàn bộ</a>
        </div>
    @endif
</div>
<div class="movies-list ah-frame-bg">
    @foreach ($item['data'] as $movie)
        @include('themes::themeanimeh.inc.section_home_item')
    @endforeach
</div>
