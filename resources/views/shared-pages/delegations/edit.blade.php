<div x-data="{ isAttachmentEditModalOpen: false }">
    <x-back-btn title="" back-url="{{ getRouteForPage('delegations.index') }}" />

    <div class="bg-white h-full w-full rounded-lg border-0 p-6">

        <form action="{{ getRouteForPage('delegation.update', $delegation->id) }}" method="POST" class="mb-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-3">
                    <label class="form-label">{{ __db('delegate_id') }}:</label>
                    <input type="text" name="code"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0 bg-gray-200"
                        value="{{ old('code', $delegation->code ?? '') }}" readonly disabled>
                    @error('code')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('invitation_from') }}:</label>
                    <select name="invitation_from_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __db('select_invitation_from') }}</option>
                        @foreach (getDropDown('internal_ranking')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('invitation_from_id', $delegation->invitation_from_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('invitation_from_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('continent') }}:</label>
                    <select name="continent_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Continent') }}</option>
                        @foreach (getDropDown('continents')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('continent_id', $delegation->continent_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('continent_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('country') }}:</label>
                    <select name="country_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Country') }}</option>
                        @foreach (getDropDown('country')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('country_id', $delegation->country_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('country_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('invitation_status') }}:</label>
                    <select name="invitation_status_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Invitation Status') }}</option>
                        @foreach (getDropDown('invitation_status')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('invitation_status_id', $delegation->invitation_status_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('invitation_status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('participation_status') }}:</label>
                    <select name="participation_status_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Participation Status') }}</option>
                        @foreach (getDropDown('participation_status')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('participation_status_id', $delegation->participation_status_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('participation_status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12">
                    <div class="flex w-full justify-between gap-5">
                        <div class="w-full">
                            <label class="form-label">{{ __db('note1') }}:</label>
                            <textarea name="note1" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500"
                                placeholder="Enter Note 1">{{ old('note1', $delegation->note1) }}</textarea>
                            @error('note1')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label class="form-label">{{ __db('note2') }}:</label>
                            <textarea name="note2" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500"
                                placeholder="Enter Note 2">{{ old('note2', $delegation->note2) }}</textarea>
                            @error('note2')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="col-span-12 mt-6">
                    <button type="submit"
                        class="btn !bg-[#B68A35] text-white rounded-lg py-3 px-6 font-semibold hover:shadow-lg transition">
                        {{ __db('update_delegation') }}
                    </button>
                </div>
            </div>
        </form>

        <span class="border-t border-neutral-200 pt-8 mt-8 w-full">
            <h4 class="text-lg font-semibold">{{ __db('attachments') }}</h4>
        </span>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0">
                    @php
                        $columns = [
                            [
                                'label' => __db('sl_no'),
                                'render' => fn($row, $key) => $key + 1,
                            ],
                            [
                                'label' => __db('title'),
                                'render' => fn($row) => e($row->title?->value ?? 'N/A'),
                            ],
                            [
                                'label' => __db('document_date'),
                                'render' => fn($row) => optional($row->document_date)
                                    ? \Carbon\Carbon::parse($row->document_date)->format('d-m-Y')
                                    : '',
                            ],
                            [
                                'label' => __db('uploaded_file'),
                                'render' => function ($row) {
                                    $fileName = e($row->file_name);
                                    $fileUrl = $row->file_path ? asset('storage/' . $row->file_path) : '#';
                                    return "<a href=\"$fileUrl\" class=\"font-semibold !text-[#b68a35] hover:underline text-xs\" target=\"_blank\">$fileName</a>";
                                },
                            ],
                            [
                                'label' => __db('actions'),
                                'render' => function ($row) {
                                    $attachmentData = [
                                        'id' => $row->id,
                                        'title_id' => $row->title_id,
                                        'document_date' => $row->document_date,
                                        'file_name' => $row->file_name,
                                        'file_path' => $row->file_path,
                                    ];

                                    $attachmentJson = htmlspecialchars(
                                        json_encode($attachmentData),
                                        ENT_QUOTES,
                                        'UTF-8',
                                    );

                                    return '<div class="flex items-center gap-5">' .
                                        '<form action="' .
                                        getRouteForPage('attachments.destroy', $row->id) .
                                        '" method="POST" class="delete-attachment-form">' .
                                        csrf_field() .
                                        method_field('DELETE') .
                                        '<button type="submit" title="Delete" class="delete-attachment-btn text-red-600 hover:text-red-800">
        <svg class="w-5.5 h-5.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
        </svg>
    </button>
                        </form>
                            <button
            type="button"
            title="Edit"
            data-attachment=\'' .
                                        $attachmentJson .
                                        '\'
            class="edit-attachment-btn text-[#B68A35] hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="#B68A35">
                <path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/>
            </svg>
        </button>
                    </div>';
                                },
                            ],
                        ];
                    @endphp

                    <x-reusable-table :columns="$columns" :data="$delegation->attachments" noDataMessage="No attachments found." />

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ getRouteForPage('delegations.updateAttachment', $delegation->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php
                            $attachmentTitleDropdown = getDropDown('attachment_title');
                            $options = $attachmentTitleDropdown ? $attachmentTitleDropdown->options : [];

                        @endphp

                        <div x-data="attachmentsComponent()">
                            <template x-for="(attachment, index) in attachments" :key="attachment.id ?? index">
                                <div class="grid grid-cols-12 gap-5 mb-4 items-center border p-4 rounded">

                                    <input type="hidden" :name="`attachments[${index}][id]`" x-model="attachment.id" />
                                    <input type="hidden" :name="`attachments[${index}][deleted]`"
                                        x-model="attachment.deleted" />

                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('title') }}</label>
                                        <select :name="`attachments[${index}][title_id]`"
                                            class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-3"
                                            x-model.number="attachment.title_id" :disabled="attachment.deleted">
                                            <option value="">{{ __db('select_title') }}</option>
                                            @foreach ($options as $option)
                                                <option value="{{ $option->id }}">{{ $option->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('file') }}</label>
                                        <input
                                            class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-3"
                                            type="file" :name="`attachments[${index}][file]`"
                                            @change="e => attachment.file = e.target.files[0]">
                                    </div>

                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('document_date') }}</label>
                                        <input type="date" :name="`attachments[${index}][document_date]`"
                                            class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-3"
                                            x-model="attachment.document_date" :disabled="attachment.deleted" />
                                    </div>
                                    <div class="col-span-3">
                                        <button type="button"
                                            class="bg-red-600 text-white p-3 h-[46px] rounded hover:bg-red-700"
                                            @click="toggleDelete(index)"
                                            x-text="attachment.deleted ? 'Undo' : '{{ __db('delete') }}'"></button>
                                    </div>
                                </div>

                                <span class="text-red-600"
                                    x-text="window.attachmentsFieldErrors?.[`attachments.${index}.file`]?.[0] ?? ''"></span>
                            </template>

                            <button type="button" class="btn !bg-[#B68A35] text-white rounded-lg px-4 py-2 mt-3"
                                @click="addAttachment()">
                                + {{ __db('add_attachments') }}
                            </button>

                            <div class="mt-6">
                                <button type="submit" class="btn !bg-[#B68A35] text-white rounded-lg px-6 py-2"
                                    x-show="attachments.length > 0">
                                    {{ __db('save_attachments') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('delegates') }} ({{ $delegation->delegates->count() }})
        </h2>
        <div class="flex items-center gap-3">
            <a href="{{ getRouteForPage('delegation.addDelegate', $delegation->id) }}" id="add-attachment-btn"
                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-3 px-5">
                <span>{{ __db('add_delegate') }}</span>
            </a>
            <a href="{{ getRouteForPage('delegations.addTravel', ['id' => $delegation->id, 'showArrival' => 1]) }}"
                id="add-attachment-btn"
                class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">
                <span>{{ __db('add_group_arrival') }}</span>
            </a>
            <a href="{{ route('delegations.addTravel', ['id' => $delegation->id, 'showDeparture' => 1]) }}"
                id="add-attachment-btn"
                class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">
                <span>{{ __db('add_group_departure') }}</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                @php
                    $delegates = $delegation->delegates ?? collect();

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
                                $teamHeadBadge = $row->team_head
                                    ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span>'
                                    : '';
                                $name = $row->name_en ?: $row->name_ar ?: 'Unnamed';
                                return $teamHeadBadge . '<div class="block">' . e($name) . '</div>';
                            },
                        ],
                        [
                            'label' => __db('designation'),
                            'render' => fn($row) => $row->designation_en ?: $row->designation_ar ?: '-',
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
                            'render' => fn($row) => $row->parent
                                ? ($row->parent->name_en ?:
                                $row->parent->name_ar ?:
                                '-')
                                : '-',
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
                        // [
                        //     'label' => 'Accommodation',
                        //     'render' => function ($row) {
                        //         if ($row->accommodation_id) {
                        //             $accommodation = \App\Models\Accommodation::find($row->accommodation_id);
                        //             return $accommodation
                        //                 ? e(
                        //                     $accommodation->name .
                        //                         ' - ' .
                        //                         $accommodation->room_type .
                        //                         ' - ' .
                        //                         $accommodation->room_number,
                        //                 )
                        //                 : '-';
                        //         }
                        //         return '-';
                        //     },
                        // ],
                        [
                            'label' => __db('arrival_status'),
                            'render' => function ($row) {
                                $transport = $row->delegateTransports->first();
                                return $transport ? e($transport->arrival_status ?? '-') : '-';
                            },
                        ],
                        [
                            'label' => __db('action'),
                            'render' => function ($row) use ($delegation) {
                                $editUrl = getRouteForPage('delegation.editDelegate', [
                                    'delegation' => $delegation->id,
                                    'delegate' => $row->id,
                                ]);

                                $deleteForm =
                                    '
                        <form action="' .
                                    getRouteForPage('delegation.destroyDelegate', [$delegation, $row]) .
                                    '" method="POST" class="delete-delegate-form">
                            ' .
                                    csrf_field() .
                                    '
                            ' .
                                    method_field('DELETE') .
                                    '
                            <button type="submit" class="delete-delegate-btn text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                </svg>
                            </button>
                        </form>';

                                $editButton =
                                    '
                        <a href="' .
                                    $editUrl .
                                    '" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                <path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#B68A35"></path>
                            </svg>
                        </a>';

                                return '<div class="flex items-center gap-5">' . $deleteForm . $editButton . '</div>';
                            },
                        ],
                    ];

                    $noDataMessage = __db('no_data_found');
                @endphp

                <x-reusable-table :data="$delegation->delegates" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>
        </div>
    </div>

    {{-- <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">Escorts</h2>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Sl.No</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Military
                                Number</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Title</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Name</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Mobile
                                Number</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Gender
                            </th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Known
                                Languages</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">1</td>
                            <td class="px-4 py-3 border border-gray-200">UM123</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">Amar Preet Singh</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 50 123 4567
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Male</td>
                            <td class="px-4 py-3 border border-gray-200">Arabic, English</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">2</td>
                            <td class="px-4 py-3 border border-gray-200">DX456</td>
                            <td class="px-4 py-3 border border-gray-200">HH</td>
                            <td class="px-4 py-3 border border-gray-200">Laila Al Kaabi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 55 234 7890
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Female</td>
                            <td class="px-4 py-3 border border-gray-200">Arabic</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">3</td>
                            <td class="px-4 py-3 border border-gray-200">AB789</td>
                            <td class="px-4 py-3 border border-gray-200">Major</td>
                            <td class="px-4 py-3 border border-gray-200">Yousef Al Ali</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 52 345 6789
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Male</td>
                            <td class="px-4 py-3 border border-gray-200">Arabic, English, French</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">4</td>
                            <td class="px-4 py-3 border border-gray-200">SH321</td>
                            <td class="px-4 py-3 border border-gray-200">Ms</td>
                            <td class="px-4 py-3 border border-gray-200">Sara Mansour</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 56 987 6543
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Female</td>
                            <td class="px-4 py-3 border border-gray-200">English</td>

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
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Sl.No</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Military
                                Number</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Title</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Name</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Mobile
                                Number</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Vehicle
                                Type</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Plate
                                Number</th>
                            <th scope="col"
                                class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Capacity
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">1</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-1024</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">Saeed Al Kaabi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 55 789 3210
                            </td>
                            <td class="px-4 py-3 border border-gray-200">SUV</td>
                            <td class="px-4 py-3 border border-gray-200">DXB 4567</td>
                            <td class="px-4 py-3 border border-gray-200">5</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">2</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-2548</td>
                            <td class="px-4 py-3 border border-gray-200">Mr</td>
                            <td class="px-4 py-3 border border-gray-200">Mohammed Al Obaidi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 50 112 3344
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Sedan</td>
                            <td class="px-4 py-3 border border-gray-200">AUH 2345</td>
                            <td class="px-4 py-3 border border-gray-200">4</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">3</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-8789</td>
                            <td class="px-4 py-3 border border-gray-200">Ms</td>
                            <td class="px-4 py-3 border border-gray-200">Fatima Al Zahra</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 52 223 4455
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Hatchback</td>
                            <td class="px-4 py-3 border border-gray-200">SHJ 9876</td>
                            <td class="px-4 py-3 border border-gray-200">5</td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">4</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-0024</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">John Doe</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 58 667 8899
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Crossover</td>
                            <td class="px-4 py-3 border border-gray-200">RAK 1234</td>
                            <td class="px-4 py-3 border border-gray-200">4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}


    @php
        $delegatesCollection = collect($delegation->delegates)->mapWithKeys(function ($delegate) {
            return [(int) $delegate->id => $delegate];
        });

        $columns = [
            ['label' => __db('sl_no'), 'render' => fn($row, $key) => $key + 1],
            [
                'label' => __db('date_time'),
                'render' => fn($row) => $row->date_time
                    ? \Carbon\Carbon::parse($row->date_time)->format('Y-m-d h:i A')
                    : '',
            ],
            [
                'label' => __db('attended_by'),
                'render' => function ($row) use ($delegatesCollection) {
                    if ($row->interviewMembers && count($row->interviewMembers)) {
                        $attendedBy = collect($row->interviewMembers)->filter(fn($m) => $m->type === 'from');
                        if ($attendedBy->isEmpty()) {
                            return '-';
                        }

                        return $attendedBy
                            ->map(function ($member) use ($delegatesCollection) {
                                $memberId = (int) $member->member_id;
                                $delegate = $delegatesCollection->get($memberId);
                                $delegateName = $delegate
                                    ? ($delegate->name_en ?:
                                    $delegate->name_ar ?:
                                    'N/A')
                                    : 'Unknown';
                                return '<span class="block">Member ID: ' .
                                    e($memberId) .
                                    ' - Delegate Name: ' .
                                    e($delegateName) .
                                    '</span>';
                            })
                            ->implode('');
                    }
                    return '-';
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
            ['label' => __db('status'), 'render' => fn($row) => e($row->status->title ?? ($row->status->value ?? 'Unknown'))],
            [
                'label' => __db('action'),
                'render' => function ($row) {
                    $deleteBtn =
                        '<a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal" class="mr-2">' .
                        '<svg class="w-5.5 h-5.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">' .
                        '<path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/></svg></a>';

                    $editBtn =
                        '<a href="' .
                        getRouteForPage('delegations.addInterview', $row->id) .
                        '">' .
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">' .
                        '<path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#B68A35"></path></svg></a>';

                    return '<div class="flex items-center gap-5">' . $deleteBtn . $editBtn . '</div>';
                },
            ],
        ];

    @endphp

    <hr class="mx-6 border-neutral-200 h-10">

    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('interviews') }}</h2>
        <a href="{{ getRouteForPage('delegation.addInterview', $delegation->id) }}" id="add-attachment-btn"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-3 px-5">
            <span>{{ __db('add_interview') }}</span>
        </a>
    </div>

    {{-- <pre>{{ dd($delegation) }}</pre> --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <x-reusable-table :data="$delegation->interviews" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>
        </div>
    </div>

    <div x-data="{
        isAttachmentEditModalOpen: false,
        attachment: {},
        delegationId: {{ $delegation->id }},
        fileUrl: '',
        open(data) {
            this.attachment = data;
            this.fileUrl = data.file_path ? `/storage/${data.file_path}` : '#';
            this.isAttachmentEditModalOpen = true;
        },
        close() {
            this.isAttachmentEditModalOpen = false;
        },
        handleFileChange(e) {
            this.attachment.file = e.target.files[0];
        }
    }" x-on:open-edit-attachment.window="open($event.detail)">
        <div x-show="isAttachmentEditModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90" id="edit-attachment-modal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full flex justify-center items-center bg-gray-900/50"
            style="display: none;">
            <div class="relative w-full max-w-lg max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button @click="isAttachmentEditModalOpen = false" type="button"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <form :action="`{{ getRouteForPage('attachments.edit', $delegation->id) }}`" method="POST"
                        enctype="multipart/form-data" class="p-6 space-y-6">
                        @csrf
                        @method('POST')

                        <input type="hidden" name="attachments[0][id]" :value="attachment.id">

                        <div>
                            <label class="form-label block mb-1">{{ __db('title') }}</label>
                            <select name="attachments[0][title_id]" x-model="attachment.title_id"
                                class="w-full p-2 border rounded">
                                <option value="">{{ __db('select_title') }}</option>
                                @foreach ($attachmentTitleDropdown->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label block mb-1">{{ __db('document_date') }}</label>
                            <input type="date" name="attachments[0][document_date]"
                                x-model="attachment.document_date" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <label class="form-label block mb-1">{{ __db('replace_file') }}</label>
                            <input type="file" name="attachments[0][file]" @change="handleFileChange"
                                class="w-full p-2 border rounded" />
                            <template x-if="attachment.file_name">
                                <p class="mt-2 text-sm text-gray-600">
                                    {{ __db('current_file') }}:
                                    <a :href="fileUrl" target="_blank" class="text-blue-600 underline"
                                        x-text="attachment.file_name"></a>
                                </p>
                            </template>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="isAttachmentEditModalOpen = false"
                                class="btn border border-gray-300 px-4 py-2 rounded hover:bg-gray-100">
                                {{ __db('cancel') }}
                            </button>
                            <button type="submit" class="btn !bg-[#B68A35] text-white px-6 py-2 rounded">
                                {{ __db('save') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>


@section('script')
    <script>
        window.attachmentsFieldErrors = @json($errors->getBag('default')->toArray());
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-attachment-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.getAttribute('data-attachment'));
                    window.dispatchEvent(new CustomEvent('open-edit-attachment', {
                        detail: data
                    }));
                });
            });
        });
    </script>

    <script>
        function attachmentsComponent() {
            return {
                attachments: [],

                addAttachment() {
                    this.attachments.push({
                        id: null,
                        title_id: '',
                        document_date: '',
                        file_name: '',
                        file_path: '',
                        file: null,
                        deleted: false,
                    });
                },

                toggleDelete(index) {
                    const attachment = this.attachments[index];
                    if (!attachment.id) {
                        this.attachments.splice(index, 1);
                    } else {
                        attachment.deleted = !attachment.deleted;
                    }
                },
            };
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-attachment-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will permanently delete the attachment.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.delete-delegate-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will permanently delete the delegate.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
