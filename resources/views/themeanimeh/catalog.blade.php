@extends('themes::themeanimeh.layout')

@php
    $years = Cache::remember('all_years', \Backpack\Settings\app\Models\Setting::get('site_cache_ttl', 5 * 60), function () {
        return \Ophim\Core\Models\Movie::select('publish_year')
            ->distinct()
            ->pluck('publish_year')
            ->sortDesc();
    });
@endphp

@section('content')
    <div class="margin-10-0 bg-gray-2">
        <div class="fs-17 fw-700 padding-0-20 color-gray inline-flex height-40 flex-hozi-center bg-black border-l-t">
            {{ $section_name }} </div>
    </div>
    @include('themes::themeanimeh.inc.catalog_filter')
    <div class="movies-list">
        @if(!count($data))
            <p>Không có dữ liệu cho mục này!</p>
        @endif
        @foreach ($data as $movie)
            @include('themes::themeanimeh.inc.section_home_item')
        @endforeach
    </div>
    {{ $data->appends(request()->all())->links('themes::themeanimeh.inc.pagination') }}
@endsection
