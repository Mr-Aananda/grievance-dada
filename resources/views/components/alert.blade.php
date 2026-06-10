{{-- show error or success message --}}
@if (session()->has('success') || $errors->any())
    <div class="bottom-right-alert">
    @if (session()->has('success'))
            <x-alert-component :messages="session()->get('success')" />
        @else
        @if ($errors->any())
{{--                {{ dd($errors->all()) }}--}}
                <x-alert-component type="danger" :messages="$errors->all()" />
            @endif
        @endif
    </div>
@endif
