@props([
    'name' => '',
    'options' => [],
    'option_name' => 'name',
    'selected_option' => '',
])

<select class="form-select @error($name) is-invalid @enderror" name="{{ $name }}">
    <option selected disabled value>- Choose One -</option>
    @foreach($options as $option)
        <option value="{{ $option->id }}" {{ (old($name) == $option->id || $selected_option == $option->id) ? 'selected' : '' }}>{{ $option[$option_name] }}</option>
    @endforeach
</select>
@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror
