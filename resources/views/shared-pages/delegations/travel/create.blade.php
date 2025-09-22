<div>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('add_travel_details') }} </h2>
        <a href="{{ route('delegations.show', $delegation->id) }}" id="add-attachment-btn"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
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
            [
                'label' => __db('country'),
                'key' => 'country',
                'render' => function ($row) {
                    if (!$row->country) {
                        return '-';
                    }

                    $flag = $row->country->flag
                        ? '<img src="' .
                            getUploadedImage($row->country->flag) .
                            '" 
                                        alt="' .
                            e($row->country->name) .
                            ' flag" 
                                        class="inline-block w-6 h-4 mr-2 rounded-sm object-cover" />'
                        : '';

                    return $flag . ' ' . e($row->country->name);
                },
            ],
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

    <form method="POST" action="{{ route('delegations.storeTravel', $delegation->id) }}" enctype="multipart/form-data">
        @csrf

        @php
            $existingArrivals = [];
            $existingDepartures = [];

            foreach ($delegates as $delegate) {
                $arrival = $delegate->delegateTransports->firstWhere('type', 'arrival');
                if ($arrival) {
                    $existingArrivals[$delegate->id] = $arrival;
                }

                $departure = $delegate->delegateTransports->firstWhere('type', 'departure');
                if ($departure) {
                    $existingDepartures[$delegate->id] = $departure;
                }
            }

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
                    'render' => function ($row, $key) {
                        return $key;
                    },
                ],

                [
                    'label' => __db('title'),
                    'render' => fn($row) => e($row->getTranslation('title') ?? ''),
                ],
                [
                    'label' => __db('name'),
                    'render' => function ($row) {
                        $badge = $row->team_head
                            ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                            : '';
                        return $badge . '<div class="block">' . e($row->getTranslation('name') ?? '-') . '</div>';
                    },
                ],
                [
                    'label' => __db('designation'),
                    'render' => fn($row) => e($row->getTranslation('designation') ?? ''),
                ],
                [
                    'label' => __db('internal_ranking'),
                    'render' => fn($row) => e($row->internalRanking->value ?? ''),
                ],
                [
                    'label' => __db('gender'),
                    'render' => fn($row) => e($row->gender->value ?? ''),
                ],
                [
                    'label' => __db('flight_details'),
                    'render' => function ($row) use (
                        $existingArrivals,
                        $existingDepartures,
                        $showDeparture,
                        $showArrival,
                    ) {
                        $details = [];

                        if (isset($existingArrivals[$row->id]) && $showArrival) {
                            $arrival = $existingArrivals[$row->id];
                            $flightInfo = [];
                            if ($arrival->flight_name) {
                                $flightInfo[] = e($arrival->flight_name);
                            }
                            if ($arrival->flight_no) {
                                $flightInfo[] = e($arrival->flight_no);
                            }
                            if ($arrival->airport) {
                                $flightInfo[] = e($arrival->airport->value);
                            }
                            $details[] = '<strong>' . __db('arrival') . ':</strong> ' . implode(', ', $flightInfo);
                        }

                        if (isset($existingDepartures[$row->id]) && $showDeparture) {
                            $departure = $existingDepartures[$row->id];
                            $flightInfo = [];
                            if ($departure->flight_name) {
                                $flightInfo[] = e($departure->flight_name);
                            }
                            if ($departure->flight_no) {
                                $flightInfo[] = e($departure->flight_no);
                            }
                            if ($departure->airport) {
                                $flightInfo[] = e($departure->airport->value);
                            }
                            $details[] = '<strong>' . __db('departure') . ':</strong> ' . implode(', ', $flightInfo);
                        }

                        if (empty($details)) {
                            return '<span class="text-gray-800">' . __db('no_flight_details') . '</span>';
                        }

                        return implode('<br>', $details);
                    },
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                    <x-reusable-table :columns="$columns" table-id="delegatesTable" :data="$delegates" :rowClass="function ($row) use (
                        $existingArrivals,
                        $existingDepartures,
                        $showArrival,
                        $showDeparture,
                    ) {
                        $hasTravel =
                            (isset($existingArrivals[$row->id]) && $showArrival) ||
                            (isset($existingDepartures[$row->id]) && $showDeparture);
                        return $hasTravel ? 'bg-green-100 border-green-300' : 'bg-yellow-100 border-yellow-300';
                    }" />


                    <div class="mt-4 flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-100 border border-green-300 mr-2"></div>
                            <span>{{ __db('delegates_with_flight_details') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 mr-2"></div>
                            <span>{{ __db('delegates_without_flight_details') }}</span>
                        </div>
                    </div>
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

            <button type="submit" name="submit_exit" id="submit_exit" value="1"
                class="btn text-md border !border-[#B68A35] !text-[#B68A35] rounded-lg py-3 px-5">{{ __db('submit_and_exit') }}</button>

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
