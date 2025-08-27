<div>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('add_travel_details') }} </h2>
        <a href="{{ route('delegations.show', $delegation->id) }}" id="add-attachment-btn"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 border border-red-400 bg-red-100 text-red-700 rounded">
            <h4 class="font-semibold mb-2">{{ __db('please_fix_the_following_errors') }}</h4>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $columns = [
            ['label' => __db('delegation_id'), 'render' => fn($row) => e($row->code ?? '')],
            ['label' => __db('invitation_from'), 'render' => fn($row) => e($row->invitationFrom->value ?? '')],
            ['label' => __db('continent'), 'render' => fn($row) => e($row->continent->value ?? '')],
            ['label' => __db('country'), 'render' => fn($row) => e($row->country->value ?? '')],
            ['label' => __db('invitation_status'), 'render' => fn($row) => e($row->invitationStatus->value ?? '')],
            [
                'label' => __db('participation_status'),
                'render' => fn($row) => e($row->participationStatus->value ?? ''),
            ],
        ];

        $data = [$delegation];
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
        <div class="xl:col-span-12">
            <div class="bg-white h-full w-full rounded-lg border-0 p-10">
                <x-reusable-table :columns="$columns" :data="$data" />
            </div>
        </div>
    </div>



    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('delegates') }}
    </h2>

    <form method="POST" action="{{ getRouteForPage('delegation.storeTravel', $delegation->id) }}"
        enctype="multipart/form-data">
        @csrf

        @php

            $columns = [
                [
                    'label' => '',
                    'render' => function ($row) {
                        return '<input type="checkbox" name="delegate_ids[]" value="' .
                            e($row->id) .
                            '" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded" />';
                    },
                ],
                [
                    'label' => __db('sl_no'),
                    'render' => fn($row) => e($row->code ?? ''),
                ],
                [
                    'label' => __db('title'),
                    'render' => fn($row) => e($row->title->value ?? ''),
                ],
                [
                    'label' => __db('name'),
                    'render' => function ($row) {
                        $badge = $row->team_head
                            ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                            : '';
                        return $badge . '<div class="block">' . e($row->value_en ?? '-') . '</div>';
                    },
                ],
                [
                    'label' => __db('designation'),
                    'render' => fn($row) => e($row->designation_en ?? ''),
                ],
                [
                    'label' => __db('internal_ranking'),
                    'render' => fn($row) => e($row->internalRanking->value ?? ''),
                ],
                [
                    'label' => __db('gender'),
                    'render' => fn($row) => e($row->gender->value ?? ''),
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <x-reusable-table :columns="$columns" :data="$delegates" />
                </div>
            </div>
        </div>

        @if ($showArrival)
            <hr class="mx-6 border-neutral-200 h-10">
            @include('shared-pages.delegations.delegate.partials.transport_section', [
                'type' => 'arrival',
                'title' => __db('arrival'),
                'transport' => null,
            ])
        @endif


        @if ($showDeparture)
            <hr class="mx-6 border-neutral-200 h-5">
            @include('shared-pages.delegations.delegate.partials.transport_section', [
                'type' => 'departure',
                'title' => __db('departure'),
                'transport' => null,
            ])
        @endif

        <div class="flex justify-start gap-5 items-center mt-6">
            <button type="submit" name="submit_add_transport" id="submit_add_transport" value="1"
                class="btn text-md !bg-[#B68A35] text-white rounded-lg py-3 px-5">{{ __db('submit_and_add_new_flight_details') }}</button>

            @if ($showArrival)
                <button type="submit" name="submit_add_departure" id="submit_add_departure" value="1"
                    class="btn text-md border !border-[#B68A35] !text-[#B68A35] rounded-lg py-3 px-5">{{ __db('submit_add_departure') }}</button>
            @endif

            @if ($showDeparture)
                <button type="submit" name="submit_add_arrival" id="submit_add_arrival" value="1"
                    class="btn text-md border !border-[#B68A35] !text-[#B68A35] rounded-lg py-3 px-5">{{ __db('submit_add_arrival') }}</button>
            @endif

            @if (!$showArrival && !$showDeparture)
                <button type="submit" name="submit_exit" id="submit_exit" value="1"
                    class="btn text-md border !border-[#B68A35] !text-[#B68A35] rounded-lg py-3 px-5">{{ __db('submit_and_exit') }}</button>
            @endif

            <button type="submit" name="submit_add_interview" id="submit_add_interview" value="1"
                class="btn text-md !bg-[#D7BC6D] text-white rounded-lg py-3 px-5">{{ __db('submit_add_interview') }}</button>
        </div>


    </form>
</div>


@section('script')
    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            const checked = e.target.checked;
            document.querySelectorAll('.delegate-checkbox').forEach(cb => cb.checked = checked);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function toggleFields(transportType) {
                const mode = document.querySelector(`input[name="${transportType}[mode]"]:checked`).value;
                const flightFields = document.querySelector(
                    `[data-transport-section='${transportType}'] [data-mode-fields='flight']`);

                if (mode === 'flight') {
                    flightFields.style.display = 'grid';
                } else {
                    flightFields.style.display = 'none';
                }
            }

            ['arrival', 'departure'].forEach(transportType => {
                const transportSection = document.querySelector(
                    `[data-transport-section='${transportType}']`);
                if (transportSection) {
                    toggleFields(transportType);
                    document.querySelectorAll(`input[name="${transportType}[mode]"]`).forEach(radio => {
                        radio.addEventListener('change', () => toggleFields(transportType));
                    });
                }
            });
        });
    </script>
@endsection
