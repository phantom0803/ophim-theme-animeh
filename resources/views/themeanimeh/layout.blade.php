@extends('themes::layout')

@php
    $menu = \Ophim\Core\Models\Menu::getTree();
@endphp

@push('header')
@endpush

@section('body')

@endsection

@push('scripts')
@endpush

@section('footer')

@endsection
