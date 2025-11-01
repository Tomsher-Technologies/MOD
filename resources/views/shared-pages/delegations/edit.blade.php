<div x-data="{ isAttachmentEditModalOpen: false }">

    @if (!$delegation->canAssignServices() && $delegation?->invitationStatus?->code !== '1')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p><strong>{{ __db('Note') }}:</strong>
                {{ __db('delegation_has_status') }} "{{ $delegation->invitationStatus?->value }}"
                {{ __db('cannot_assign_these_services') }}.</p>
        </div>
    @endif

    <x-back-btn title=""
        back-url="{{ Session::has('edit_delegations_last_url') ? Session::get('edit_delegations_last_url') : route('delegations.index') }}" />

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
                    <label class="form-label">{{ __db('invitation_from') }}: <span class="text-red-600">*</span></label>
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
                    <label class="form-label">{{ __db('continent') }}: <span class="text-red-600">*</span></label>
                    <select name="continent_id" id="continent-select"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select') }}</option>
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
                    <label class="form-label">{{ __db('country') }}: <span class="text-red-600">*</span></label>
                    <select name="country_id" id="country-select"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select') }}</option>
                        {{-- @foreach (getAllCountries() as $option)
                            <option value="{{ $option->id }}"
                                {{ old('country_id', $delegation->country_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>
                        @endforeach --}}
                    </select>
                    @error('country_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('invitation_status') }}: <span
                            class="text-red-600">*</span></label>
                    <select name="invitation_status_id" id="invitation_status_select"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __db('select') }}</option>
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


                @php
                    $participationStatusOptions = getDropDown('participation_status');
                    $participationStatusDefaultOption = null;
                    if ($participationStatusOptions && $participationStatusOptions->options) {
                        $participationStatusDefaultOption = $participationStatusOptions->options->firstWhere('code', 1); // NOT_YET_ARRIVED
                    }

                    $selectedValue = old(
                        'participation_status_id',
                        $delegation->participation_status_id ?? optional($participationStatusDefaultOption)->id,
                    );

                @endphp


                <div class="col-span-3">
                    <label class="form-label">{{ __db('participation_status') }}: <span
                            class="text-red-600">*</span></label>

                    <select name="participation_status_id" disabled
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">

                        @if ($selectedValue && $participationStatusOptions)
                            @foreach ($participationStatusOptions->options as $option)
                                <option value="{{ $option->id }}"
                                    {{ $selectedValue == $option->id ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @elseif ($participationStatusDefaultOption)
                            <option value="{{ $participationStatusDefaultOption->id }}" selected>
                                {{ $participationStatusDefaultOption->value }}
                            </option>
                            @if ($participationStatusOptions)
                                @foreach ($participationStatusOptions->options as $option)
                                    @if ($option->id != $participationStatusDefaultOption->id)
                                        <option value="{{ $option->id }}">
                                            {{ $option->value }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        @else
                            <option value="" selected>
                                {{ __db('not_yet_arrived') }}
                            </option>
                            @if ($participationStatusOptions)
                                @foreach ($participationStatusOptions->options as $option)
                                    <option value="{{ $option->id }}">
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            @endif
                        @endif
                    </select>

                    <input type="hidden" name="participation_status_id"
                        value="{{ $selectedValue ?? ($participationStatusDefaultOption->id ?? '') }}">

                    @error('participation_status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-span-12">
                    <div class="flex w-full justify-between gap-5">
                        <div class="w-full">
                            <label class="form-label">{{ __db('note_1') }}:</label>
                            <textarea name="note1" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500"
                                placeholder="{{ __db('note_1') }}">{{ old('note1', $delegation->note1) }}</textarea>
                            @error('note1')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="w-full">
                            <label class="form-label">{{ __db('note_2') }}:</label>
                            <textarea name="note2" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500"
                                placeholder="{{ __db('note_2') }}">{{ old('note2', $delegation->note2) }}</textarea>
                            @error('note2')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                @directCanany(['edit_delegations', 'delegate_edit_delegations'])
                    <div class="col-span-12 mt-6 flex gap-3">
                        <button type="submit"
                            class="btn !bg-[#B68A35] text-white rounded-lg py-3 px-6 font-semibold hover:shadow-lg transition"
                            @click="window.hasUnsavedAttachments = false">
                            {{ __db('update_delegation') }}
                        </button>
                    </div>
                @enddirectCanany
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
                                        <label class="form-label">{{ __db('title') }}<span
                                                class="text-red-600">*</span></label>
                                        <select :name="`attachments[${index}][title_id]`" required
                                            class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-3"
                                            x-model.number="attachment.title_id" :disabled="attachment.deleted"
                                            @change="window.hasUnsavedAttachments = true">
                                            <option value="">{{ __db('select_title') }}</option>
                                            @foreach ($options as $option)
                                                <option value="{{ $option->id }}">{{ $option->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('file') }}<span
                                                class="text-red-600">*</span></label>
                                        <input required
                                            class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-3"
                                            type="file" :name="`attachments[${index}][file]`"
                                            @change="e => { 
                                                attachment.file = e.target.files[0];
                                                window.hasUnsavedAttachments = true;
                                            }">
                                    </div>

                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('document_date') }}</label>
                                        <input type="date" :name="`attachments[${index}][document_date]`"
                                            class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-3"
                                            x-model="attachment.document_date" :disabled="attachment.deleted"
                                            @change="window.hasUnsavedAttachments = true" />
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

                            @directCanany(['edit_delegations', 'delegate_edit_delegations'])
                                <button type="button" class="btn !bg-[#B68A35] text-white rounded-lg px-4 py-2 mt-3"
                                    @click="addAttachment()">
                                    + {{ __db('add_attachments') }}
                                </button>
                            @enddirectCanany


                            @directCanany(['edit_delegations', 'delegate_edit_delegations'])
                                <div class="mt-6">
                                    <button type="submit" class="btn !bg-[#B68A35] text-white rounded-lg px-6 py-2"
                                        x-show="attachments.length > 0" @click="window.hasUnsavedAttachments = false">
                                        {{ __db('save_attachments') }}
                                    </button>
                                </div>
                            @enddirectCanany
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
            @directCanany(['add_delegates', 'delegate_add_delegates'])
                <a href="{{ route('delegations.addDelegate', $delegation->id) }}" id="add-attachment-btn"
                    class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-3 px-5">
                    <span>{{ __db('add_delegate') }}</span>
                </a>
            @enddirectCanany

            @directCanany(['add_travels', 'delegate_edit_delegations'])
                <a href="{{ route('delegations.addTravel', ['id' => $delegation->id, 'showArrival' => 1]) }}"
                    id="add-attachment-btn"
                    class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">
                    <span>{{ __db('add_group_arrival') }}</span>
                </a>
            @enddirectCanany

            @directCanany(['add_travels', 'delegate_edit_delegations'])
                <a href="{{ route('delegations.addTravel', ['id' => $delegation->id, 'showDeparture' => 1]) }}"
                    id="add-attachment-btn"
                    class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">
                    <span>{{ __db('add_group_departure') }}</span>
                </a>
            @enddirectCanany

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
                            'label' => __db('delegate_code'),
                            'render' => fn($row) => $row->code ?? '-',
                        ],
                        [
                            'label' => __db('name_ar'),
                            'render' => function ($row) {
                                $badge = $row->team_head
                                    ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                                    : '';

                                $name = $row->name_ar;
                                $title = $row->title_ar;

                                return $badge . '<div class="block">' . getLangTitleSeperator($title, $name) . '</div>';
                            },
                        ],

                        [
                            'label' => __db('designation_ar'),
                            'render' => fn($row) => $row?->designation_ar,
                        ],
                        [
                            'label' => __db('name_en'),
                            'render' => function ($row) {
                                $badge = $row->team_head
                                    ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                                    : '';

                                $name = $row->name_en;
                                $title = $row->title_en;

                                return $badge . '<div class="block">' . getLangTitleSeperator($title, $name) . '</div>';
                            },
                        ],

                        [
                            'label' => __db('designation_en'),
                            'render' => fn($row) => $row->designation_en,
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
                            'render' => fn($row) => $row->parent?->getTranslation('name') ?? '-',
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
                                return $row->participation_status ? __db($row->participation_status) : '-';
                            },
                        ],
                        [
                            'label' => __db('accommodation'),
                            'render' => function ($row) {
                                if (!$row->accommodation) {
                                    return __db('not_required');
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

                <x-reusable-table :data="$delegation->delegates" table-id="delegatesTableEdit" :enableColumnListBtn="true" :columns="$columns"
                    :no-data-message="__db('no_data_found')" />




                @foreach ($delegation->delegates as $delegate)
                    <div id="delegate-transport-modal-{{ $delegate->id }}" tabindex="-1" aria-hidden="true"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:p-6">
                        <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="flex items-start justify-between p-4 border-b rounded-t">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ __db('transport_information_for') }} {{ $delegate->name_en ?? '-' }}
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                    data-modal-hide="delegate-transport-modal-{{ $delegate->id }}"
                                    aria-label="Close modal">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="p-6 space-y-6">
                                {{-- Arrival --}}
                                <section>
                                    @php $arrival = $delegate->delegateTransports->where('type', 'arrival')->first(); @endphp
                                    <h4 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('arrival') }}</h4>
                                    <span class="mb-2">{{ __db('mode') . ': ' . $arrival?->mode ?? '-' }}</span>
                                    <div
                                        class="border rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 bg-gray-50">
                                        @if ($arrival)
                                            <div class="border-b md:border-b-0 md:border-r border-gray-300 pb-4 pr-4">
                                                <p class="font-medium text-gray-600">{{ __db('to_airport') }}</p>
                                                <p class="text-base text-gray-900">
                                                    {{ $arrival->airport?->value ?? '-' }}</p>
                                            </div>
                                            <div class="border-b md:border-b-0 border-gray-300 pb-4">
                                                <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                                <p class="text-base text-gray-900">{{ $arrival->flight_no ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="py-4 pr-4 md:border-r md:border-gray-300">
                                                <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                                <p class="text-base text-gray-900">{{ $arrival->flight_name ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="py-4 !pb-0">
                                                <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                                <p class="text-base text-gray-900">{{ $arrival->date_time ?? '-' }}
                                                </p>
                                            </div>
                                        @else
                                            <p class="col-span-2 text-gray-500">{{ __db('no_arrival_information') }}.
                                            </p>
                                        @endif
                                    </div>
                                </section>

                                {{-- Departure --}}
                                <section>
                                    @php $departure = $delegate->delegateTransports->where('type', 'departure')->first(); @endphp
                                    <h4 class="text-xl font-semibold text-gray-900 pb-2">{{ __db('departure') }}</h4>
                                    <span class="mb-2">{{ __db('mode') . ': ' . $departure?->mode ?? '-' }}</span>
                                    <div
                                        class="border rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 bg-gray-50">
                                        @if ($departure)
                                            <div class="border-b md:border-b-0 md:border-r border-gray-300 pb-4 pr-4">
                                                <p class="font-medium text-gray-600">{{ __db('from_airport') }}</p>
                                                <p class="text-base text-gray-900">
                                                    {{ $departure->airport?->value ?? '-' }}</p>
                                            </div>
                                            <div class="border-b md:border-b-0 border-gray-300 pb-4">
                                                <p class="font-medium text-gray-600">{{ __db('flight_no') }}</p>
                                                <p class="text-base text-gray-900">{{ $departure->flight_no ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="py-4 pr-4 md:border-r md:border-gray-300">
                                                <p class="font-medium text-gray-600">{{ __db('flight_name') }}</p>
                                                <p class="text-base text-gray-900">
                                                    {{ $departure->flight_name ?? '-' }}</p>
                                            </div>
                                            <div class="py-4 !pb-0">
                                                <p class="font-medium text-gray-600">{{ __db('date_time') }}</p>
                                                <p class="text-base text-gray-900">{{ $departure->date_time ?? '-' }}
                                                </p>
                                            </div>
                                        @else
                                            <p class="col-span-2 text-gray-500">
                                                {{ __db('no_departure_information') }}.</p>
                                        @endif
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <hr class="mx-6 border-neutral-200 h-10">

    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('escorts') }} ({{ $delegation->escorts->count() }})</h2>

        @if ($delegation->canAssignServices())
            <div class="flex items-center gap-3">
                @directCanany(['add_escorts', 'escort_add_escorts'])
                    <a href="{{ route('escorts.index', ['delegation_id' => $delegation->id, 'assignment_mode' => 'escort']) }}"
                        class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('escorts') }}</a>
                @enddirectCanany
            </div>
        @endif
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
                                // return '<a href="' .
                                //     $searchUrl .
                                //     '" class="text-[#B68A35] hover:underline">' .
                                //     e($escort->code) .
                                //     '</a>';

                                return '<span class="">' . e($escort->code) . '</span>';
                            },
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->military_number]);
                                // return '<a href="' .
                                //     $searchUrl .
                                //     '" class="text-[#B68A35] hover:underline">' .
                                //     e($escort->military_number) .
                                //     '</a>';

                                return '<span class="">' . e($escort->military_number) . '</span>';
                            },
                        ],
                        [
                            'label' => __db('rank'),
                            'key' => 'rank',
                            'render' => fn($escort) => e(optional($escort->internalRanking)->value),
                        ],
                        [
                            'label' => __db('name'),
                            'key' => 'name',
                            'render' => function ($escort) {
                                $searchUrl = route('escorts.index', ['search' => $escort->name_en]);
                                // return '<a href="' .
                                //     $searchUrl .
                                //     '" class="text-[#B68A35] hover:underline">' .
                                //     e($escort->getTranslation('title') . ' ' . $escort->getTranslation('name')) .
                                //     '</a>';

                                return '<span class="">' .
                                    e(getLangTitleSeperator($escort?->getTranslation('title'), $escort?->getTranslation('name'))) .
                                    '</span>';
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
                            'label' => __db('accommodation'),
                            'render' => function ($escort) {
                                $room = $escort->currentRoomAssignment ?? null;

                                $accommodation = $escort->current_room_assignment_id
                                    ? $room?->hotel?->hotel_name .
                                            ' - ' .
                                            $room->roomType?->roomType?->value .
                                            ' - ' .
                                            $room?->room_number ??
                                        '-'
                                    : '-';
                                //;
                                return $accommodation ?? '-';
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
                            'render' => function ($escort) use ($delegation) {
                                $editUrl = route('escorts.edit', $escort->id);
                                $output = '<div class="flex align-center gap-4">';

                                $output .=
                                    '<a href="' .
                                    $editUrl .
                                    '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#B68A35" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg></a>';
                                if ($escort->status == 1) {
                                    $currentDelegationAssignment = $escort->delegations;
                                    if ($currentDelegationAssignment) {
                                        $unassignUrl = route('escorts.unassign', $escort->id);
                                        $output .=
                                            '<form action="' .
                                            $unassignUrl .
                                            '" method="POST" class="unassign-escort-form" style="display:inline;">' .
                                            csrf_field() .
                                            '<input type="hidden" name="delegation_id" value="' .
                                            $delegation->id .
                                            '" />
                                                <button type="submit" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto">
                                                    <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                    </svg>
                                                    <span>' .
                                            __db('unassign') .
                                            '</span>
                                                </button>
                                            </form>';
                                    } else {
                                        $assignUrl = route('escorts.assignIndex', $escort->id);
                                        $output .=
                                            '<a href="' .
                                            $assignUrl .
                                            '" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-xs flex items-center gap-2 py-1 rounded-lg me-auto">
                                                <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                                <span>' .
                                            __db('assign') .
                                            '</span>
                                            </a>';
                                    }
                                }
                                $output .= '</div>';
                                return $output;
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$delegation->escorts" table-id="escortsTable" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>

        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">



    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('drivers') }} ({{ $delegation->drivers->count() }})
        </h2>

        @if ($delegation->canAssignServices())
            <div class="flex items-center gap-3">
                @directCanany(['add_drivers', 'driver_add_drivers'])
                    <a href="{{ route('drivers.index', ['delegation_id' => $delegation->id, 'assignment_mode' => 'driver']) }}"
                        class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('drivers') }}</a>
                @enddirectCanany
            </div>
        @endif
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
                                // return '<a href="' .
                                //     $searchUrl .
                                //     '" class="text-[#B68A35] hover:underline">' .
                                //     e($driver->code) .
                                //     '</a>';

                                return '<span class="">' . e($driver->code) . '</span>';
                            },
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->military_number]);
                                // return '<a href="' .
                                //     $searchUrl .
                                //     '" class="text-[#B68A35] hover:underline">' .
                                //     e($driver->military_number) .
                                //     '</a>';

                                return '<span class="">' . e($driver->military_number) . '</span>';
                            },
                        ],
                        [
                            'label' => __db('name'),
                            'key' => 'name',
                            'render' => function ($driver) {
                                $searchUrl = route('drivers.index', ['search' => $driver->name_en]);

                                return '<span class="">' . e(getLangTitleSeperator($driver?->getTranslation('title'), $driver?->getTranslation('name'))) . '</span>';
                            },
                        ],
                        [
                            'label' => __db('phone_number'),
                            'key' => 'phone_number',
                            'render' => fn($driver) => '<span dir="ltr">' . e($driver->phone_number) . '</span>',
                        ],
                        [
                            'label' => __db('vehicle') . ' ' . __db('type'),
                            'key' => 'car_type',
                            'render' => fn($driver) => e($driver->car_type),
                        ],
                        [
                            'label' => __db('vehicle') . ' ' . __db('number'),
                            'key' => 'car_number',
                            'render' => fn($driver) => e($driver->car_number),
                        ],
                        [
                            'label' => __db('capacity'),
                            'key' => 'capacity',
                            'render' => fn($driver) => e($driver->capacity),
                        ],
                        [
                            'label' => __db('accommodation'),
                            'render' => function ($driver) {
                                $room = $driver->currentRoomAssignment ?? null;

                                $accommodation = $driver->current_room_assignment_id
                                    ? $room?->hotel?->hotel_name .
                                            ' - ' .
                                            $room->roomType?->roomType?->value .
                                            ' - ' .
                                            $room?->room_number ??
                                        '-'
                                    : '-';
                                //;
                                return $accommodation ?? '-';
                            },
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
                            'render' => function ($driver) use ($delegation) {
                                $editUrl = route('drivers.edit', $driver->id);
                                $output = '<div class="flex align-center gap-4">';

                                $output .=
                                    '<a href="' .
                                    $editUrl .
                                    '">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#B68A35" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg>
                                        </a>';
                                if ($driver->status == 1) {
                                    $currentDelegationAssignment = $driver->delegations
                                        ->where('pivot.status', 1)
                                        ->where('id', $delegation->id)
                                        ->first();
                                    if ($currentDelegationAssignment) {
                                        $unassignUrl = route('drivers.unassign', $driver->id);
                                        $output .=
                                            '<form action="' .
                                            $unassignUrl .
                                            '" method="POST" class="unassign-driver-form" style="display:inline;">' .
                                            csrf_field() .
                                            '<input type="hidden" name="delegation_id" value="' .
                                            $delegation->id .
                                            '" />
                                                <button type="submit" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto">
                                                    <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                    <span>' .
                                            __db('unassign') .
                                            '</span>
                                                </button>
                                            </form>';
                                    } else {
                                        $assignUrl = route('drivers.assignIndex', $driver->id);
                                        $output .=
                                            '<a href="' .
                                            $assignUrl .
                                            '" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-xs flex items-center gap-2 py-1 rounded-lg me-auto">
                                                <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                <span>' .
                                            __db('assign') .
                                            '</span>
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


                <x-reusable-table :data="$delegation->drivers" table-id="driversTable" :columns="$columns" :no-data-message="__db('no_data_found')" />
            </div>

        </div>
    </div>



    <hr class="mx-6 border-neutral-200 h-10">




    <hr class="mx-6 border-neutral-200 h-10">
    <div class="flex items-center justify-between mt-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('interviews') }}</h2>
        @if ($delegation->canAssignServices())
            <div class="flex items-center gap-3">
                @directCanany(['add_interviews', 'delegate_edit_delegations'])
                    <a href="{{ route('delegations.addInterview', $delegation) }}"
                        class="bg-[#B68A35] text-white px-4 py-2 rounded-lg">{{ __db('add') . ' ' . __db('interview') }}</a>
                @enddirectCanany
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                @php
                    $delegatesCollection = collect($delegation->delegates)->mapWithKeys(function ($delegate) {
                        return [(int) $delegate->id => $delegate];
                    });

                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'render' => fn($row, $key) => $key + 1,
                        ],
                        [
                            'label' => __db('date_time'),
                            'render' => fn($row) => $row->date_time
                                ? Carbon\Carbon::parse($row->date_time)->format('Y-m-d H:i')
                                : '-',
                        ],
                        [
                            'label' => __db('attended_by'),
                            'render' => function ($row) {
                                $attendees = $row->interviewMembers->where('type', 'from');
                                $names = $attendees
                                    ->map(
                                        fn($im) => e(
                                            $im->resolveMemberForInterview($row)?->getTranslation('name') ?? '-',
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
                                    $otherMemberId = $row->otherMember->getTranslation('name') ?? $row->other_member_id;
                                    if ($otherMemberId) {
                                        $with =
                                            '<a href="' .
                                            route('other-interview-members.show', [
                                                'other_interview_member' => base64_encode($row->other_member_id),
                                            ]) .
                                            '" class="!text-[#B68A35]">
                                    <span class="block">' .
                                            __db('other_member') .
                                            ': ' .
                                            e($row->otherMember->getTranslation('name')) .
                                            '</span>
                                </a>';
                                    }
                                } else {
                                    $toMembers = $row->toMembers->where('type', 'to');
                                    if ($toMembers->count() > 0) {
                                        $delegateNames = $toMembers
                                            ->map(function ($member) use ($row) {
                                                $delegate = $member->resolveMemberForInterview($row);
                                                if ($delegate) {
                                                    if ($delegate instanceof \App\Models\Delegate) {
                                                        return '<a href="' .
                                                            route(
                                                                'delegations.show',
                                                                $row->interviewWithDelegation->id ?? '',
                                                            ) .
                                                            '" class="block !text-[#B68A35]">' .
                                                            e(getLangTitleSeperator($delegate?->getTranslation('title'), $delegate?->getTranslation('name'))
                                                            ) .
                                                            '</a>';
                                                    } elseif ($delegate instanceof \App\Models\OtherInterviewMember) {
                                                        return '<a href="' .
                                                            route('other-interview-members.show', [
                                                                'other_interview_member' => base64_encode(
                                                                    $delegate->id,
                                                                ),
                                                            ]) .
                                                            '" class="block !text-[#B68A35]">' .
                                                            __db('other_member') .
                                                            ': ' .
                                                            e($delegate->getTranslation('name')) .
                                                            '</a>';
                                                    }
                                                }
                                                return '';
                                            })
                                            ->filter()
                                            ->implode('');

                                        if (!empty($delegateNames)) {
                                            $with = $delegateNames;
                                        } else {
                                            $with =
                                                '<a href="' .
                                                route('delegations.show', $row->interviewWithDelegation->id ?? '') .
                                                '" class="!text-[#B68A35]">' .
                                                ' ' .
                                                __db('delegation_id') .
                                                ' : ' .
                                                e($row?->interviewWithDelegation?->code ?? '') .
                                                '</a>';
                                        }
                                    } else {
                                        $with =
                                            '<a href="' .
                                            route('delegations.show', $row->interviewWithDelegation->id ?? '') .
                                            '" class="!text-[#B68A35]">' .
                                            '' .
                                            __db('delegation_id') .
                                            ' : ' .
                                            e(
                                                ($row->interviewWithDelegation
                                                    ? $row->interviewWithDelegation->code
                                                    : '') ?? '',
                                            ) .
                                            '</a>';
                                    }
                                }

                                return $with;
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
                <x-reusable-table :data="$delegation->interviews" table-id="interviewsTable" :columns="$columns"
                    :no-data-message="__db('no_data_found')" />
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
                                class="w-full p-2 border rounded" @change="handleTitleChange">
                                <option value="">{{ __db('select_title') }}</option>
                                @foreach ($attachmentTitleDropdown->options as $option)
                                    <option value="{{ $option->id }}">{{ $option->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label block mb-1">{{ __db('document_date') }}</label>
                            <input type="date" name="attachments[0][document_date]"
                                x-model="attachment.document_date" class="w-full p-2 border rounded"
                                @change="handleDateChange" />
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
                            <button type="submit" class="btn !bg-[#B68A35] text-white px-6 py-2 rounded"
                                @click="window.hasUnsavedAttachments = false">
                                {{ __db('save') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

@php
    $invitationDropdowns = getDropDown('invitation_status');
    $unassignableStatuses = $unassignableStatus;
@endphp


@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const invitationDropdowns = @json($invitationDropdowns);
            const unassignableStatusCodes = @json($unassignableStatuses);


            $('#invitation_status_select').on('change', function() {
                let selectedValue = parseInt($(this).val(), 10);
                const selectOptionSettings = invitationDropdowns?.options?.find((val) => val.id ===
                    selectedValue);

                if (!selectOptionSettings) {
                    console.log("No settings found for selected value:", selectedValue);
                    return;
                }

                const isUnassignable = unassignableStatusCodes.includes(Number(selectOptionSettings?.code));

                if (isUnassignable) {
                    Swal.fire({
                        title: '{{ __db('are_you_sure') }}',
                        text: "{{ __db('all_unassign_warning') }}",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __db('i_understand') }}',
                    })
                }
            });

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
        document.addEventListener('DOMContentLoaded', () => {
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

            window.hasUnsavedAttachments = false;

            $('form[action="{{ route('delegations.updateAttachment', $delegation->id) }}"]').on('submit',
                function() {
                    window.hasUnsavedAttachments = false;
                });

            $('button[type="submit"]').on('click', function() {
                window.hasUnsavedAttachments = false;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (window.hasUnsavedAttachments) {
                e.preventDefault();
                e.returnValue = '';
                return '';
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
                    window.hasUnsavedAttachments = true;
                },

                toggleDelete(index) {
                    const attachment = this.attachments[index];
                    if (!attachment.id) {
                        this.attachments.splice(index, 1);
                    } else {
                        attachment.deleted = !attachment.deleted;
                    }
                    window.hasUnsavedAttachments = true;
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
                        title: '{{ __db('are_you_sure') }}',
                        text: "{{ __db('delete_attachment_confirm_msg') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __db('yes') }}',
                        cancelButtonText: '{{ __db('cancel') }}'
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
                        title: '{{ __db('are_you_sure') }}',
                        text: "{{ __db('delete_delegate_confirm_msg') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __db('yes') }}',
                        cancelButtonText: '{{ __db('cancel') }}',
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                            cancelButton: 'px-4 rounded-lg'
                        },
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
                        title: '{{ __db('are_you_sure') }}',
                        text: "{{ __db('delete_interview_confirm_msg') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __db('yes') }}',
                        cancelButtonText: '{{ __db('cancel') }}',
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                            cancelButton: 'px-4 rounded-lg'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.unassign-escort-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('unassign_confirm_text') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes_unassign') }}",
                        cancelButtonText: "{{ __db('cancel') }}",
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                            cancelButton: 'px-4 rounded-lg'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.unassign-driver-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __db('are_you_sure') }}",
                        text: "{{ __db('unassign_confirm_text') }}",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "{{ __db('yes_unassign') }}",
                        cancelButtonText: "{{ __db('cancel') }}",
                        customClass: {
                            popup: 'w-full max-w-2xl',
                            confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                            cancelButton: 'px-4 rounded-lg'
                        },
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

        // Handle delegation delete button
        document.querySelectorAll('.delete-delegation-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const delegationId = this.getAttribute('data-delegation-id');
                const delegationCode = this.getAttribute('data-delegation-code');

                Swal.fire({
                    title: '{{ __db('are_you_sure') }}',
                    text: "{{ __db('delete_delegation_confirm_msg') }} " + delegationCode + "?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __db('yes_delete') }}',
                    cancelButtonText: '{{ __db('cancel') }}',
                    customClass: {
                        popup: 'w-full max-w-2xl',
                        confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]',
                        cancelButton: 'px-4 rounded-lg'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit delete form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ url('/mod-events/delegations') }}/' + delegationId;

                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = '{{ csrf_token() }}';
                        form.appendChild(tokenInput);

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
