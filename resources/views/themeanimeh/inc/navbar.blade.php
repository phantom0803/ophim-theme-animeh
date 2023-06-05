@php
    $logo = setting('site_logo', '');
    $brand = setting('site_brand', '');
    $title = isset($title) ? $title : setting('site_homepage_title', '');
@endphp

<div id="navbar">
    <div class="flex flex-hozi-center padding-10">
        <div class="logo">
            <a href="/" title="{{ $title }}" rel="home">
                @if ($logo)
                    {!! $logo !!}
                @else
                    {!! $brand !!}
                @endif
            </a>
        </div>
        <div id="drop-down-4" class="search-bar flex flex-1 margin-0-10 flex-ver-center">
            <form class="flex" id="form-search" action="/" method="GET">
                <input type="text" placeholder="Nhập từ khoá..." value="{{ request('search') }}" class="padding-10 bg-black color-gray"
                    name="search">
                <button type="submit" class="flex flex-hozi-center bg-black color-gray">
                    <span class="material-icons-round"> search </span>
                </button>
            </form>
        </div>
        <div class="nav-items flex-wrap flex">
            <a href="#" onclick="clickEventDropDown(this,'search')" class="toggle-search toggle-dropdown"
                bind="drop-down-4">
                <span class="material-icons-round"> search </span>
            </a>
            <a href="#" onclick="clickEventDropDown(this,'reorder')" class="toggle-dropdown" bind="drop-down-1">
                <span class="material-icons-round"> reorder </span>
            </a>
        </div>
    </div>
    <div id="drop-down-1" class="dropdown-menu bg-black w-100-percent flex-column">
        <div class="tab-links flex-1">
            @foreach ($menu as $item)
                @if (count($item['children']))
                    <a href="#" class="item-tab-link parent-menu" bind="tab-{{ $item['id'] }}"> <span
                            class="material-icons-round margin-0-5"> menu </span>{{ $item['name'] }} </a>
                @else
                    <a href="{{ $item['link'] }}" class="item-tab-link"> <span class="material-icons-round margin-0-5">
                            auto_awesome </span>{{ $item['name'] }} </a>
                @endif
            @endforeach
        </div>
        <div class="tab-content">
            @foreach ($menu as $item)
                @if (count($item['children']))
                    <div id="tab-{{$item['id']}}" class="item-tab-content sub-menu-content">
                        <div class="flex flex-wrap">
                            @foreach ($item['children'] as $children)
                                <a href="{{$children['link']}}" title="{{$children['name']}}">{{$children['name']}}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

</div>
