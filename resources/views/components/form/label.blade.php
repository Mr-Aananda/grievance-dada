@props([
    'name' => '',
    'required' => false,
    'for' => '',
])
<label class="form-label {{ $required ? 'required' : '' }}" for="{{ $for }}">{{ $name }}</label>
