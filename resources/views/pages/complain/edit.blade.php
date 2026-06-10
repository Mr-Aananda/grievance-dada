@section('title', $complain->type === 'complain' ? 'Edit Complain' : 'Edit Manual')

<x-app-layout>
    <!-- Header Widget -->
    <div class="widget mb-3">
        <div class="widget-body d-flex align-items-center">
            @include('pages.complain.menu')

            <div class="ms-auto">
                <a href="{{ route('complain.index') }}" class="btn icon lg rounded" title="Go back">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Body Widget -->
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="widget">
                <div class="widget-header border-bottom mb-3 pb-2">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                        {{ $complain->type === 'complain' ? 'Edit Complain' : 'Edit Manual' }}
                    </h5>
                </div>

                <div class="widget-body">
                    <div id="vueRoot">
                        <complain :complain-types='@json($complainTypes)'
                            :buyers='@json($buyers)'
                            :categories='@json($categories)' :statuses='@json($statuses)'
                            :type='@json($complain->type)' :existing-files='@json($existingFiles)'
                            :existing-videos='@json($existingVideos)'
                            :complain-data='@json($complain)'
                            submit-url="{{ route('complain.update', $complain->id) }}"
                            cancel-url="{{ route('complain.index') }}" :is-edit="true" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
