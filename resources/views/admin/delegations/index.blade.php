@extends('layouts.admin_account', ['title' => __db('all_delegations')])

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('all_delegations') }}</h2>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class="flex items-center justify-between gap-12 mb-4">
                    <form class="w-[75%]" action="{{ route('delegations.index') }}" method="GET">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <div class="flex">
                                <input type="text" name="search"
                                    class="block w-[35%] p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg"
                                    placeholder="{{ __db('search') }}" value="{{ request('search') }}">

                                @php
                                    $statusDropdown = getDropDown('participation_status');
                                @endphp

                                <select name="participation_status"
                                    class="block w-[20%] mr-2 p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg">
                                    <option value="">{{ __db('select_status') }}</option>
                                    @if ($statusDropdown)
                                        @foreach ($statusDropdown->options as $option)
                                            <option value="{{ $option->id }}"
                                                {{ request('participation_status') == $option->id ? 'selected' : '' }}>
                                                {{ $option->value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="flex">
                                <a href="{{ route('delegations.index') }}"
                                    class="absolute end-[80px] bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                    {{ __db('reset') }}</a>
                                <button type="submit"
                                    class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    {{ __db('search') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

                @php
                    $columns = [
                        [
                            'label' => 'Delegation ID',
                            'render' => fn($delegation) => e($delegation->code),
                        ],
                        [
                            'label' => 'Invitation From',
                            'render' => fn($delegation) => e($delegation->invitationFrom->value ?? '-'),
                        ],
                        [
                            'label' => 'Team Head',
                            'render' => function ($delegation) {
                                $teamHeads = $delegation->delegates->filter(fn($d) => $d->team_head);
                                return $teamHeads->isNotEmpty()
                                    ? $teamHeads->map(fn($head) => e($head->name_en))->implode('<br>')
                                    : '-';
                            },
                        ],
                        [
                            'label' => 'Escorts',
                            'render' => function ($delegation) {
                                $escorts = $delegation->delegates->pluck('escorts')->filter()->unique();
                                return $escorts->isNotEmpty() ? $escorts->map(fn($e) => e($e))->implode('<br>') : '-';
                            },
                        ],
                        [
                            'label' => 'Drivers',
                            'render' => function ($delegation) {
                                $drivers = $delegation->delegates->pluck('drivers')->filter()->unique();
                                return $drivers->isNotEmpty() ? $drivers->map(fn($d) => e($d))->implode('<br>') : '-';
                            },
                        ],
                        [
                            'label' => 'Invitation Status',
                            'render' => fn($delegation) => e($delegation->invitationStatus->value ?? '-'),
                        ],
                        [
                            'label' => 'Participation Status',
                            'render' => fn($delegation) => e($delegation->participationStatus->value ?? '-'),
                        ],
                        [
                            'label' => 'Note',
                            'render' => function ($delegation) {
                                return '<svg data-modal-target="default-modal1" data-modal-toggle="default-modal1"
                                            class="w-6 h-6 text-[#B68A35] cursor-pointer" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7.556 8.5h8m-8 3.5H12m7.111-7H4.89a.896.896 0 0 0-.629.256.868.868 0 0 0-.26.619v9.25c0 .232.094.455.26.619A.896.896 0 0 0 4.89 16H9l3 4 3-4h4.111a.896.896 0 0 0 .629-.256.868.868 0 0 0 .26-.619v-9.25a.868.868 0 0 0-.26-.619.896.896 0 0 0-.63-.256Z" />
                                        </svg>';
                            },
                        ],
                        [
                            'label' => 'Actions',
                            'render' => function ($delegation) {
                                // Use your own permission logic
                                $buttons = '';
                                if (auth()->user() && auth()->user()->can('edit_delegations')) {
                                    $buttons .=
                                        '<a href="' .
                                        route('delegations.edit', $delegation->id) .
                                        '" title="' .
                                        __db('edit') .
                                        '"
                                        class="w-8 h-8 bg-[#FBF3D6] text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 512 512\'><path d=\'M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z\' fill=\'#B68A35\'></path></svg>
                                    </a>';
                                }
                                if (auth()->user() && auth()->user()->can('view_delegations')) {
                                    $buttons .=
                                        '<a href="' .
                                        route('delegations.show', $delegation->id) .
                                        '" class="w-8 h-8 bg-[#FBF3D6] text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns=\'http://www.w3.org/2000/svg\' width=\'18\' height=\'18\' viewBox=\'0 0 16 12\' fill=\'none\'><path d=\'M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z\' stroke=\'#7C5E24\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\' /><path d=\'M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z\' stroke=\'#7C5E24\' stroke-width=\'1.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\' /></svg>
                                    </a>';
                                }
                                return $buttons;
                            },
                        ],
                    ];
                @endphp

                <x-reusable-table :data="$delegations" :columns="$columns" :no-data-message="__db('no_data_found')" />

                <div class="mt-4">
                    {{ $delegations->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
