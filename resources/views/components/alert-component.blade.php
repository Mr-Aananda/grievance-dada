@props(['type' => 'success', 'messages' => 'Operation successful'])

<!-- Alert -->
{{-- <div role="alert" {{ $attributes->merge(['class' => 'alert alert-dismissible fade show p-2 text-start alert-' . $type]) }}>--}}

@if (is_array($messages))
    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
        <strong>{{ $type == 'success' ? 'Messages' : 'Alert' }}: </strong>
{{--        <small class="d-block">{{ str_replace('_', ' ', $messages[0]). ' and' }} {{ (count($messages) - 1). ' other errors' }}</small>--}}
        @foreach($messages as $message)
            <small class="d-block">{{ str_replace('_', ' ', $message) }}</small>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@else
    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
        <strong>{{ $type == 'success' ? 'Messages' : 'Alert' }}: </strong>
        <small class="d-block">{{ str_replace('_', ' ', $messages) }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button> --}}
{{-- </div> --}}
<!-- End Alert -->
