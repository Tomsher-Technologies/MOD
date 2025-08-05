@extends('layouts.admin_account', ['title' => __db('all_delegations')])

@section('content')
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

    <form method="POST" action="{{ route('delegations.storeTravel', $delegation->id) }}" enctype="multipart/form-data">
        @csrf

        @php
            $delegates = $delegation->delegates->filter(fn($d) => !$d->transport);

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





        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('arrival') }}</h2>
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">

            <div class="flex items-center gap-4 mb-5">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="arrival[mode]" value="flight" class="form-radio text-blue-600" checked />
                    <span class="text-[15px] text-gray-700">{{ __db('flight') }}</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="arrival[mode]" value="land" class="form-radio text-green-600" />
                    <span class="text-[15px] text-gray-700">{{ __db('land') }}</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="arrival[mode]" value="sea" class="form-radio text-purple-600" />
                    <span class="text-[15px] text-gray-700">{{ __db('sea') }}</span>
                </label>
            </div>

            <div class="grid grid-cols-5 gap-5 w-full">
                <div id="arrival-flight-fields" class="col-span-3 grid grid-cols-3 gap-5">
                    <div>
                        <label
                            class="form-label block mb-1 text-gray-700 font-medium">{{ __db('arrival_airport') }}:</label>
                        <select name="arrival[airport_id]" class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                            <option selected disabled>{{ __db('select_to_airport') }}</option>
                            @foreach (getDropdown('airports')->options as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_no') }}:</label>
                        <input name="arrival[flight_no]" type="text"
                            class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" placeholder="Enter Flight No" />
                    </div>
                    <div>
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_name') }}:</label>
                        <input name="arrival[flight_name]" type="text"
                            class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                            placeholder="Enter Flight Name" />
                    </div>
                </div>
                <div class="col-span-2 grid grid-cols-2 gap-5">
                    <div>
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('date_time') }}:</label>
                        <input name="arrival[date_time]" type="datetime-local"
                            class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" />
                    </div>
                    <div>
                        <label
                            class="form-label block mb-1 text-gray-700 font-medium">{{ __db('arrival_status') }}:</label>
                        <select name="arrival[status_id]" class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                            <option selected disabled>{{ __db('select_status') }}</option>
                            @foreach (getDropdown('arrival_status')->options as $status)
                                <option value="{{ $status->id }}">{{ $status->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-span-5 mt-4" id="land-sea-arrival">
                <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('comment') }}:</label>
                <textarea name="arrival[comment]" rows="4" class="block p-2.5 w-full text-sm rounded-lg border !border-[#d1d5db]"
                    placeholder="Type here..."></textarea>
            </div>
        </div>


        <hr class="mx-6 border-neutral-200 h-5">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('departure') }}</h2>
        <div class="bg-white rounded-lg p-6 mb-5 mt-4">
            <div class="flex items-center gap-4 mb-5">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="departure[mode]" value="flight" class="form-radio text-blue-600" checked>
                    <span class="text-[15px] text-gray-700">{{ __db('flight') }}</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="departure[mode]" value="land" class="form-radio text-green-600">
                    <span class="text-[15px] text-gray-700">{{ __db('land') }}</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="departure[mode]" value="sea" class="form-radio text-purple-600">
                    <span class="text-[15px] text-gray-700">{{ __db('sea') }}</span>
                </label>
            </div>

            <div class="grid grid-cols-5 gap-5 w-full">
                <div id="departure-flight-fields" class="col-span-3 grid grid-cols-3 gap-5">
                    <div>
                        <label
                            class="form-label block mb-1 text-gray-700 font-medium">{{ __db('departure_airport') }}:</label>
                        <select name="departure[airport_id]"
                            class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                            <option selected disabled>{{ __db('select_from_airport') }}</option>
                            @foreach (getDropdown('airports')->options as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_no') }}:</label>
                        <input name="departure[flight_no]" type="text"
                            class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                            placeholder="Enter Flight No" />
                    </div>
                    <div>
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_name') }}:</label>
                        <input name="departure[flight_name]" type="text"
                            class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                            placeholder="Enter Flight Name" />
                    </div>
                </div>

                <div class="col-span-2 grid grid-cols-2 gap-5">

                    <div>
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('date_time') }}:</label>
                        <input name="departure[date_time]" type="datetime-local"
                            class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" />
                    </div>

                    <div>
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('departure_status') }}:</label>
                            <select name="departure[status_id]"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                                <option selected disabled>{{ __db('select_status') }}</option>
                                @foreach (getDropdown('departure_status')->options as $status)
                                    <option value="{{ $status->id }}">{{ $status->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-5 mt-4" id="land-sea-departure">
                <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('comment') }}:</label>
                <textarea name="departure[comment]" rows="4"
                    class="block p-2.5 w-full text-sm rounded-lg border !border-[#d1d5db]" placeholder="Type here..."></textarea>
            </div>



        </div>


        <div class="flex justify-start gap-5 items-center">
            <button type="submit" id="submit_add_transport"
                class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">{{ __db('submit_and_add_new_flight_details') }}</button>

            <button type="submit" id="submit_exit"
                class="btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg py-[1px] h-12">{{ __db('submit_and_exit') }}</button>

            <button type="submit" id="submit_add_interview"
                class="btn text-md mb-[-10px] !bg-[#D7BC6D] text-white rounded-lg py-[1px] h-12">{{ __db('submit_add_interview') }}</button>
        </div>


    </form>
@endsection

@section('script')
    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            const checked = e.target.checked;
            document.querySelectorAll('.delegate-checkbox').forEach(cb => cb.checked = checked);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function toggleArrivalFields() {
                const mode = document.querySelector('input[name="arrival[mode]"]:checked').value;
                const flightFields = document.getElementById('arrival-flight-fields');

                if (mode === 'flight') {
                    flightFields.style.display = 'grid';
                } else {
                    flightFields.style.display = 'none';
                }
            }

            function toggleDepartureFields() {
                const mode = document.querySelector('input[name="departure[mode]"]:checked').value;
                const flightFields = document.getElementById('departure-flight-fields');

                if (mode === 'flight') {
                    flightFields.style.display = 'grid';
                } else {
                    flightFields.style.display = 'none';
                }
            }

            toggleArrivalFields();
            toggleDepartureFields();

            document.querySelectorAll('input[name="arrival[mode]"]').forEach(radio => {
                radio.addEventListener('change', toggleArrivalFields);
            });

            document.querySelectorAll('input[name="departure[mode]"]').forEach(radio => {
                radio.addEventListener('change', toggleDepartureFields);
            });
        });
    </script>
@endsection
