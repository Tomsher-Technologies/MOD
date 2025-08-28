<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('drivers') }}</h2>

    </div>
    <!-- Drivers -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">

        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

                <div class=" mb-4 flex items-center justify-between gap-3">
                    <form class="w-[50%] me-4" action="{{ getRouteForPage('drivers.index') }}" method="GET">
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
                                placeholder="Search by Military Number, Name, Mobile Number, Driver ID, Car Type, Car Number"
                                value="{{ request('search') }}" />
                            <button type="submit"
                                class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __db('search') }}</button>

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
                            'render' => function ($driver, $key) use ($drivers) {
                                return $drivers->firstItem() + $key;
                            },
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
                            'key' => 'phone_number',
                            'render' => fn($driver) => '<span dir="ltr">' . e($driver->phone_number) . '</span>',
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
                        [
                            'label' => __db('assigned') . ' ' . __db('delegation'),
                            'key' => 'assigned_delegation',
                            'render' => function ($driver) {
                                return $driver->delegations
                                    ->map(function ($delegation) use ($driver) {
                                        if ($delegation->pivot->status === 1) {
                                            $unassignUrl = getRouteForPage('drivers.unassign', $driver->id);
                                            $delegationUrl = getRouteForPage('delegation.show', $delegation->id);

                                            $unassignButton = '';

                                            if (can(['assign_drivers'])) {
                                                $unassignButton =
                                                    '
                                                <form class="unassign-form" action="' .
                                                    $unassignUrl .
                                                    '" method="POST" style="display:inline;">
                                                    ' .
                                                    csrf_field() .
                                                    '
                                                    <input type="hidden" name="delegation_id" value="' .
                                                    $delegation->id .
                                                    '" />
                                                    <button type="submit" class="!bg-[#E6D7A2] !text-[#5D471D] px-2 py-1 rounded-lg text-sm flex items-center gap-1">
                                                        Unassign
                                                    </button>
                                                </form>';
                                            }

                                            return '
                                                  <div class="flex items-center gap-2 mb-2">
                                                        ' .
                                                $unassignButton .
                                                '

                                                        <a href="' .
                                                $delegationUrl .
                                                '" class="font-medium !text-[#B68A35] hover:underline">
                                                            ' .
                                                $delegation->code .
                                                '
                                                        </a>

                                                        ' .
                                                ($delegation->pivot->end_date
                                                    ? '<span class="!text-xs">(Till: ' .
                                                        $delegation->pivot->end_date .
                                                        ')</span>'
                                                    : '') .
                                                '
                                                    </div>

                                                ';
                                        }

                                        return null;
                                    })
                                    ->filter()
                                    ->implode('');
                            },
                        ],

                        [
                            'label' => __db('status'),
                            'key' => 'status',
                            'render' => function ($driver) {
                                return '<div class="flex items-center">
                <label for="switch-' .
                                    $driver->id .
                                    '" class="relative inline-block w-11 h-6">
                    <input type="checkbox" id="switch-' .
                                    $driver->id .
                                    '" onchange="update_status(this)" value="' .
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
                            'render' => function ($driver) {
                                $editUrl = getRouteForPage('drivers.edit', $driver->id);

                                $output = '<div class="flex flex-col items-start gap-2">'; // flex column with gap

                                // Edit button
                                if (can(['edit_drivers'])) {
                                    $output .=
                                        '
                                <a href="' .
                                        $editUrl .
                                        '" class="flex items-center gap-2 px-3 py-1 rounded-lg !bg-[#E6D7A2] !text-[#5D471D] text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                        <path fill="#B68A35" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>';
                                }

                                // Assign button
                                if (can(['assign_drivers'])) {
                                    $assignUrl = getRouteForPage('drivers.assignIndex', $driver->id);
                                    $output .=
                                        '
                                <a href="' .
                                        $assignUrl .
                                        '" class="flex items-center gap-2 px-3 py-1 rounded-lg !bg-[#B68A35] !text-white text-sm">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <span>Assign</span>
                                </a>';
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


                <x-reusable-table :columns="$columns" :data="$drivers" :row-class="$rowClass" enableRowLimit
                    no-data-message="No drivers found." />

                <div class="mt-3 flex items-center justify-start gap-3 ">
                    <div class="h-5 w-5 bg-[#f2eccf] rounded"></div>
                    <span class="text-gray-800 text-sm">{{ __db('unassigned') . ' ' . __db('drivers') }}</span>
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

    <form action="{{ getRouteForPage('drivers.index') }}" method="GET">
        <div class="flex flex-col gap-4 mt-4">
            <select name="title[]" placeholder="Title"
                class="w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                @foreach (getDropDown('title')->options as $option)
                    <option value="{{ $option->value }}" @if (in_array($option->value, request('title', []))) selected @endif>
                        {{ $option->value }}</option>
                @endforeach
            </select>
            <select name="car_type[]" placeholder="Vehicle Type"
                class="w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                @foreach (getDropDown('vehicle_type')->options as $option)
                    <option value="{{ $option->value }}" @if (in_array($option->value, request('car_type', []))) selected @endif>
                        {{ $option->value }}</option>
                @endforeach
            </select>
            <select name="car_number[]" placeholder="Plate Number"
                class="w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->car_number }}" @if (in_array($driver->car_number, request('car_number', []))) selected @endif>
                        {{ $driver->car_number }}</option>
                @endforeach
            </select>
            <select name="capacity[]" placeholder="Capacity"
                class="w-full p-3 text-secondary-light rounded-lg border border-gray-300 text-sm">
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->capacity }}" @if (in_array($driver->capacity, request('capacity', []))) selected @endif>
                        {{ $driver->capacity }}</option>
                @endforeach
            </select>
            <select name="delegation_id"
                class="w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light">
                <option value="">{{ __db('all_delegations') }}</option>
                @foreach ($delegations as $delegation)
                    <option value="{{ $delegation->id }}"
                        {{ request('delegation_id') == $delegation->id ? 'selected' : '' }}>
                        {{ $delegation->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-6">
            <a href="{{ getRouteForPage('drivers.index') }}"
                class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">Reset</a>
            <button type="submit"
                class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">Filter</button>
        </div>
    </form>
</div>

<div id="column-visibility-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">{{ __db('column_list') }}</h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="column-visibility-modal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <div class="space-y-3 grid grid-cols-3" id="column-toggles">
                    @foreach ($columns as $column)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="form-checkbox text-blue-600 me-2 column-toggle-checkbox"
                                value="{{ $column['key'] }}" checked>
                            <span>{{ $column['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function update_status(el) {
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

        document.addEventListener('DOMContentLoaded', function() {
            const storageKey = 'driver_column_visibility';
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
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unassignForms = document.querySelectorAll('.unassign-form');

            unassignForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // prevent default submission
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are about to unassign this delegation!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, unassign it!',
                        cancelButtonText: 'Cancel'
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
