@props([
'route' => '',
'name' => '',
'checkActive' => []
])

@if(count($checkActive) > 0)
    <a href="{{ route($route) }}" id="{{ in_array(Route::currentRouteName(), $checkActive) ? 'is-menu-active' : '' }}" class="nav-link">
        <span>{{ $name }}</span>
    </a>
@else
    <a href="{{ route($route) }}" id="{{ Route::currentRouteName() == $route ? 'is-menu-active' : '' }}" class="nav-link">
        <span>{{ $name }}</span>
    </a>
@endif
