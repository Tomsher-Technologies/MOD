<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-10">
        <h2 class="font-semibold text-2xl">{{ __db('delegation') }}</h2>
        <div class="flex gap-3 ms-auto">
            <a href="{{ route('delegations.edit', $delegation) }}" data-modal-hide="default-modal"
                class="btn text-sm ms-auto !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-5">
                {{ __db('edit') }}
            </a>
            <x-back-btn class="" back-url="{{ route('delegations.index') }}" />
        </div>
    </div>

    @php
        $columns = [
            ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? '-'],
            ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom->value ?? '-'],
            ['label' => __db('continent'), 'render' => fn($row) => $row->continent->value ?? '-'],
            ['label' => __db('country'), 'render' => fn($row) => $row->country->value ?? '-'],
            ['label' => __db('invitation_status'), 'render' => fn($row) => $row->invitationStatus->value ?? '-'],
            ['label' => __db('participation_status'), 'render' => fn($row) => $row->participationStatus->value ?? '-'],
        ];

        $data = [$delegation];
        $noDataMessage = __db('no_data_found');
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
        <div class="xl:col-span-12">
            <div class="bg-white h-full w-full rounded-lg border-0 p-10">
                <x-reusable-table :columns="$columns" :data="$data" :noDataMessage="$noDataMessage" />

                <hr class="my-5">

                <div class="grid grid-cols-2 gap-6 mt-3">
                    <table class="table-auto mb-0 border-collapse border border-gray-300 w-full">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="p-3  !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    {{ __db('note_1') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-sm align-[middle]">
                                <td class="px-4 border border-gray-200 py-3">{{ $delegation->note1 ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-auto mb-0 border-collapse border border-gray-300 w-full">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="p-3  !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                    {{ __db('note_2') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-sm align-[middle]">
                                <td class="px-4 border border-gray-200 py-3">{{ $delegation->note2 ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegates') }}</h2>
    @php
        $columns = [
            [
                'label' => __db('sl_no'),
                'render' => fn($row, $key) => $key + 1,
            ],
            [
                'label' => __db('title'),
                'render' => fn($row) => $row->title->value ?? '-',
            ],
            [
                'label' => __db('name'),
                'render' => function ($row) {
                    $badge = $row->team_head
                        ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                        : '';
                    $name = $row->name_en ?? ($row->name_ar ?? '-');
                    return $badge . '<div class="block">' . e($name) . '</div>';
                },
            ],
            [
                'label' => __db('designation'),
                'render' => fn($row) => $row->designation_en ?? ($row->designation_ar ?? '-'),
            ],
            [
                'label' => __db('internal_ranking'),
                'render' => fn($row) => $row->internalRanking->value ?? '-',
            ],
            [
                'label' => __db('gender'),
                'render' => fn($row) => $row->gender->value ?? '-',
            ],
            [
                'label' => __db('parent_id'),
                'render' => fn($row) => $row->parent->name_en ?? ($row->parent->name_ar ?? '-'),
            ],
            [
                'label' => __db('relationship'),
                'render' => fn($row) => $row->relationship->value ?? '-',
            ],
            [
                'label' => __db('badge_printed'),
                'render' => fn($row) => $row->badge_printed ? 'Yes' : 'No',
            ],
            [
                'label' => __db('participation_status'),
                'render' => fn($row) => $row->delegation->participationStatus->value ?? '-',
            ],
            [
                'label' => __db('accommodation'),
                'render' => fn($row) => property_exists($row, 'accommodation') ? $row->accommodation ?? '-' : '-',
            ],
            [
                'label' => __db('arrival_status'),
                'render' => function ($row) {
                    $id = $row->id ?? uniqid();
                    return '<svg class=" cursor-pointer" width="36" height="30" data-modal-target="default-modal3-' .
                        $id .
                        '" data-modal-toggle="default-modal3-' .
                        $id .
                        '" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><rect width="480" height="32" x="16" y="464" fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect><path fill="var(--ci-primary-color, #B68A35)" d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z" class="ci-primary"></path></g></svg>';
                },
            ],
        ];
        $data = $delegation->delegates;
        $noDataMessage = __db('no_delegates_found');
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <x-reusable-table :columns="$columns" :data="$data" :noDataMessage="$noDataMessage" />
            </div>
        </div>
    </div>


    {{--
        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">Escorts</h2>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Military Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Title</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Name</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Mobile Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Gender</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Known Languages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">UM123</td>
                                <td class="px-4 py-3">Captain</td>
                                <td class="px-4 py-3">Amar Preet Singh</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 50 123 4567</td>
                                <td class="px-4 py-3">Male</td>
                                <td class="px-4 py-3">Arabic, English</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">DX456</td>
                                <td class="px-4 py-3">HH</td>
                                <td class="px-4 py-3">Laila Al Kaabi</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 55 234 7890</td>
                                <td class="px-4 py-3">Female</td>
                                <td class="px-4 py-3">Arabic</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">AB789</td>
                                <td class="px-4 py-3">Major</td>
                                <td class="px-4 py-3">Yousef Al Ali</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 52 345 6789</td>
                                <td class="px-4 py-3">Male</td>
                                <td class="px-4 py-3">Arabic, English, French</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">SH321</td>
                                <td class="px-4 py-3">Ms</td>
                                <td class="px-4 py-3">Sara Mansour</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 56 987 6543</td>
                                <td class="px-4 py-3">Female</td>
                                <td class="px-4 py-3">English</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}


    {{-- <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px] ">Drivers
        </h2>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Military Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Title</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Name</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Mobile Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Vehicle Type</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Plate Number</th>
                                <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-1024</td>
                                <td class="px-4 py-3">Captain</td>
                                <td class="px-4 py-3">Saeed Al Kaabi</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 55 789 3210</td>
                                <td class="px-4 py-3">SUV</td>
                                <td class="px-4 py-3">DXB 4567</td>
                                <td class="px-4 py-3">5</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-2548</td>
                                <td class="px-4 py-3">Mr</td>
                                <td class="px-4 py-3">Mohammed Al Obaidi</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 50 112 3344</td>
                                <td class="px-4 py-3">Sedan</td>
                                <td class="px-4 py-3">AUH 2345</td>
                                <td class="px-4 py-3">4</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-8789</td>
                                <td class="px-4 py-3">Ms</td>
                                <td class="px-4 py-3">Fatima Al Zahra</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 52 223 4455</td>
                                <td class="px-4 py-3">Hatchback</td>
                                <td class="px-4 py-3">SHJ 9876</td>
                                <td class="px-4 py-3">5</td>
                            </tr>
                            <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                                <td class="px-4 py-3">MIL-0024</td>
                                <td class="px-4 py-3">Captain</td>
                                <td class="px-4 py-3">John Doe</td>
                                <td class="px-4 py-3 text-end" dir="ltr">+971 58 667 8899</td>
                                <td class="px-4 py-3">Crossover</td>
                                <td class="px-4 py-3">RAK 1234</td>
                                <td class="px-4 py-3">4</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('interviews') }}
    </h2>

    @php
        $columns = [
            [
                'label' => 'Date & Time',
                'render' => fn($row) => $row->date_time
                    ? Carbon\Carbon::parse($row->date_time)->format('Y-m-d h:i A')
                    : '-',
            ],
            [
                'label' => 'Attended By',
                'render' => function ($row) {
                    $attendees = $row->interviewMembers->filter(fn($im) => $im->type === 'from');
                    $names = $attendees
                        ->map(function ($im) use ($row) {
                            $member = $im->resolveMemberForInterview($row);
                            return $member ? e($member->name_en ?? ($member->name_ar ?? '-')) : '';
                        })
                        ->filter()
                        ->all();

                    return implode('<br>', $names) ?: '-';
                },
            ],
            [
                'label' => 'Interview With',
                'render' => function ($row) {
                    $interviewees = $row->interviewMembers->filter(fn($im) => $im->type === 'to');
                    $names = $interviewees
                        ->map(function ($im) use ($row) {
                            $member = $im->resolveMemberForInterview($row);
                            return $member ? e($member->name_en ?? ($member->name_ar ?? '-')) : '';
                        })
                        ->filter()
                        ->all();

                    $delegationLink = $row->interviewWithDelegation
                        ? '<a href="#" class="!text-[#B68A35]" data-modal-target="DelegationModal" data-modal-toggle="DelegationModal"> Delegation ID : ' .
                            e($row->interviewWithDelegation->code) .
                            '</a><br>'
                        : '';

                    return $delegationLink . implode('<br>', $names);
                },
            ],
            [
                'label' => 'Status',
                'render' => fn($row) => e(ucfirst($row->status->value)),
            ],
        ];

        $data = $delegation->interviews ?? collect();
        $noDataMessage = 'No interviews found.';
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <x-reusable-table :columns="$columns" :data="$data" :noDataMessage="$noDataMessage" />
            </div>
        </div>
    </div>


    <h4 class="text-lg font-semibold mb-3 mt-6">{{ __db('attachments') }}</h4>

    @php
        $columns = [
            [
                'label' => __db('title'),
                'render' => fn($row) => $row->title->value ?? '-',
            ],
            [
                'label' => __db('file_name'),
                'render' => fn($row) => $row->file_name ?? '-',
            ],
            [
                'label' => __db('uploaded_file'),
                'render' => function ($row) {
                    $fileUrl = $row->file_path ? asset('storage/' . $row->file_path) : '#';
                    $fileName = e($row->file_name);
                    return '<a href="' .
                        $fileUrl .
                        '" target="_blank" class="font-medium !text-[#B68A35]">' .
                        $fileName .
                        '</a>';
                },
            ],
            [
                'label' => __db('uploaded_date'),
                'render' => fn($row) => $row->created_at ? $row->created_at->format('d-m-Y') : '-',
            ],
            [
                'label' => __db('document_date'),
                'render' => fn($row) => $row->document_date ?? '-',
            ],
        ];

        $data = $delegation->attachments;
        $noDataMessage = __db('no_attachments_found');
    @endphp

    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <x-reusable-table :columns="$columns" :data="$data" :noDataMessage="$noDataMessage" />
    </div>




    @foreach ($delegation->delegates as $delegate)
        <div id="default-modal3-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __db('transport_information_for') }}
                            {{ $delegate->name_en ?? '-' }}</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            data-modal-hide="default-modal3-{{ $delegate->id }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-6">
                        <h3 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('arrival') }}</h3>

                        @php
                            $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();
                        @endphp

                        <div class="border rounded-lg p-6 grid grid-cols-2 gap-x-8">
                            @if ($arrival && $arrival->airport && $arrival->airport->value)
                                <div class="border-b py-4">
                                    <p class="font-medium text-gray-600">{{ __db('to_airport') }}</p>
                                    <p class="text-base">
                                        {{ $delegate->delegateTransports->where('type', 'arrival')->first()?->airport->value ?? '-' }}
                                    </p>
                                </div>
                                <div class="border-b py-4">
                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                    <p class="text-base">
                                        {{ $delegate->delegateTransports->where('type', 'arrival')->first()?->flight_no ?? '-' }}
                                    </p>
                                </div>
                                <div class="py-4 !pb-0">
                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                    <p class="text-base">
                                        {{ $delegate->delegateTransports->where('type', 'arrival')->first()?->flight_name ?? '-' }}
                                    </p>
                                </div>
                            @endif
                            <div class="py-4 !pb-0">
                                <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                <p class="text-base">
                                    {{ $arrival->date_time ?? '-' }}
                                </p>
                            </div>
                        </div>


                        <h3 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('departure') }}</h3>
                        @php
                            $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                        @endphp

                        <div class="border rounded-lg p-6 grid grid-cols-2 gap-x-8">
                            @if ($departure && $departure->airport && $departure->airport->value)
                                <div class="border-b py-4 !pt-0">
                                    <p class="font-medium text-gray-600">{{ __db('from_airport') }}</p>
                                    <p class="text-base">
                                        {{ $delegate->delegateTransports->where('type', 'departure')->first()?->airport->value ?? '-' }}
                                    </p>
                                </div>
                                <div class="border-b py-4">
                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                    <p class="text-base">
                                        {{ $delegate->delegateTransports->where('type', 'departure')->first()?->flight_no ?? '-' }}
                                    </p>
                                </div>
                                <div class="py-4 !pb-0">
                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                    <p class="text-base">
                                        {{ $delegate->delegateTransports->where('type', 'departure')->first()?->flight_name ?? '-' }}
                                    </p>
                                </div>
                            @endif
                            <div class="py-4 !pb-0">
                                <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                <p class="text-base">
                                    {{ $departure->date_time ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
</div>

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-delegate-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('delete_delegate_confirm_msg') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes_delete') }}",
                        cancelButtonText: "{{ __db('cancel') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
