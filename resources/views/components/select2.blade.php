{{-- resources/views/components/select2.blade.php --}}
@props(['placeholder' => 'Select an option'])

<select
    {{ $attributes->merge(['class' => 'form-select select2']) }}
    data-placeholder="{{ $placeholder }}"
>
    <option value=""></option>
    {{ $slot }}
</select>

