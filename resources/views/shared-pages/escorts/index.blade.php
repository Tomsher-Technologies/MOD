<div class="">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('escorts') }}</h2>
        <div class="flex gap-3">

            @directCanany(['import_escorts'])
                <a href="{{ route('escorts.import.form') }}"
                    class="flex text-center items-center px-4 py-2 text-md  !bg-[#B68A35] text-white rounded-lg " type="button">
                    {{ __db('import') . ' ' . __db('escorts') }}
                </a>
            @enddirectCanany

            @if (isset($delegationId) && isset($assignmentMode) && $assignmentMode === 'escort' && Session::has('escorts_index_last_url'))
                <x-back-btn title="" class="" back-url="{{ Session::get('escorts_index_last_url') }}" />
            @endif
        </div>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                @if (isset($delegationId) && isset($assignmentMode) && $assignmentMode === 'escort')
                    <div class="mb-4 p-4 bg-[#E6D7A2] rounded-lg">
                        <h3 class="font-semibold text-lg">{{ __db('assigning_escort_to_delegation') }}</h3>
                        @if (isset($assignmentDelegation))
                            <div class="mt-2 pt-2 ">
                                <p class="text-sm"><strong>{{ __db('delegation') }}:</strong>
                                    {{ $assignmentDelegation->code }}</p>
                                @if ($assignmentDelegation->country)
                                    <p class="text-sm"><strong>{{ __db('country') }}:</strong>
                                        {{ $assignmentDelegation->country->name }}</p>
                                @endif
                                @if ($assignmentDelegation->continent)
                                    <p class="text-sm"><strong>{{ __db('continent') }}:</strong>
                                        {{ $assignmentDelegation->continent->value }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <div class=" mb-4 flex items-center justify-between gap-3">
                    <form class="w-[50%] me-4" action="{{ route('escorts.index') }}" method="GET">
                        @if (isset($delegationId) && isset($assignmentMode))
                            <input type="hidden" name="delegation_id" value="{{ $delegationId }}">
                            <input type="hidden" name="assignment_mode" value="{{ $assignmentMode }}">
                        @endif
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="default-search" name="search"
                                class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                                placeholder="Search by Military Number, Name, Mobile Number"
                                value="{{ request('search') }}" />
                            <button type="submit"
                                class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                        </div>
                    </form>

                    <div class="text-center">
                        <button
                            class="text-white flex items-center gap-1 !bg-[#B68A35] hover:bg-[#A87C27] focus:ring-4 focus:ring-yellow-300 font-sm rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                            type="button" data-drawer-target="filter-drawer" data-drawer-show="filter-drawer"
                            aria-controls="filter-drawer">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                    d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                            </svg>
                            <span>{{ __db('filter') }}</span>
                        </button>
                    </div>
                </div>

                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'key' => 'sl_no',
                            'render' => function ($escort, $key) use ($escorts) {
                                return $escorts->firstItem() + $key;
                            },
                        ],
                        [
                            'label' => __db('escort') . ' ' . __db('code'),
                            'key' => 'escort_id',
                            'render' => fn($escort) => e($escort->code),
                        ],
                        [
                            'label' => __db('military_number'),
                            'key' => 'military_number',
                            'render' => fn($escort) => e($escort->military_number),
                        ],
                        [
                            'label' => __db('title'),
                            'key' => 'title',
                            'render' => fn($escort) => e($escort->getTranslation('title') ?? ''),
                        ],
                        [
                            'label' => __db('name'),
                            'key' => 'name',
                            'render' => fn($escort) => e($escort->getTranslation('name')),
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
                            'label' => __db('assigned') . ' ' . __db('delegation'),
                            'key' => 'assigned_delegation',
                            'render' => function ($escort) {
                                return $escort->delegations
                                    ->where('pivot.status', 1)
                                    ->map(function ($delegation) {
                                        $delegationUrl = $delegation->id
                                            ? route('delegations.show', $delegation->id)
                                            : '';
                                        return '<a class="font-medium !text-[#B68A35] hover:underline" href="' .
                                            $delegationUrl .
                                            '?id=' .
                                            $delegation->id .
                                            '">' .
                                            e($delegation->code) .
                                            '</a>';
                                    })
                                    ->implode(', ');
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
                                    '" onchange="update_status(this)" value="' .
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
                            'render' => function ($escort) use ($delegationId, $assignmentMode) {
                                $editUrl = route('escorts.edit', $escort->id);
                                $output = '<div class="flex align-center gap-4">';
                                if (can(['edit_escorts', 'escort_edit_escorts'])) {
                                    $output .=
                                        '<a href="' .
                                        $editUrl .
                                        '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#B68A35" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/></svg></a>';
                                }
                                if ($escort->status == 1) {
                                    if (
                                        isset($delegationId) &&
                                        isset($assignmentMode) &&
                                        $assignmentMode === 'escort'
                                    ) {
                                        if ($escort->delegations->where('pivot.status', 1)->count() == 0) {
                                            if (can(['assign_escorts', 'escort_edit_escorts'])) {
                                                $assignUrl = route('escorts.assign', $escort->id);
                                                $output .=
                                                    '<form action="' .
                                                    $assignUrl .
                                                    '" method="POST" class="assign-form" style="display:inline;">' .
                                                    csrf_field() .
                                                    '<input type="hidden" name="delegation_id" value="' .
                                                    $delegationId .
                                                    '" />' .
                                                    '<button type="submit" class="assign-btn !bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto">
                                                       <svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                        <span>' .
                                                    __db('assign') .
                                                    '</span>
                                                    </button>
                                                    </form>';
                                            }
                                        }
                                    } else {
                                        if ($escort->delegations->where('pivot.status', 1)->count() > 0) {
                                            if (can(['unassign_escorts', 'escort_edit_escorts'])) {
                                                foreach (
                                                    $escort->delegations->where('pivot.status', 1)
                                                    as $delegation
                                                ) {
                                                    $unassignUrl = route('escorts.unassign', $escort->id);
                                                    $output .=
                                                        '<form action="' .
                                                        $unassignUrl .
                                                        '" method="POST" class="unassign-form" style="display:inline;">' .
                                                        csrf_field() .
                                                        '<input type="hidden" name="delegation_id" value="' .
                                                        $delegation->id .
                                                        '" /><button type="submit" class="unassign-btn !bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto"><svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg><span> Unassign from ' .
                                                        e($delegation->code) .
                                                        '</span></button></form>';
                                                }
                                            }
                                        } else {
                                            if (can(['assign_escorts', 'escort_edit_escorts'])) {
                                                $assignUrl = route('escorts.assignIndex', $escort->id);
                                                $output .=
                                                    '<a href="' .
                                                    $assignUrl .
                                                    '" class="!bg-[#E6D7A2] !text-[#5D471D] px-3 text-[10px] flex items-center gap-2 py-1 rounded-lg me-auto"><svg class="w-5 h-5 !text-[#5D471D]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg><span> Assign</span></a>';
                                            }
                                        }
                                    }
                                }
                                $output .= '</div>';
                                return $output;
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :columns="$columns" :data="$escorts" :enableRowLimit="true"
                    noDataMessage="No escorts found." :rowClass="function ($row) {
                        return $row->delegations->where('pivot.status', 1)->count() > 0 ? 'bg-green-100' : '';
                    }" />

                <div class="mt-3 flex items-center justify-start gap-3 ">
                    <div class="h-5 w-5 bg-green-100 rounded"></div>
                    <span class="text-gray-800 text-sm">{{ __db('assigned') . ' ' . __db('escorts') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="filter-drawer"
    class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80"
    tabindex="-1" aria-labelledby="drawer-label">
    <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
        {{ __db('filter') }}</h5>
    <button type="button" data-drawer-hide="filter-drawer" aria-controls="filter-drawer"
        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
        <span class="sr-only">{{ __db('close_menu') }}</span>
    </button>

    <form action="{{ route('escorts.index') }}" method="GET">
        @if (isset($delegationId) && isset($assignmentMode))
            <input type="hidden" name="delegation_id" value="{{ $delegationId }}">
            <input type="hidden" name="assignment_mode" value="{{ $assignmentMode }}">
        @endif
        <div class="flex flex-col gap-4 mt-4">
            <div class="flex flex-col">
                <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('title_en') }}</label>
                <select multiple name="title_en[]"
                    class="select2 w-full h-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm"
                    data-placeholder="{{ __db('select_title_en') }}">
                    <option value="">{{ __db('all') }}</option>
                    @foreach ($titleEns as $titleEn)
                        <option value="{{ $titleEn }}" @if (is_array(request('title_en', [])) && in_array($titleEn, request('title_en', []))) selected @endif>
                            {{ $titleEn }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('title_ar') }}</label>
                <select multiple name="title_ar[]"
                    class="select2 w-full h-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm"
                    data-placeholder="{{ __db('select_title_ar') }}">
                    <option value="">{{ __db('all') }}</option>
                    @foreach ($titleArs as $titleAr)
                        <option value="{{ $titleAr }}" @if (is_array(request('title_ar', [])) && in_array($titleAr, request('title_ar', []))) selected @endif>
                            {{ $titleAr }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('gender') }}</label>
                <select name="gender_id[]" multiple data-placeholder="{{ __db('select_genders') }}"
                    class="select2 w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                    @foreach (getDropDown('gender')->options as $gender)
                        <option value="{{ $gender->id }}" @if (in_array($gender->id, request('gender_id', []))) selected @endif>
                            {{ $gender->value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('language') }}</label>
                <select name="language_id[]" multiple data-placeholder="{{ __db('select_languages') }}"
                    class="select2 w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                    @foreach (getDropDown('spoken_languages')->options as $language)
                        <option value="{{ $language->id }}" @if (in_array($language->id, request('language_id', []))) selected @endif>
                            {{ $language->value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('delegation') }}</label>
                <select name="delegation_id[]" multiple data-placeholder="{{ __db('select_delegations') }}"
                    class="select2 w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                    @foreach ($delegations as $delegation)
                        <option value="{{ $delegation->id }}" @if (is_array(request('delegation_id', [])) && in_array($delegation->id, request('delegation_id', []))) selected @endif>
                            {{ $delegation->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-6">
            <a href="{{ route('escorts.index', isset($delegationId) && isset($assignmentMode) ? ['delegation_id' => $delegationId, 'assignment_mode' => $assignmentMode] : []) }}"
                class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">Reset</a>
            <button type="submit"
                class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">Filter</button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        function update_status(el) {
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

        document.addEventListener('DOMContentLoaded', function() {
            const storageKey = 'escort_column_visibility';
            const checkboxes = document.querySelectorAll('.column-toggle-checkbox');

            const applyVisibility = () => {
                let preferences = {};
                checkboxes.forEach(checkbox => {
                    const columnKey = checkbox.value;
                    const isVisible = checkbox.checked;
                    preferences[columnKey] = isVisible;
                    document.querySelectorAll(
                            `th[data-column-key='${columnKey}'], td[data-column-key='${columnKey}']`)
                        .forEach(el => {
                            el.style.display = isVisible ? '' : 'none';
                        });
                });
                localStorage.setItem(storageKey, JSON.stringify(preferences));
            };

            const loadPreferences = () => {
                const savedPrefs = JSON.parse(localStorage.getItem(storageKey));
                if (savedPrefs) {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = savedPrefs[checkbox.value] !== false;
                    });
                }
            };

            const table = document.querySelector('table');
            if (table) {
                const headers = table.querySelectorAll('thead th');
                const rows = table.querySelectorAll('tbody tr');
                const columnKeys = @json(array_column($columns, 'key'));

                headers.forEach((th, index) => {
                    if (columnKeys[index]) {
                        th.setAttribute('data-column-key', columnKeys[index]);
                    }
                });

                rows.forEach(row => {
                    row.querySelectorAll('td').forEach((td, index) => {
                        if (columnKeys[index]) {
                            td.setAttribute('data-column-key', columnKeys[index]);
                        }
                    });
                });
            }

            loadPreferences();
            applyVisibility();

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', applyVisibility);
            });

            document.querySelectorAll('.unassign-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __db('are_you_sure') }}',
                        text: '{{ __db('unassign_confirm_text') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __db('yes_unassign') }}',
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

            document.querySelectorAll('.assign-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __db('are_you_sure') }}',
                        text: '{{ __db('assign_confirm_text') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4CAF50',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __db('yes_assign') }}',
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
        });
    </script>
@endpush
