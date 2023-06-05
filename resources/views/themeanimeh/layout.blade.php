@extends('themes::layout')

@php
    $menu = \Ophim\Core\Models\Menu::getTree();
@endphp

@push('header')
    <link href="{{ asset('/themes/animeh/css/css.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('/themes/animeh/js/functions.js') }}"></script>
@endpush

@section('body')
    <div id="ah_wrapper">
        @include('themes::themeanimeh.inc.navbar')
        @if (get_theme_option('ads_header'))
            <div id="top-banner">
                {!! get_theme_option('ads_header') !!}
            </div>
        @endif
        <div class="ah_content">
            @yield('content')
        </div>
        {!! get_theme_option('footer') !!}
    </div>
@endsection

@push('scripts')
@endpush

@section('footer')
    @if (get_theme_option('ads_catfish'))
        <div id="catfish-banner">
            {!! get_theme_option('ads_catfish') !!}
        </div>
    @endif
    {!! setting('site_scripts_google_analytics') !!}
@endsection
