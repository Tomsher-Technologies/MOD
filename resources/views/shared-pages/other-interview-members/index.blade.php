<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('all_other_interview_members') }}</h2>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
    <div class="xl:col-span-12 h-full">
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
            <div class="flex items-center justify-between gap-12 mb-4">
                <form class="w-full flex flex-wrap gap-2 items-center"
                    action="{{ route('other-interview-members.index') }}" method="GET">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="text" name="search"
                            class="block w-full p-2.5 pl-10 text-secondary-light text-sm border border-[#d1d5db] rounded-lg"
                            placeholder="{{ __db('search') }}" value="{{ request('search') }}">
                    </div>

                    <div>
                        <select name="status"
                            class="block w-[150px] p-2.5 text-secondary-light text-sm border border-[#d1d5db] rounded-lg">
                            <option value="">{{ __db('select_status') }}</option>
                            <option value="1" {{ request()->status == 1 ? 'selected' : '' }}>
                                {{ __db('active') }}</option>
                            <option value="2" {{ request()->status == 2 ? 'selected' : '' }}>
                                {{ __db('inactive') }}</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('other-interview-members.index') }}"
                            class="border border-[#B68A35] text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 flex items-center justify-center">
                            {{ __db('reset') }}
                        </a>

                        <button type="submit"
                            class="bg-[#E6D7A2] hover:bg-[#d6c68d] text-[#5D471D] font-medium rounded-lg text-sm px-4 py-2 focus:ring-4 focus:outline-none focus:ring-yellow-300">
                            {{ __db('search') }}
                        </button>
                    </div>
                </form>
            </div>

            @php
                $columns = [
                    [
                        'label' => __db('sl_no'),
                        'render' => function ($member, $key) use ($other_interview_members) {
                            return $key +
                                1 +
                                ($other_interview_members->currentPage() - 1) * $other_interview_members->perPage();
                        },
                    ],
                    // [
                    //     'label' => __db('event_code'),
                    //     'render' => function ($member) {
                    //         return ($member->event?->code ?? '-') .
                    //             ($member->event?->is_default
                    //                 ? ' <span class="inline-block rounded bg-green-500 px-2 py-1 text-xs font-semibold text-white">' .
                    //                     __db('default') .
                    //                     '</span>'
                    //                 : '');
                    //     },
                    // ],
                    [
                        'label' => __db('name_en'),
                        'render' => fn($member) => e($member->name_en),
                    ],
                    [
                        'label' => __db('name_ar'),
                        'render' => fn($member) => e($member->name_ar),
                    ],
                    [
                        'label' => __db('status'),
                        'render' => function ($member) {
                            $statusVal = strtolower($member->status);
                            $isActive = $statusVal === 'active' || $statusVal === 'completed' || $member->status == 1;
                            $label = $isActive ? __db('active') : __db('inactive');
                            $bgColor = $isActive ? 'bg-green-500 text-white' : 'bg-yellow-400 text-black';

                            return '<span class="inline-block rounded px-2 py-1 text-xs font-semibold ' .
                                $bgColor .
                                '">' .
                                $label .
                                '</span>';
                        },
                    ],
                    [
                        'label' => __db('action'),
                        'permission' => ['edit_other_interview_members', 'view_other_interview_members'],
                        'render' => function ($member) {
                            $btn = '';

                            if (can('view_other_interview_members')) {
                                $btn .=
                                    '<a href="' .
                                    route('otherInterviewMembers.show', [
                                        'id' => base64_encode($member->id),
                                    ]) .
                                    '" class="w-8 h-8  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">';
                                $btn .=
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 12" fill="none"><path d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z" stroke="#B68A35" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z" stroke="#B68A35" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                $btn .= '</a>';
                            }

                            if (can('edit_other_interview_members')) {
                                $btn .=
                                    '<a href="' .
                                    route('otherInterviewMembers.edit', [
                                        'id' => base64_encode($member->id),
                                    ]) .
                                    '" title="' .
                                    __db('edit') .
                                    '" class="w-8 h-8  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center me-2">';
                                $btn .=
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z" fill="#B68A35"/></svg>';
                                $btn .= '</a>';
                            }
                            return $btn;
                        },
                    ],
                ];
            @endphp

            <x-reusable-table :data="$other_interview_members ?? []" :columns="$columns" :no-data-message="__db('no_data_found')" />
        </div>
    </div>
</div>

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {});

        function update_status(el) {
            var status = el.checked ? 0 : 1;
            $.post('{{ route('staff.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    toastr.success("{{ __db('status_updated') }}");
                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                }
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            });
        }
    </script>
@endsection
