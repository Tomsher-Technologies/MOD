<div>
    <x-back-btn title="{{ __db('add_travel_details') }}" back-url="{{ route('delegations.show', $delegation->id) }}" />

    @php
        $columns = [
            ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? ''],
            ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom->value ?? ''],
            ['label' => __db('continent'), 'render' => fn($row) => $row->continent->value ?? ''],
            ['label' => __db('country'), 'render' => fn($row) => $row->country->value ?? ''],
            ['label' => __db('invitation_status'), 'render' => fn($row) => $row->invitationStatus->value ?? ''],
            ['label' => __db('participation_status'), 'render' => fn($row) => $row->participationStatus->value ?? ''],
        ];
        $data = [$delegation];
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
        <div class="xl:col-span-12">
            <div class="bg-white h-full w-full rounded-lg border-0 p-10">
                <x-reusable-table :columns="$columns" :data="$data" />
            </div>
        </div>
    </div>

    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('delegates') }}
    </h2>

    @error('delegate_ids')
        <div class="text-red-600">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('delegations.storeOrUpdateInterview', $delegation->id) ?? '#' }}"
        enctype="multipart/form-data">
        @csrf
        @php
            $delegates = $delegation->delegates->filter(fn($d) => !$d->transport);

            $columns = [
                [
                    'label' => '',
                    'render' => function ($row) {
                        $checked = collect(old('delegate_ids'))->contains($row->id) ? 'checked' : '';
                        return '<input type="checkbox" name="delegate_ids[]" value="' .
                            $row->id .
                            '" class="w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded" ' .
                            $checked .
                            ' />';
                    },
                ],
                [
                    'label' => __db('sl_no'),
                    'render' => fn($row, $key) => $row->code,
                ],
                [
                    'label' => __db('title'),
                    'render' => fn($row) => $row?->getTranslation('title') ?? '',
                ],
                [
                    'label' => __db('name'),
                    'render' => function ($row) {
                        $badge = $row->team_head
                            ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                            : '';
                        return $badge . '<div class="block">' . e($row->value_en ?? '-') . '</div>';
                    },
                ],
                [
                    'label' => __db('designation'),
                    'render' => fn($row) => $row->designation_en ?? '',
                ],
                [
                    'label' => __db('internal_ranking'),
                    'render' => fn($row) => $row->internalRanking->value ?? '',
                ],
                [
                    'label' => __db('gender'),
                    'render' => fn($row) => $row->gender->value ?? '',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <x-reusable-table :columns="$columns" :data="$delegates" />
                </div>
            </div>
        </div>



        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('add_interview_requests') }}</h2>
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white grid grid-cols-4 gap-5 mt-6 mb-4">

                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('date_time') }}:</label>
                    <input type="datetime-local" class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                        name="date_time" value="{{ old('date_time') }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('interview_with') }}:</label>
                    @error('interview_type')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <div class="flex items-center gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="interview_type" value="delegation" checked
                                class="text-[#B68A35] focus:ring-[#B68A35]" onchange="toggleInterviewInput(this)">
                            <span class="text-gray-700">{{ __db('delegation') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="interview_type" value="other"
                                class="text-[#B68A35] focus:ring-[#B68A35]" onchange="toggleInterviewInput(this)">
                            <span class="text-gray-700">{{ __db('other') }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex col-span-2 items-end gap-3" id="delegation-input">
                    <div class="w-full">
                        <label class="form-label block text-gray-700 font-semibold">{{ __db('interview_with') }}
                            ({{ __db('delegate_id') }}):</label>
                        <input type="text" id="delegation_code_input" name="interview_with_delegation"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            placeholder="{{ __db('delegate_id') }}" value="{{ old('interview_with_delegation') }}">
                        @error('interview_with_delegation')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" id="search-delegation-btn"
                        class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">
                        <svg class="pe-1 text-[#FFF]" width="25" height="25" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="w-[150px]">{{ __db('search_delegation_id') }}</span>
                    </button>
                </div>


                <div id="other-input" class="hidden col-span-1">
                    <label class="form-label block text-gray-700 font-medium">{{ __db('select') }}:</label>
                    <select name="interview_with_other_member_id" class="select2 p-3 rounded-lg w-full border text-sm">
                        <option selected disabled>{{ __db('select_other_member') }}</option>
                        @foreach ($otherMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name_en ?? $member->name_ar }}</option>
                        @endforeach
                    </select>

                    @error('interview_with_other_member_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div id="membersbox">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('members') }}:</label>
                    <select name="members" class="select2 p-3 rounded-lg w-full border text-sm" id="members-select">
                    </select>

                    @error('members')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div id="statusbox">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('status') }}:</label>
                    <select name="status" class="select2 p-3 rounded-lg w-full border text-sm">
                        <option selected disabled>{{ __db('select_status') }}</option>
                        @foreach (getDropdown('interview_status')->options as $status)
                            <option value="{{ $status->id }}">{{ $status->value }}</option>
                        @endforeach
                    </select>

                    @error('status')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="flex justify-start gap-5 items-center">

            <button type="submit" name="submit_exit"
                class="btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">{{ __db('submit_and_exit') }}</button>

            <button type="submit" name="submit_add_new"
                class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">{{ __db('submit_add_new') }}</button>

            <button type="submit" name="submit_add_travel"
                class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">{{ __db('submit_add_flight_details') }}</button>
        </div>


    </form>


    <div id="default-modal4" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg w-full max-w-lg p-6">
            <h3 class="text-xl font-semibold mb-4">{{ __db('search_delegations') }}</h3>

            <div class="grid grid-cols-2 gap-4 mb-6">

                @php
                    $continentOptions = getDropDown('continents');
                    $countryOptions = getDropDown('country');
                @endphp
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('continents') }}:</label>
                    <select id="modal-continent" class="select2 p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select_continent') }}</option>

                        @if ($continentOptions)
                            @foreach ($continentOptions->options as $option)
                                <option value="{{ $option->id }}"
                                    {{ request('continentOptions') == $option->id ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('country') }}:</label>
                    <select id="modal-country" class="select2 p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select_country') }}</option>
                        @if ($countryOptions)
                            @foreach ($countryOptions->options as $option)
                                <option value="{{ $option->id }}"
                                    {{ request('countryOptions') == $option->id ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div id="modal-search-results"
                class="mb-6 max-h-60 overflow-auto border border-gray-300 rounded p-3 hidden">
                <ul id="modal-delegations-list" class="divide-y divide-gray-300">

                </ul>
            </div>

            <div class="flex justify-end gap-3">
                <button id="modal-search-btn"
                    class="btn !bg-[#B68A35] !text-white rounded-lg px-5 py-2 disabled:opacity-50">{{ __db('search') }}</button>

                <button id="modal-select-btn"
                    class="btn !bg-[#B68A35] !text-white rounded-lg px-5 py-2 disabled:opacity-50 hidden">
                    {{ __db('select') }}
                </button>

                <button id="modal-close-btn"
                    class="btn border !border-[#B68A35] !text-[#B68A35] rounded-lg px-5 py-2">{{ __db('cancel') }}</button>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            const checked = e.target.checked;
            document.querySelectorAll('.delegate-checkbox').forEach(cb => cb.checked = checked);
        });
    </script>

    <script>
        window.pageRoutes = {
            delegationSearchByCode: @json(route('delegations.searchByCode')),
            delegationSearchByFilters: @json(route('delegations.search')),
            delegationMembers: @json(route('delegations.members'))
        };
    </script>

    <script>
        function toggleInterviewInput(el) {
            const delegationInput = document.getElementById('delegation-input');
            const otherInput = document.getElementById('other-input');
            const membersBox = document.getElementById('membersbox'); // lowercase id
            const statusBox = document.getElementById('statusbox'); // lowercase id

            if (el.value === 'delegation') {
                delegationInput.classList.remove('hidden');
                otherInput.classList.add('hidden');
                membersBox.classList.remove('hidden');
                statusBox.classList.remove('hidden');
            } else {
                delegationInput.classList.add('hidden');
                otherInput.classList.remove('hidden');
                membersBox.classList.add('hidden');
                statusBox.classList.remove('hidden');
            }
        }
    </script>







    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('search-delegation-btn');
            const codeInput = document.getElementById('delegation_code_input');
            const modal = document.getElementById('default-modal4');
            const modalCloseBtn = document.getElementById('modal-close-btn');
            const modalSelectBtn = document.getElementById('modal-select-btn');
            const modalResults = document.getElementById('modal-search-results');
            const delegationsList = document.getElementById('modal-delegations-list');
            const membersSelect = document.getElementById('members-select');

            let selectedDelegationId = null;
            let selectedMembers = [];

            function openModal() {
                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                delegationsList.innerHTML = '';
                modalResults.classList.add('hidden');
                modalSelectBtn.classList.add('hidden');
                modalSelectBtn.disabled = true;
                selectedDelegationId = null;
            }

            searchBtn.addEventListener('click', function() {
                const code = codeInput.value.trim();
                if (code === '') {
                    openModal();
                    return;
                }

                fetch(`${window.pageRoutes.delegationSearchByCode}?code=${encodeURIComponent(code)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.delegation) {
                            populateMembers(data.delegation.delegates);
                            toastr.success("{{ __db('members_fetched') }}");
                        } else {
                            toastr.error("{{ __db('delegation_not_found') }}");
                            membersSelect.innerHTML =
                                '<option selected disabled>No members found</option>';
                        }
                    })
                    .catch(() => {
                        alert('Error fetching delegation.');
                    });
            });

            const modalSearchBtn = document.getElementById('modal-search-btn');
            modalSearchBtn.addEventListener('click', function() {
                const continentId = document.getElementById('modal-continent').value;
                const countryId = document.getElementById('modal-country').value;

                if (!continentId && !countryId) {
                    alert('Please select at least Continent or Country.');
                    return;
                }

                fetch(`${window.pageRoutes.delegationSearchByFilters}?continent_id=${continentId}&country_id=${countryId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            delegationsList.innerHTML = '';
                            data.delegations.forEach(delegation => {
                                const li = document.createElement('li');
                                li.className = 'py-2 cursor-pointer bg-gray-800 hover:bg-gray-800';
                                li.textContent =
                                    `${delegation.code} - ${delegation.invitationFrom_value}`;
                                li.dataset.id = delegation.id;
                                li.addEventListener('click', function() {
                                    delegationsList.querySelectorAll('li').forEach(el =>
                                        el.classList.remove('bg-[#222]',
                                            'text-white'));
                                    this.classList.add('bg-[#222]', 'text-black');
                                    selectedDelegationId = delegation.id;

                                    document.querySelector(
                                            'input[name="interview_with_delegation"]')
                                        .value = delegation.code;

                                    modalSelectBtn.disabled = false;
                                    modalSelectBtn.classList.remove(
                                        'hidden');
                                });
                                delegationsList.appendChild(li);
                            });
                            modalResults.classList.remove('hidden');
                            toastr.success("{{ __db('delegations_fetched') }}");

                            modalSelectBtn.classList.add('hidden');
                            modalSelectBtn.disabled = true;

                        } else {
                            delegationsList.innerHTML = '<li>No delegations found.</li>';
                            toastr.error("{{ __db('delegation_not_found') }}");
                            modalResults.classList.remove('hidden');

                            modalSelectBtn.classList.add('hidden');
                            modalSelectBtn.disabled = true;
                        }
                    })
                    .catch(() => {
                        alert('Failed to fetch delegations.');
                    });
            });

            modalSelectBtn.addEventListener('click', function() {
                if (!selectedDelegationId) return;

                fetch(`${window.pageRoutes.delegationMembers}/${selectedDelegationId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            populateMembers(data.members);
                            closeModal();
                            toastr.success("{{ __db('members_fetched') }}");
                            // codeInput.value =
                            //     '';
                        } else {
                            toastr.success("{{ __db('failed_to_load_members') }}");

                        }
                    })
                    .catch(() => alert('Error loading members.'));
            });

            modalCloseBtn.addEventListener('click', closeModal);

            function populateMembers(members) {
                if (!members || members.length === 0) {
                    membersSelect.innerHTML = '<option selected disabled>No members found</option>';
                    return;
                }
                membersSelect.innerHTML = '<option selected disabled>Select Members</option>';
                members.forEach(member => {
                    const opt = document.createElement('option');
                    opt.value = member.id;
                    opt.textContent = member.name_en || member.name_ar || 'Unnamed Member';
                    membersSelect.appendChild(opt);
                });
            }
        });
    </script>












@endsection
