<div x-data="{ isAttachmentEditModalOpen: false }">
    <x-back-btn title="" back-url="{{ route('delegations.index') }}" />

    <div class="bg-white h-full w-full rounded-lg border-0 p-6">

        <form action="{{ route('delegations.update', $delegation->id) }}" method="POST" data-ajax-form="true"
            class="mb-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-3">
                    <label class="form-label">{{ __db('delegate_id') }}:</label>
                    <input type="text" name="code"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0 bg-gray-200"
                        value="{{ old('code', $delegation->code ?? '') }}" readonly disabled>
                    @error('code')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('invitation_from') }}:</label>
                    <select name="invitation_from_id"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __db('select_invitation_from') }}</option>
                        @foreach (getDropDown('departments')->options as $option)
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
                    <select name="continent_id" id="continent-select"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __('Select Continent') }}</option>
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
                    <select name="country_id" id="country-select"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __('Select Country') }}</option>
                        @foreach (getAllCountries() as $option)
                            <option value="{{ $option->id }}"
                                {{ old('country_id', $delegation->country_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->name }}
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
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
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
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
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

                @canany(['edit_delegations', 'delegate_edit_delegations'])
                    <div class="col-span-12 mt-6">
                        <button type="submit"
                            class="btn !bg-[#B68A35] text-white rounded-lg py-3 px-6 font-semibold hover:shadow-lg transition">
                            {{ __db('update_delegation') }}
                        </button>
                    </div>
                @endcanany
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
                                'label' => __db(__db('actions')),
                                'permission' => ['edit_delegations', 'del_edit_delegations'],
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
                                        route('attachments.destroy', $row->id) .
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

                    <form method="POST" action="{{ route('delegations.updateAttachment', $delegation->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('POST')


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

                            @canany(['edit_delegations', 'delegate_edit_delegations'])
                                <button type="button" class="btn !bg-[#B68A35] text-white rounded-lg px-4 py-2 mt-3"
                                    @click="addAttachment()">
                                    + {{ __db('add_attachments') }}
                                </button>
                            @endcanany


                            @canany(['edit_delegations', 'delegate_edit_delegations'])
                                <div class="mt-6">
                                    <button type="submit" class="btn !bg-[#B68A35] text-white rounded-lg px-6 py-2"
                                        x-show="attachments.length > 0">
                                        {{ __db('save_attachments') }}
                                    </button>
                                </div>
                            @endcanany
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
            @canany(['add_delegates', 'delegate_add_delegates'])
                <a href="{{ route('delegations.addDelegate', $delegation->id) }}" id="add-attachment-btn"
                    class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-3 px-5">
                    <span>{{ __db('add_delegate') }}</span>
                </a>
            @endcanany

            @canany(['add_travels', 'delegate_edit_delegations'])
                <a href="{{ route('delegations.addTravel', ['id' => $delegation->id, 'showArrival' => 1]) }}"
                    id="add-attachment-btn"
                    class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">
                    <span>{{ __db('add_group_arrival') }}</span>
                </a>
            @endcanany

            @canany(['add_travels', 'delegate_edit_delegations'])
                <a href="{{ route('delegations.addTravel', ['id' => $delegation->id, 'showDeparture' => 1]) }}"
                    id="add-attachment-btn"
                    class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">
                    <span>{{ __db('add_group_departure') }}</span>
                </a>
            @endcanany

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
                            'render' => fn($escort) => e($escort->title->value ?? ''),
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
                            'render' => function ($row) {
                                return $row->participation_status ?? '-';
                            },
                        ],
                        [
                            'label' => __db('accommodation'),
                            'render' => function ($row) {
                                // If accommodation is not required (boolean field is false)
                                if (!$row->accommodation) {
                                    return 'Not Required';
                                }
                                
                                $room = $row->currentRoomAssignment ?? null;

                                $accommodation = $row->current_room_assignment_id
                                    ? $room?->hotel?->hotel_name .
                                            ' - ' .
                                            $room->roomType?->roomType?->value .
                                            ' - ' .
                                            $room?->room_number ??
                                        '-'
                                    : '-';

                                return $accommodation ?? '-';
                            },
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
                        [
                            'label' => __db('action'),
                            'render' => function ($row) use ($delegation) {
                                $editUrl = route('delegations.editDelegate', [
                                    'delegation' => $delegation->id,
                                    'delegate' => $row->id,
                                ]);

                                $deleteForm = '';
                                $editButton = '';
                                if (can(['delete_delegates', 'delegate_delete_delegates'])) {
                                    $deleteForm =
                                        '
                                        <form action="' .
                                        route('delegations.destroyDelegate', [$delegation, $row]) .
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
                                }

                                if (can(['edit_delegates', 'delegate_edit_delegates'])) {
                                    $editButton =
                                        '
                                        <a href="' .
                                        $editUrl .
                                        '" class="text-blue-600 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                                <path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#B68A35"></path>
                                            </svg>
                                        </a>';
                                }

                                return '<div class="flex items-center gap-5">' . $deleteForm . $editButton . '</div>';
                            },
                        ],
                    ];

                    $noDataMessage = __db('no_data_found');
                @endphp

                <x-reusable-table :data="$delegation->delegates" :columns="$columns" :no-data-message="__db('no_data_found')" />

                @foreach ($delegation->delegates as $delegate)
                    <div id="delegate-transport-modal-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl mx-auto">
                            <div class="bg-white rounded-lg shadow ">
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        {{ __db('transport_information_for') }}
                                        {{ $delegate->name_en ?? '-' }}</h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 mr-auto inline-flex items-center"
                                        data-modal-hide="delegate-transport-modal-{{ $delegate->id }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                            <p class="col-span-2 text-gray-500">No arrival information available.</p>
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
                                            <p class="col-span-2 text-gray-500">No departure information available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <hr class="mx-6 border-neutral-200 h-10">

    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('escorts') }} ({{ $delegation->escorts->count() }})</h2>

        <div class="flex items-center gap-3">
            @canany(['add_escorts', 'escort_add_escorts'])
                <a href={{ route('escorts.index') }}
                    class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('escorts') }}</a>
            @endcanany
        </div>
    </div>

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
                            'label' => __db('escort') . ' ' . __db('code'),
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->code]);
                                return '<a href="' . $searchUrl . '" class="text-[#B68A35] hover:underline">' . e($escort->code) . '</a>';
                            },
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->military_number]);
                                return '<a href="' . $searchUrl . '" class="text-[#B68A35] hover:underline">' . e($escort->military_number) . '</a>';
                            },
                        ],
                        [
                            'label' => __db('title'),
                            'key' => 'title',
                            'render' => fn($escort) => e($escort->title->value ?? ''),
                        ],
                        [
                            'label' => __db('name_en'),
                            'key' => 'name',
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->name_en]);
                                return '<a href="' . $searchUrl . '" class="text-[#B68A35] hover:underline">' . e($escort->name_en) . '</a>';
                            },
                        ],
                        [
                            'label' => __db('phone_number'),
                            'key' => 'phone_number',
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

                        [
                            'label' => __db('status'),
                            'key' => 'status',
                            'permission' => ['edit_escorts', 'escort_edit_escorts'],
                            'render' => function ($escort) {
                                return '<div class="flex items-center">
                <label for="switch-' .
                                    $escort->id .
                                    '" class="relative inline-block w-11 h-6">
                    <input type="checkbox" id="switch-' .
                                    $escort->id .
                                    '" onchange="update_escort_status(this)" value="' .
                                    $escort->id .
                                    '" class="sr-only peer" ' .
                                    ($escort->status == 1 ? 'checked' : '') .
                                    ' />
                    <div class="block bg-gray-300 peer-checked:bg-[#009448] w-11 h-6 rounded-full transition"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                </label>
            </div>';
                            },
                        ],
                        [
                            'label' => __db('actions'),
                            'key' => 'actions',
                            'permission' => ['assign_escorts', 'escort_edit_escorts'],
                            'render' => function ($escort) {
                                $editUrl = route('escorts.edit', $escort->id);
                                $output = '<div class="flex align-center gap-4">';

                                $output .=
                                    '<a href="' .
                                    $editUrl .
                                    '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#B68A35" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg></a>';
                                if ($escort->status == 1) {
                                    if ($escort->delegations->where('pivot.status', 1)->count() > 0) {
                                        foreach ($escort->delegations->where('pivot.status', 1) as $delegation) {
                                            $unassignUrl = route('escorts.unassign', $escort->id);
                                            $output .=
                                                '<form action="' .
                                                $unassignUrl .
                                                '" method="POST" style="display:inline;">' .
                                                csrf_field() .
                                                '<input type="hidden" name="delegation_id" value="' .
                                                $delegation->id .
                                                '" /><button type="submit" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto"><svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg><span> Unassign from ' .
                                                e($delegation->code) .
                                                '</span></button></form>';
                                        }
                                    } else {
                                        $assignUrl = route('escorts.assignIndex', $escort->id);
                                        $output .=
                                            '<a href="' .
                                            $assignUrl .
                                            '" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-xs flex items-center gap-2 py-1 rounded-lg me-auto"><svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg><span> Assign</span></a>';
                                    }
                                }
                                $output .= '</div>';
                                return $output;
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$delegation->escorts" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>

        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">



    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('drivers') }} ({{ $delegation->drivers->count() }})</h2>


        <div class="flex items-center gap-3">
            @canany(['add_drivers', 'driver_add_drivers'])
                <a href={{ route('drivers.index') }}
                    class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('drivers') }}</a>
            @endcanany
        </div>
    </div>


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
                            'label' => __db('driver') . ' ' . __db('code'),
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->code]);
                                return '<a href="' . $searchUrl . '" class="text-[#B68A35] hover:underline">' . e($driver->code) . '</a>';
                            },
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->military_number]);
                                return '<a href="' . $searchUrl . '" class="text-[#B68A35] hover:underline">' . e($driver->military_number) . '</a>';
                            },
                        ],
                        [
                            'label' => __db('title'),
                            'key' => 'title',
                            'render' => fn($driver) => e($driver->title->value ?? ''),
                        ],
                        [
                            'label' => __db('name_en'),
                            'key' => 'name_en',
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->name_en]);
                                return '<a href="' . $searchUrl . '" class="text-[#B68A35] hover:underline">' . e($driver->name_en) . '</a>';
                            },
                        ],
                        [
                            'label' => __db('phone_number'),
                            'key' => 'phone_number',
                            'render' => fn($driver) => '<span dir="ltr">' . e($driver->phone_number) . '</span>',
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
                        [
                            'label' => __db('status'),
                            'key' => 'status',
                            'permission' => ['edit_drivers', 'driver_edit_drivers'],
                            'render' => function ($driver) {
                                return '<div class="flex items-center">
                                        <label for="switch-driver' .
                                    $driver->id .
                                    '" class="relative inline-block w-11 h-6">
                                            <input type="checkbox" id="switch-driver' .
                                    $driver->id .
                                    '" onchange="update_driver_status(this)" value="' .
                                    $driver->id .
                                    '" class="sr-only peer" ' .
                                    ($driver->status == 1 ? 'checked' : '') .
                                    ' />
                                            <div class="block bg-gray-300 peer-checked:bg-[#009448] w-11 h-6 rounded-full transition"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                                        </label>
                                    </div>';
                            },
                        ],
                        [
                            'label' => __db('actions'),
                            'key' => 'actions',
                            'permission' => ['assign_drivers', 'driver_edit_drivers'],
                            'render' => function ($driver) {
                                $editUrl = route('drivers.edit', $driver->id);
                                $output = '<div class="flex align-center gap-4">';

                                $output .=
                                    '<a href="' .
                                    $editUrl .
                                    '">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#B68A35" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg>
                                        </a>';
                                if ($driver->status == 1) {
                                    if ($driver->delegations->where('pivot.status', 1)->count() > 0) {
                                        foreach ($driver->delegations->where('pivot.status', 1) as $delegation) {
                                            $unassignUrl = route('drivers.unassign', $driver->id);
                                            $output .=
                                                '<form action="' .
                                                $unassignUrl .
                                                '" method="POST" style="display:inline;">' .
                                                csrf_field() .
                                                '<input type="hidden" name="delegation_id" value="' .
                                                $delegation->id .
                                                '" />
                                                    <button type="submit" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto">
                                                        <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                        <span> Unassign from ' .
                                                e($delegation->code) .
                                                '</span>
                                                    </button>
                                                </form>';
                                        }
                                    } else {
                                        $assignUrl = route('drivers.assignIndex', $driver->id);
                                        $output .=
                                            '<a href="' .
                                            $assignUrl .
                                            '" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-xs flex items-center gap-2 py-1 rounded-lg me-auto">
                                                <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                <span> Assign</span>
                                            </a>';
                                    }
                                }
                                $output .= '</div>';
                                return $output;
                            },
                        ],
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




    <hr class="mx-6 border-neutral-200 h-10">
    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('interviews') }}</h2>
        <div class="flex items-center gap-3">
            @canany(['add_interviews', 'delegate_edit_delegations'])
                <a href="{{ route('delegations.addInterview', $delegation) }}"
                    class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('interview') }}</a>
            @endcanany
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
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
                            'render' => function ($row) use ($delegatesCollection, $delegation) {
                                if ($row->interviewMembers && count($row->interviewMembers)) {
                                    $attendedBy = collect($row->interviewMembers)->filter(
                                        fn($m) => $m->type === 'from',
                                    );
                                    if ($attendedBy->isEmpty()) {
                                        return '-';
                                    }

                                    return $attendedBy
                                        ->map(function ($member) use ($delegatesCollection, $delegation) {
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
                                    $otherMemberId = $row->otherMember->name_en ?? $row->other_member_id;
                                    if ($otherMemberId) {
                                        $with =
                                            '<a href="' .
                                            route('other-interview-members.show', [
                                                'other_interview_member' => base64_encode($otherMemberId),
                                            ]) .
                                            '" class="!text-[#B68A35]">
                                    <span class="block">Other Member: ' .
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
                        [
                            'label' => __db('status'),
                            'render' => fn($row) => e($row->status->title ?? ($row->status->value ?? 'Unknown')),
                        ],
                        [
                            'label' => __db('action'),
                            'render' => function ($row) use ($delegation) {
                                $editUrl = route('delegations.editInterview', [
                                    'delegation' => $delegation->id,
                                    'interview' => $row->id,
                                ]);

                                $deleteForm = '';
                                $editButton = '';

                                if (can(['delete_interviews', 'delegate_edit_delegations'])) {
                                    $deleteForm =
                                        '
                        <form action="' .
                                        route('delegations.destroyInterview', [$row]) .
                                        '" method="POST" class="delete-interview-form">
                            ' .
                                        csrf_field() .
                                        '
                            ' .
                                        method_field('DELETE') .
                                        '
                            <button type="submit" class="delete-interview-btn text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                </svg>
                            </button>
                        </form>';
                                }
                                if (can(['edit_interviews', 'delegate_edit_delegations'])) {
                                    $editButton =
                                        '
                        <a href="' .
                                        $editUrl .
                                        '" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                <path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#B68A35"></path>
                            </svg>
                        </a>';
                                }

                                return '<div class="flex items-center gap-5">' . $deleteForm . $editButton . '</div>';
                            },
                        ],
                    ];

                @endphp
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
                <div class="relative bg-white rounded-lg shadow ">
                    <button @click="isAttachmentEditModalOpen = false" type="button"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <form :action="`{{ route('delegations.updateAttachment', $delegation->id) }}`" method="POST"
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.edit-attachment-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const data = JSON.parse(btn.getAttribute('data-attachment'));
                    window.dispatchEvent(new CustomEvent('open-edit-attachment', {
                        detail: data
                    }));
                });
            });
        });
    </script>

    <script>
        window.attachmentsFieldErrors = @json($errors->getBag('default')->toArray());
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            const delegationCountryId = @json(old('country_id', $delegation->country_id));

            $('#continent-select').on('change', function() {
                const continentId = $(this).val();
                const countrySelect = $('#country-select');

                countrySelect.find('option[value!=""]').remove();

                if (continentId) {
                    $.get('{{ route('countries.by-continent') }}', {
                        continent_ids: continentId
                    }, function(data) {
                        $.each(data, function(index, country) {
                            countrySelect.append(new Option(country.name, country.id, false,
                                false));
                        });

                        if (delegationCountryId) {
                            countrySelect.val(delegationCountryId);
                        } else {
                            countrySelect.val(null);
                        }

                        countrySelect.trigger('change');
                    }).fail(function() {
                        console.log('Failed to load countries');
                    });
                }
            });

            const selectedContinent = $('#continent-select').val();
            if (selectedContinent) {
                $('#continent-select').trigger('change');
            }
        });

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

            document.querySelectorAll('.delete-interview-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will permanently delete the interview.",
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


        function update_escort_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('escorts.status') }}', {
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
            $.post('{{ route('drivers.status') }}', {
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
