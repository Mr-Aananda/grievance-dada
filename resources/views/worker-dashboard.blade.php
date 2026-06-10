@section('title', 'GMS — Grievance Management System')

<x-guest-layout>

<div id="vueRoot">
    <grievance
        :categories='@json($categories)'
        :departments='@json($departments)'
        store-url="{{ route('grievance.store') }}"
        list-url="{{ route('grievance.index') }}"
        show-url="/grievance/{id}"
        :initial-counts='@json($statusCounts)'
        :initial-ticket='@json(session("new_ticket"))'
    />
</div>

</x-guest-layout>
