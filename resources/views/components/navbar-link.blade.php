{{-- @props([
'route' => '',
'name' => '',
'url' => '',
'icon' => ''
])

@php
    $path = parse_url(url()->current(), PHP_URL_PATH);
    $segments = explode('/', $path);
    $desiredSegment = $segments[1];
@endphp

<a href="{{ route($route) }}"  class="dropdown-item {{ $desiredSegment == $url ? 'active' : '' }}">
    <i class="{{ $icon }}"></i>
    <span>{{ $name }}</span>
</a> --}}


@props([
    'route' => '',
    'name' => '',
    'url' => '',
    'icon' => ''
])

<a href="{{ route($route) }}" class="dropdown-item {{ request()->routeIs($route) ? 'active' : '' }}">
    <i class="{{ $icon }}"></i>
    <span>{{ $name }}</span>
</a>
