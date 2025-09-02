<div>
    @php
        $isEditMode = $interview->exists;
        $title = $isEditMode ? __db('edit_interview') : __db('add_interview');

        $oldFromDelegateIds = collect(
            old('from_delegate_ids', $isEditMode ? $interview->fromMembers()->pluck('member_id')->all() : []),
        );

        $oldInterviewType = old(
            'interview_type',
            $isEditMode ? ($interview->type === 'del_del' ? 'delegation' : 'other') : 'delegation',
        );

        $oldToDelegationCode = old(
            'interview_with_delegation_code',
            $isEditMode && $interview->interviewWithDelegation ? $interview->interviewWithDelegation->code : '',
        );

        $oldToDelegateId = old('to_delegate_id', $isEditMode ? $interview->toMembers()->value('member_id') : '');

        $formAction = $isEditMode
            ? route('delegations.storeOrUpdateInterview', [
                'delegation' => $delegation,
                'interview' => $interview,
            ])
            : route('delegations.storeOrUpdateInterview', $delegation);
    @endphp

    <x-back-btn :title="$title" back-url="{{ route('delegations.show', $delegation->id) }}" />

    {{-- <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
        <div class="xl:col-span-12">
            <div class="bg-white h-full w-full rounded-lg border-0 p-10">
                @php
                    $columns = [
                        ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? ''],
                        ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom->value ?? ''],
                        ['label' => __db('continent'), 'render' => fn($row) => $row->continent->value ?? ''],
                        ['label' => __db('country'), 'render' => fn($row) => $row->country->value ?? ''],
                    ];
                @endphp
                <x-reusable-table :columns="$columns" :data="[$delegation]" />
            </div>
        </div>
    </div> --}}

    <form method="POST" action="{{ $formAction ?? '#' }}" enctype="multipart/form-data" data-ajax-form="true">
        @csrf

        @error('from_delegate_ids')
            <div class="text-red-600 mt-1">{{ $message }}</div>
        @enderror

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    @php
                        $delegates = $delegation->delegates->filter(fn($d) => !$d->transport);
                        $columns = [
                            [
                                'label' => '',
                                'render' => function ($row) use ($oldFromDelegateIds) {
                                    $checked = $oldFromDelegateIds->contains($row->id) ? 'checked' : '';
                                    return '<input type="checkbox" name="from_delegate_ids[]" value="' .
                                        $row->id .
                                        '" class="delegate-checkbox w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded" ' .
                                        $checked .
                                        ' />';
                                },
                            ],
                            ['label' => __db('title'), 'render' => fn($row) => $row->title->value ?? ''],
                            ['label' => __db('name'), 'render' => fn($row) => e($row->name_en ?? '-')],
                            ['label' => __db('designation'), 'render' => fn($row) => $row->designation_en ?? ''],
                            [
                                'label' => __db('internal_ranking'),
                                'render' => fn($row) => $row->internalRanking->value ?? '',
                            ],
                            ['label' => __db('gender'), 'render' => fn($row) => $row->gender->value ?? ''],
                        ];
                    @endphp
                    <x-reusable-table :columns="$columns" :data="$delegates" :is-raw="['label', 'render']" />
                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('interview_details') }}</h2>
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white grid grid-cols-4 gap-5 mt-6 mb-4">

                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('date_time') }}:</label>
                    <input type="datetime-local" class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                        name="date_time"
                        value="{{ old('date_time', $isEditMode ? \Carbon\Carbon::parse($interview->date_time)->format('Y-m-d\TH:i') : '') }}">
                    @error('date_time')
                        <div class="text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('interview_with') }}:</label>
                    @error('interview_type')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                    <div class="flex items-center gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="interview_type" value="delegation" @checked($oldInterviewType === 'delegation')
                                class="text-[#B68A35] focus:ring-[#B68A35]" onchange="toggleInterviewInput(this)">
                            <span class="text-gray-700">{{ __db('delegation') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="interview_type" value="other" @checked($oldInterviewType === 'other')
                                class="text-[#B68A35] focus:ring-[#B68A35]" onchange="toggleInterviewInput(this)">
                            <span class="text-gray-700">{{ __db('other') }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex col-span-2 items-end gap-3" id="delegation-input" @class(['hidden' => $oldInterviewType !== 'delegation'])>
                    <div class="w-full">
                        <label class="form-label block text-gray-700 font-semibold">{{ __db('interview_with') }}
                            ({{ __db('delegate_id') }}):</label>
                        <input type="text" id="delegation_code_input" name="interview_with_delegation_code"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300"
                            placeholder="{{ __db('enter_code_or_search') }}" value="{{ $oldToDelegationCode }}">
                        @error('interview_with_delegation_code')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" id="search-delegation-btn"
                        class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">
                        <svg class="pe-1 text-[#FFF]" width="25" height="25" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="w-[150px]">{{ __db('delegation_id') }}</span>
                    </button>
                </div>


                <div id="membersbox" class=" col-span-1" @class(['hidden' => $oldInterviewType !== 'delegation'])>
                    <label class="form-label block text-gray-700 font-medium">{{ __db('select') }}:</label>
                    <select name="to_delegate_id" class="p-3 rounded-lg w-full border text-sm" id="members-select">
                        @if ($isEditMode && $oldInterviewType === 'delegation' && !empty($toDelegationMembers))
                            <option value="" selected disabled>{{ __db('select_member') }}</option>
                            @foreach ($toDelegationMembers as $member)
                                <option value="{{ $member->id }}" @selected($oldToDelegateId == $member->id)>
                                    {{ $member->value_en ?? $member->name_ar }}
                                </option>
                            @endforeach
                        @else
                            <option value="" selected disabled>{{ __db('enter_delegation_code_first') }}
                            </option>
                        @endif
                    </select>
                    @error('to_delegate_id')
                        <div class="text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div id="other-input" class="hidden" @class(['hidden' => $oldInterviewType !== 'other'])>
                    <label class="form-label block mb-1 rounded-l-s text-gray-700 font-medium">{{ __db('members') }}:</label>
                    <select name="other_member_id" class="p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select') }}</option>
                        @foreach ($otherMembers as $member)
                            <option value="{{ $member->id }}" @selected(old('other_member_id', $isEditMode ? $interview->other_member_id : '') == $member->id)>
                                {{ $member->name_en ?? $member->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('other_member_id')
                        <div class="text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div id="statusbox">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('status') }}:</label>
                    <select name="status_id" class="p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select_status') }}</option>
                        @foreach (getDropdown('interview_status')->options as $status)
                            <option value="{{ $status->id }}" @selected(old('status_id', $isEditMode ? $interview->status_id : '') == $status->id)>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>

                    @error('status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="flex justify-start gap-5 items-center">
            <button type="submit" class="btn text-md border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12 px-6">
                {{ $isEditMode ? __db('update_and_exit') : __db('submit_and_exit') }}
            </button>
        </div>
    </form>


    <div id="default-modal4" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg w-full max-w-lg p-6">
            <h3 class="text-xl font-semibold mb-4">{{ __db('search_delegations') }}</h3>

            <div class="grid grid-cols-2 gap-4 mb-6">

                @php
                    $continentOptions = getDropDown('continents');
                @endphp
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('continents') }}:</label>
                    <select id="modal-continent" class="p-3 rounded-lg w-full border text-sm">
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
                    <select id="modal-country" class="p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select_country') }}</option>
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
            delegationMembers: '/mod-admin/delegations/members'
        };
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="interview_type"]:checked');
            if (checkedRadio) {
                toggleInterviewInput(checkedRadio);
            }
        });

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
            const continentSelect = document.getElementById('modal-continent');
            const countrySelect = document.getElementById('modal-country');

            let selectedDelegationId = null;
            let selectedDelegationCode = null;
            let selectedMembers = [];

            // Load countries when continent is selected
            continentSelect.addEventListener('change', function() {
                const continentId = this.value;
                countrySelect.innerHTML = '<option value="" selected disabled>{{ __db('select_country') }}</option>';
                
                if (continentId) {
                    fetch(`/mod-admin/get-countries?continent_ids=${continentId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(country => {
                            const option = document.createElement('option');
                            option.value = country.id;
                            option.textContent = country.name;
                            countrySelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        console.error('Failed to load countries');
                    });
                }
            });

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
                        console.log("data", data);

                        if (data.success && data.members) {
                            populateMembers(data.members);
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

                // Build query parameters
                let queryParams = [];
                if (continentId) queryParams.push(`continent_id=${continentId}`);
                if (countryId) queryParams.push(`country_id=${countryId}`);
                const queryString = queryParams.join('&');

                fetch(`${window.pageRoutes.delegationSearchByFilters}?${queryString}`, {
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
                                li.className = 'py-2 cursor-pointer hover:bg-gray-100';
                                li.textContent =
                                    `${delegation.code} - ${delegation.invitationFrom_value}`;
                                li.dataset.id = delegation.id;
                                li.addEventListener('click', function() {
                                    delegationsList.querySelectorAll('li').forEach(el =>
                                        el.classList.remove('bg-[#B68A35]',
                                            'text-white'));
                                    this.classList.add('bg-[#B68A35]', 'text-white');
                                    selectedDelegationId = delegation.id;
                                    selectedDelegationCode = delegation.code;

                                    modalSelectBtn.disabled = false;
                                    modalSelectBtn.classList.remove(
                                        'hidden');
                                });
                                delegationsList.appendChild(li);
                            });
                            modalResults.classList.remove('hidden');
                            toastr.success("{{ __db('delegations_fetched') }}");

                            modalSelectBtn.disabled = false;
                            modalSelectBtn.classList.remove(
                                'hidden');

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

                codeInput.value = selectedDelegationCode;

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
