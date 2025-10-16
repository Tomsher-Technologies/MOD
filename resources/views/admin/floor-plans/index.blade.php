@extends('layouts.admin_account', ['title' => __db('floor_plans')])

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h2 class="font-semibold text-2xl">{{ __db('floor_plans') }}</h2>
        @directCanany(['add_floor_plans'])
            <a href="{{ route('floor-plans.create') }}" class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12"
                type="button">
                {{ __db('add_floor_plan') }}
            </a>
        @enddirectCanany
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <form class="w-[50%] me-4" action="{{ route('floor-plans.index') }}" method="GET">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="default-search" name="search" value="{{ request('search') }}"
                                class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg"
                                placeholder="{{ __db('search_floor_plans') }}" />
                            <button type="submit"
                                class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                            <a href="{{ route('floor-plans.index') }}"
                                class="absolute end-[85px] bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                {{ __db('reset') }}</a>

                        </div>
                    </form>
                </div>

                @php
                    $columns = [
                        [
                            'label' => __db('sl_no'),
                            'key' => 'sl_no',
                            'render' => fn($row, $key) => $key +
                                1 +
                                ($floorPlans->currentPage() - 1) * $floorPlans->perPage(),
                        ],
                        [
                            'label' => __db('event'),
                            'key' => 'event',
                            'render' => fn($floorPlan) => e($floorPlan->event->name_en ?? '-'),
                        ],
                        [
                            'label' => __db('title_en'),
                            'key' => 'title_en',
                            'render' => fn($floorPlan) => e($floorPlan->title_en),
                        ],
                        [
                            'label' => __db('title_ar'),
                            'key' => 'title_ar',
                            'render' => fn($floorPlan) => e($floorPlan->title_ar),
                        ],
                        [
                            'label' => __db('files_count'),
                            'key' => 'files_count',
                            'render' => fn($floorPlan) => count($floorPlan->file_paths ?? []),
                        ],
                        [
                            'label' => __db('created_at'),
                            'key' => 'created_at',
                            'render' => fn($floorPlan) => $floorPlan->created_at
                                ? $floorPlan->created_at->format('d-m-Y H:i')
                                : '-',
                        ],
                        [
                            'label' => __db('actions'),
                            'key' => 'actions',
                            'permission' => ['view_floor_plans', 'edit_floor_plans', 'delete_floor_plans'],
                            'render' => function ($floorPlan) {
                                $buttons = '';

                                if (can(['view_floor_plans'])) {
                                    $buttons .=
                                        '<a href="' .
                                        route('floor-plans.show', $floorPlan->id) .
                                        '" class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 12" fill="none"><path d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z" stroke="#B68A35" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z" stroke="#B68A35" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </a>';
                                }

                                if (can(['edit_floor_plans'])) {
                                    $buttons .=
                                        '<a href="' .
                                        route('floor-plans.edit', $floorPlan->id) .
                                        '" title="' .
                                        __db('edit') .
                                        '"
                                        class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#B68A35"></path></svg>
                                    </a>';
                                }

                                if (can(['delete_floor_plans'])) {
                                    $buttons .=
                                        '<button type="button" title="' .
                                        __db('delete') .
                                        '" class="w-8 h-8 text-red-600 dark:text-red-400 rounded-full inline-flex items-center justify-center delete-floor-plan-btn" data-floor-plan-id="' .
                                        $floorPlan->id .
                                        '" data-floor-plan-title="' .
                                        e($floorPlan->title_en) .
                                        '">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </button>';
                                }

                                return $buttons;
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$floorPlans" :enableRowLimit="true" table-id="floorPlansTable" :enableColumnListBtn="true"
                    :columns="$columns" :no-data-message="__db('no_floor_plans_found')" />

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-floor-plan-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const floorPlanId = this.getAttribute('data-floor-plan-id');
                    const floorPlanTitle = this.getAttribute('data-floor-plan-title');

                    Swal.fire({
                        title: '{{ __db('are_you_sure') }}',
                        text: "{{ __db('delete_floor_plan_confirm_msg') }} " +
                            floorPlanTitle + "?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __db('yes_delete') }}',
                        cancelButtonText: '{{ __db('cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ url('/mod-events/floor-plans') }}/" +
                                floorPlanId;

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
