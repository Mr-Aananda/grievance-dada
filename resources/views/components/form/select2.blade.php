@props([
    'options' => [],
    'name' => '',
    'option_name' => 'name',
    'selected_option' => '',
])
<select
    {{ $attributes }}
    class="js-example-basic-single col-sm-12"
    name="{{ $name }}"
>
    <option>Choose One</option>
    @foreach($options as $option)
        <option value="{{ $option->id }}" {{ (old($name) == $option->id || $selected_option == $option->id) ? 'selected' : '' }}>{{ $option[$option_name] }}</option>
    @endforeach
</select>

@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror
