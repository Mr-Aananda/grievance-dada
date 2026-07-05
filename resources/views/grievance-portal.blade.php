@section('title', 'GMS — Grievance Management System')

<x-portal-layout>
    <div class="py-0">
        <grievance
            v-model:active-tab="activeTab"
            v-model:total-records="totalRecords"
            :categories='@json($categories)'
            :departments='@json($departments)'
            store-url="{{ route('grievance.store') }}"
            list-url="{{ route('grievance.index') }}"
            show-url="/grievance/{id}"
            logo-url="{{ Vite::asset('resources/assets/images/logo/dada_bg.png') }}"
            :initial-counts='@json($statusCounts)'
            :initial-ticket='@json(session("new_ticket"))'
        />
    </div>
</x-portal-layout>
