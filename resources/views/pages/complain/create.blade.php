@section('title', $type === 'complain' ? 'Create Complain' : 'Create Manual')

<x-app-layout>
    <!-- Header Widget -->
    <div class="widget mb-3 ">
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
            <div class="widget p-2">
                <div class="widget-header border-bottom mb-3 pb-2">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-plus-circle me-2 text-success"></i>
                        {{ $type === 'complain' ? 'Create New Complain' : 'Create New Manual' }}
                    </h5>
                </div>

                <div class="widget-body">
                    <div id="vueRoot">
                        <complain
                            :complain-types='@json($complainTypes)'
                            :categories='@json($categories)'
                            :buyers='@json($buyers)'
                            :statuses='@json($statuses)'
                            :type='@json($type)'
                            submit-url="{{ route('complain.store') }}"
                            cancel-url="{{ route('complain.index') }}"
                            :is-edit="false"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
