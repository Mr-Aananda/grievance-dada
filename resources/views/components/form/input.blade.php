@props([
    'type' => 'text',
    'name' => '',
    'old_text' => '',
])
@if($type == 'textarea')
    <textarea
        {{ $attributes }}
        name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror"
    >{{ old($name) ?? $old_text }}</textarea>
@else
    <input
        {{ $attributes }}
        type="{{ $type }}"
        value="{{ old($name) }}"
        name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror"
    >
    @error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
@endif
