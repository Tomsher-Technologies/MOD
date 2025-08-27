<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-10">
        <h2 class="font-semibold text-2xl">{{ __db('delegation') }}</h2>
        <div class="flex gap-3 ms-auto">
            <a href="{{ getRouteForPage('delegation.edit', $delegation->id) }}" data-modal-hide="default-modal"
                class="btn text-sm ms-auto !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-5">
                {{ __db('edit') }}
            </a>
            <x-back-btn class="" back-url="{{ getRouteForPage('delegations.index') }}" />
        </div>
    </div>

    @php
        $columns = [
            ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? '-'],
            ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom->value ?? '-'],
            ['label' => __db('continent'), 'render' => fn($row) => $row->continent->value ?? '-'],
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

                @php
                    $note1_columns = [['label' => __db('note_1'), 'render' => fn($row) => $row->note1 ?? '-']];
                    $note2_columns = [['label' => __db('note_2'), 'render' => fn($row) => $row->note2 ?? '-']];
                    $data = [$delegation];
                    $noDataMessage = __db('no_data_found');
                @endphp

                <div class="grid grid-cols-2 gap-6 mt-3">
                    <x-reusable-table :columns="$note1_columns" :data="$data" :noDataMessage="$noDataMessage" />
                    <x-reusable-table :columns="$note2_columns" :data="$data" :noDataMessage="$noDataMessage" />
                </div>
            </div>
        </div>
    </div>


    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('delegates') }} ({{ $delegation->delegates->count() }})</h2>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

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
                            'render' => function ($row) {
                                return $row->participation_status ?? '-';
                            },
                        ],
                        [
                            'label' => __db('accommodation'),
                            'render' => fn($row) => property_exists($row, 'accommodation')
                                ? $row->accommodation ?? '-'
                                : '-',
                        ],
                        [
                            'label' => __db('arrival_status'),
                            'render' => function ($row) {
                                $id = $row->id ?? uniqid();
                                return '<svg class="cursor-pointer" width="36" height="30" data-modal-target="delegate-transport-modal-' .
                                    $id .
                                    '" data-modal-toggle="delegate-transport-modal-' .
                                    $id .
                                    '" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><rect width="480" height="32" x="16" y="464" fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect><path fill="var(--ci-primary-color, #B68A35)" d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z" class="ci-primary"></path></g></svg>';
                            },
                        ],
                    ];
                    $data = $delegation->delegates;
                    $noDataMessage = __db('no_delegates_found');
                @endphp

                <x-reusable-table :columns="$columns" :data="$data" :noDataMessage="$noDataMessage" />
            </div>
        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('escorts') }} ({{ $delegation->escorts->count() }})</h2>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-lg">{{ __db('escorts') }}</h4>
                    <a href={{ getRouteForPage('escorts.index') }}
                        class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('escort') }}</a>
                </div>
                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key + 1,
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => fn($escort) => e($escort->military_number),
                        ],
                        [
                            'label' => __db('title'),
                            'key' => 'title',
                            'render' => fn($escort) => e($escort->title),
                        ],
                        [
                            'label' => __db('name_en'),
                            'key' => 'name',
                            'render' => fn($escort) => e($escort->name_en),
                        ],
                        [
                            'label' => __db('mobile_number'),
                            'key' => 'mobile_number',
                            'render' => fn($escort) => '<span dir="ltr">' . e($escort->phone_number) . '</span>',
                        ],
                        [
                            'label' => __db('gender'),
                            'key' => 'gender',
                            'render' => fn($escort) => e(optional($escort->gender)->value),
                        ],
                        [
                            'label' => __db('spoken_languages'),
                            'key' => 'known_languages',
                            'render' => function ($escort) {
                                $ids = $escort->spoken_languages ? explode(',', $escort->spoken_languages) : [];
                                $names = \App\Models\DropdownOption::whereIn('id', $ids)->pluck('value')->toArray();
                                return e(implode(', ', $names));
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$delegation->escorts" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>

        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('drivers') }} ({{ $delegation->drivers->count() }})</h2>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-lg">{{ __db('drivers') }}</h4>
                    <a href={{ getRouteForPage('drivers.index') }}
                        class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('drivers') }}</a>
                </div>
                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key + 1,
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => fn($driver) => e($driver->military_number),
                        ],
                        [
                            'label' => __db('title'),
                            'key' => 'title',
                            'render' => fn($driver) => e($driver->title),
                        ],
                        [
                            'label' => __db('name_en'),
                            'key' => 'name_en',
                            'render' => fn($driver) => e($driver->name_en),
                        ],
                        [
                            'label' => __db('mobile_number'),
                            'key' => 'mobile_number',
                            'render' => fn($driver) => '<span dir="ltr">' . e($driver->mobile_number) . '</span>',
                        ],
                        [
                            'label' => __db('driver') . ' ' . __db('id'),
                            'key' => 'driver_id',
                            'render' => fn($driver) => e($driver->driver_id),
                        ],
                        [
                            'label' => __db('car') . ' ' . __db('type'),
                            'key' => 'car_type',
                            'render' => fn($driver) => e($driver->car_type),
                        ],
                        [
                            'label' => __db('car') . ' ' . __db('number'),
                            'key' => 'car_number',
                            'render' => fn($driver) => e($driver->car_number),
                        ],
                        [
                            'label' => __db('capacity'),
                            'key' => 'capacity',
                            'render' => fn($driver) => e($driver->capacity),
                        ],
                        //             [
                        //                 'label' => __db('status'),
                        //                 'key' => 'status',
                        //                 'render' => function ($driver) {
                        //                     return '<div class="flex items-center">
    //     <label for="switch-driver' .
                        //                         $driver->id .
                        //                         '" class="relative inline-block w-11 h-6">
    //         <input type="checkbox" id="switch-driver' .
                        //                         $driver->id .
                        //                         '" onchange="update_driver_status(this)" value="' .
                        //                         $driver->id .
                        //                         '" class="sr-only peer" ' .
                        //                         ($driver->status == 1 ? 'checked' : '') .
                        //                         ' />
    //         <div class="block bg-gray-300 peer-checked:bg-[#009448] w-11 h-6 rounded-full transition"></div>
    //         <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
    //     </label>
    // </div>';
                        //                 },
                        //             ],
                    ];

                    $rowClass = function ($driver) {
                        return $driver->delegations->where('pivot.status', 1)->count() > 0 ? '' : 'bg-[#f2eccf]';
                    };
                @endphp


                <x-reusable-table :data="$delegation->drivers" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>

        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('interviews') }}
    </h2>

    @php
        $columns = [
            [
                'label' => __db('sl_no'),
                'render' => fn($row, $key) => $key + 1,
            ],
            [
                'label' => 'Date & Time',
                'render' => fn($row) => $row->date_time
                    ? Carbon\Carbon::parse($row->date_time)->format('Y-m-d h:i A')
                    : '-',
            ],
            [
                'label' => 'Attended By',
                'render' => function ($row) {
                    $attendees = $row->interviewMembers->where('type', 'from');
                    $names = $attendees
                        ->map(
                            fn($im) => e(
                                $im->resolveMemberForInterview($row)->name_en ??
                                    ($im->resolveMemberForInterview($row)->name_ar ?? '-'),
                            ),
                        )
                        ->filter()
                        ->implode('<br>');
                    return $names ?: '-';
                },
            ],
            [
                'label' => __db('interview_with'),
                'render' => function ($row) {
                    if (!empty($row->other_member_id) && $row->otherMember) {
                        $otherMemberName = $row->otherMember->name ?? '';
                        $otherMemberId = $row->otherMember->id ?? $row->other_member_id;
                        if ($otherMemberId) {
                            $with =
                                '<a href="' .
                                route('other-interview-members.show', [
                                    'other_interview_member' => base64_encode($otherMemberId),
                                ]) .
                                '" class="!text-[#B68A35]">
                                    <span class="block">Other Member ID: ' .
                                e($otherMemberId) .
                                '</span>
                                </a>';
                        }
                    } else {
                        $with =
                            '<a href="' .
                            route('delegations.show', $row->interviewWithDelegation->id ?? '') .
                            '" class="!text-[#B68A35]">' .
                            'Delegation ID : ' .
                            e($row->interviewWithDelegation->code ?? '') .
                            '</a>';
                    }

                    $names = $row->interviewMembers
                        ->map(fn($member) => '<span class="block">' . e($member->name ?? '') . '</span>')
                        ->implode('');

                    return $with . $names;
                },
            ],
            ['label' => 'Status', 'render' => fn($row) => e(ucfirst($row->status->value))],
        ];
        $data = $delegation->interviews ?? collect();
        $noDataMessage = __db('no_data_found');
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
                'label' => __db('sl_no'),
                'render' => fn($row, $key) => $key + 1,
            ],
            [
                'label' => __db('title'),
                'render' => fn($row) => $row->title->value ?? '-',
            ],
            // [
            //     'label' => __db('file_name'),
            //     'render' => fn($row) => $row->file_name ?? '-',
            // ],
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
        <div id="delegate-transport-modal-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow ">
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">{{ __db('transport_information_for') }}
                            {{ $delegate->name_en ?? '-' }}</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            data-modal-hide="delegate-transport-modal-{{ $delegate->id }}">
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
                            @if ($arrival)
                                <div class="border-b py-4">
                                    <p class="font-medium text-gray-600">{{ __db('to_airport') }}</p>
                                    <p class="text-base">{{ $arrival->airport->value ?? '-' }}</p>
                                </div>
                                <div class="border-b py-4">
                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                    <p class="text-base">{{ $arrival->flight_no ?? '-' }}</p>
                                </div>
                                <div class="py-4 border-b md:border-b-0">
                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                    <p class="text-base">{{ $arrival->flight_name ?? '-' }}</p>
                                </div>
                                <div class="py-4 !pb-0">
                                    <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                    <p class="text-base">{{ $arrival->date_time ?? '-' }}</p>
                                </div>
                            @else
                                <p class="col-span-2 text-gray-500">{{ __db('no_arrival_information') }}.</p>
                            @endif
                        </div>

                        <h3 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('departure') }}</h3>
                        @php
                            $departure = $delegate->delegateTransports->where('type', 'departure')->first();
                        @endphp
                        <div class="border rounded-lg p-6 grid grid-cols-2 gap-x-8">
                            @if ($departure)
                                <div class="border-b py-4">
                                    <p class="font-medium text-gray-600">{{ __db('from_airport') }}</p>
                                    <p class="text-base">{{ $departure->airport->value ?? '-' }}</p>
                                </div>
                                <div class="border-b py-4">
                                    <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                    <p class="text-base">{{ $departure->flight_no ?? '-' }}</p>
                                </div>
                                <div class="py-4 border-b md:border-b-0">
                                    <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                    <p class="text-base">{{ $departure->flight_name ?? '-' }}</p>
                                </div>
                                <div class="py-4 !pb-0">
                                    <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                    <p class="text-base">{{ $departure->date_time ?? '-' }}</p>
                                </div>
                            @else
                                <p class="col-span-2 text-gray-500">{{ __db('no_departure_information') }}.</p>
                            @endif
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



        function update_escort_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ getRouteForPage('escorts.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data.status == 'success') {
                    toastr.success("{{ __db('status_updated') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        }


        function update_driver_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ getRouteForPage('drivers.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data.status == 'success') {
                    toastr.success("{{ __db('status_updated') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        }
    </script>
@endsection
