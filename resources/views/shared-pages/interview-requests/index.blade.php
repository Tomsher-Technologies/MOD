<div class="">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('interview_requests') }}</h2>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class=" mb-4 flex items-center justify-between gap-3">
                    <form class="w-[50%] me-4" action="{{ route('delegations.interviewsIndex') }}" method="GET">
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
                                placeholder="" value="{{ request('search') }}" />
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
                            'render' => function ($row, $key) use ($interviews) {
                                return $interviews->firstItem() + $key;
                            },
                        ],
                        [
                            'label' => __db('date_time'),
                            'render' => fn($row) => $row->date_time
                                ? \Carbon\Carbon::parse($row->date_time)->format('Y-m-d H:i')
                                : '-',
                        ],
                        [
                            'label' => __db('delegation'),
                            'render' => function ($row) {
                                $delegationUrl = route('delegations.show', $row->delegation->id);

                                return '
                                                <div class="flex items-center gap-2">
                                                    ' .
                                    '

                                                    <a href="' .
                                    $delegationUrl .
                                    '" class="font-medium !text-[#B68A35] hover:underline">
                                                        ' .
                                    $row->delegation->code .
                                    '
                                                    </a>

                                                    ';
                            },
                        ],
                        [
                            'label' => __db('attended_by'),
                            'render' => function ($row) {
                                $attendees = $row->fromMembers;
                                $names = $attendees
                                    ->map(function ($im) use ($row) {
                                        $member = $im->resolveMemberForInterview($row);
                                        return $member
                                            ? e(
                                                $member->getTranslation('title') .
                                                    '. ' .
                                                    $member->getTranslation('name'),
                                            )
                                            : '-';
                                    })
                                    ->filter()
                                    ->implode('<br>');

                                return $names ?: '-';
                            },
                        ],
                        [
                            'label' => __db('interview_with'),
                            'render' => function ($row) {
                                if (!empty($row->other_member_id) && $row->otherMember) {
                                    $otherMemberId = $row->other_member_id;
                                    if ($otherMemberId) {
                                        $with =
                                            '<a href="' .
                                            route('other-interview-members.show', [
                                                'other_interview_member' => base64_encode($otherMemberId),
                                            ]) .
                                            '" class="!text-[#B68A35]">
                                <span class="block">Other Member: ' .
                                            e($row->otherMember?->getTranslation('name')) .
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
                        ['label' => __db('status'), 'render' => fn($row) => e(ucfirst($row->status->value))],
                        [
                            'label' => __db('actions'),
                            'permission' => ['add_interviews', 'delegate_edit_delegations'],
                            'render' => function ($row) {
                                $editUrl = route('delegations.editInterview', [
                                    'delegation' => $row->delegation_id,
                                    'interview' => $row->id,
                                ]);
                                $output = '<div class="flex align-center gap-4 ">';
                                $output .=
                                    '<a class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center" href="' .
                                    $editUrl .
                                    '"><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 512 512\'><path d=\'M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z\' fill=\'#B68A35\'></path></svg></a>';
                                $output .= '</div>';
                                return $output;
                            },
                        ],
                    ];
                    $data = $interviews;
                    $noDataMessage = __db('no_interviews_found');
                @endphp

                <x-reusable-table :columns="$columns" :data="$data" :enableRowLimit="true" :noDataMessage="$noDataMessage" />

                <div class="mt-4">
                    {{ $interviews->appends(request()->input())->links() }}
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

    <form action="{{ route('delegations.interviewsIndex') }}" method="GET">
        <div class="flex flex-col gap-4 mt-4">

            <div class="flex flex-col">
                <label class="form-label block text-gray-700 font-medium">{{ __db('continents') }}</label>
                <select multiple name="continent_id[]" id="continent-select" data-placeholder="{{ __db('select') }}"
                    class="select2 w-full rounded-lg border border-gray-300 text-sm">
                    <option value="">{{ __db('select') }}</option>
                    @foreach (getDropDown('continents')->options as $continent)
                        <option value="{{ $continent->id }}"
                            {{ is_array(request('continent_id')) && in_array($continent->id, request('continent_id')) ? 'selected' : '' }}>
                            {{ $continent->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="form-label block text-gray-700 font-medium">{{ __db('countries') }}</label>
                <select name="country_id[]" id="country-select" multiple data-placeholder="{{ __db('select') }}"
                    class="select2 w-full rounded-lg border border-gray-300 text-sm">
                    <option value="">{{ __db('select') }}</option>
                    @foreach (getAllCountries() as $option)
                        <option value="{{ $option->id }}"
                            {{ is_array(request('country_id')) && in_array($option->id, request('country_id')) ? 'selected' : '' }}>
                            {{ $option->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="form-label block text-gray-700 font-medium">{{ __db('status') }}</label>
                <select name="status_id"
                    class="select2 w-full bg-white !py-3 text-sm !px-6 rounded-lg border text-secondary-light"
                    data-placeholder="{{ __db('select') }}" multiple data-placeholder="{{ __db('select') }}">
                    @foreach (getDropDown('interview_status')->options as $status)
                        <option value="{{ $status->id }}"
                            {{ request('status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->value }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="grid grid-cols-2 gap-4 mt-6">
            <a href="{{ route('delegations.interviewsIndex') }}"
                class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
            <button type="submit"
                class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
        </div>
    </form>
</div>


@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            let initiallySelectedCountries = $('#country-select').val() || [];

            $('#continent-select').on('change', function() {
                const continentId = $(this).val();
                const countrySelect = $('#country-select');

                countrySelect.find('option[value!=""]').remove();

                if (continentId) {
                    $.get('{{ route('countries.by-continent') }}', {
                        continent_ids: continentId
                    }, function(data) {
                        $.each(data, function(index, country) {
                            const isSelected = initiallySelectedCountries.includes(country
                                .id.toString());

                            countrySelect.append(new Option(country.name, country.id, false,
                                isSelected));
                        });

                        countrySelect.trigger('change');
                    }).fail(function() {
                        console.log('Failed to load countries');
                    });
                } else {
                    countrySelect.val(null).trigger('change');
                }
            });

            const selectedContinent = $('#continent-select').val();
            if (selectedContinent && selectedContinent.length > 0) {
                $('#continent-select').trigger('change');
            }
        });
    </script>
@endsection
