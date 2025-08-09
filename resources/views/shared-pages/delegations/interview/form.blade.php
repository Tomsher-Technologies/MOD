<div>
    @php
        $isEditMode = $interview->exists;
        $title = $isEditMode ? __db('edit_interview') : __db('add_interview');
    @endphp

    <x-back-btn :title="$title" back-url="{{ route('delegations.show', $delegation->id) }}" />

    {{-- Delegation Info Table (from your old file) --}}
    @php
        $delegationInfoColumns = [
            ['label' => __db('delegation_id'), 'render' => fn($row) => $row->code ?? ''],
            ['label' => __db('invitation_from'), 'render' => fn($row) => $row->invitationFrom->value ?? ''],
            ['label' => __db('continent'), 'render' => fn($row) => $row->continent->value ?? ''],
            ['label' => __db('country'), 'render' => fn($row) => $row->country->value ?? ''],
            ['label' => __db('invitation_status'), 'render' => fn($row) => $row->invitationStatus->value ?? ''],
            ['label' => __db('participation_status'), 'render' => fn($row) => $row->participationStatus->value ?? ''],
        ];
    @endphp
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-4">
        <div class="xl:col-span-12">
            <div class="bg-white h-full w-full rounded-lg border-0 p-10">
                <x-reusable-table :columns="$delegationInfoColumns" :data="[$delegation]" />
            </div>
        </div>
    </div>
    <hr class="mx-6 border-neutral-200 h-10">

    {{-- 2. Main Form with AJAX and Dynamic Action/Method --}}
    <form method="POST"
        action="{{ $isEditMode ? route('interviews.update', ['delegation' => $delegation, 'interview' => $interview]) : route('interviews.store', $delegation) }}"
        data-ajax-form="true">
        @csrf
        @if ($isEditMode)
            @method('PUT')
        @endif

        {{-- Prepare existing data for populating form fields --}}
        @php
            $existingFromDelegateIds = old(
                'from_delegate_ids',
                $isEditMode ? $interview->fromMembers()->pluck('member_id')->all() : [],
            );
            $existingToDelegateId = old(
                'to_delegate_id',
                $isEditMode ? $interview->toMembers()->value('member_id') : null,
            );
            $interviewType = old(
                'interview_type',
                $isEditMode ? ($interview->type === 'del_del' ? 'delegation' : 'other') : 'delegation',
            );
            $interviewWithDelegationCode = old(
                'interview_with_delegation_code',
                $isEditMode && $interview->interviewWithDelegation ? $interview->interviewWithDelegation->code : '',
            );
        @endphp

        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('delegates') }} (Interview Participants)</h2>
        @error('from_delegate_ids')
            <div class="text-red-600 my-2">{{ $message }}</div>
        @enderror

        {{-- Delegates Table (from your old file, adapted for edit mode) --}}
        @php
            $delegateColumns = [
                [
                    'label' => '<input type="checkbox" id="select-all" />',
                    'render' => function ($row) use ($existingFromDelegateIds) {
                        $checked = in_array($row->id, $existingFromDelegateIds) ? 'checked' : '';
                        return '<input type="checkbox" name="from_delegate_ids[]" value="' .
                            $row->id .
                            '" class="delegate-checkbox w-4 h-4 !accent-[#B68A35] !border-[#B68A35] !focus:ring-[#B68A35] rounded" ' .
                            $checked .
                            ' />';
                    },
                ],
                ['label' => __db('sl_no'), 'render' => fn($row, $key) => $row->code],
                ['label' => __db('title'), 'render' => fn($row) => $row->title->value ?? ''],
                [
                    'label' => __db('name'),
                    'render' => function ($row) {
                        $badge = $row->team_head
                            ? '<span class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span> '
                            : '';
                        return $badge . '<div class="block">' . e($row->name_en ?? '-') . '</div>';
                    },
                ],
                ['label' => __db('designation'), 'render' => fn($row) => $row->designation_en ?? ''],
                ['label' => __db('internal_ranking'), 'render' => fn($row) => $row->internalRanking->value ?? ''],
                ['label' => __db('gender'), 'render' => fn($row) => $row->gender->value ?? ''],
            ];
        @endphp
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 h-full">
            <div class="xl:col-span-12 h-full">
                <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                    <x-reusable-table :columns="$delegateColumns" :data="$delegation->delegates" />
                </div>
            </div>
        </div>

        <hr class="mx-6 border-neutral-200 h-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('add_interview_requests') }}</h2>

        {{-- Interview Details Form (from your old file, adapted for edit mode) --}}
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white grid grid-cols-4 gap-5 mt-6 mb-4">
                <div>
                    <label class="form-label">Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                        name="date_time"
                        value="{{ old('date_time', $interview->date_time ? \Carbon\Carbon::parse($interview->date_time)->format('Y-m-d\TH:i') : '') }}"
                        required>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Interview With <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="interview_type" value="delegation"
                                class="text-[#B68A35] focus:ring-[#B68A35]" onchange="toggleInterviewInput(this)"
                                @if ($interviewType === 'delegation') checked @endif>
                            <span class="text-gray-700">{{ __db('delegation') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="interview_type" value="other"
                                class="text-[#B68A35] focus:ring-[#B68A35]" onchange="toggleInterviewInput(this)"
                                @if ($interviewType === 'other') checked @endif>
                            <span class="text-gray-700">{{ __db('other') }}</span>
                        </label>
                    </div>
                </div>

                {{-- Delegation Type Inputs --}}
                <div class="flex col-span-2 items-end gap-3" id="delegation-input">
                    <div class="w-full">
                        <label class="form-label">Interview With (Delegation Code):</label>
                        <input type="text" id="delegation_code_input" name="interview_with_delegation_code"
                            class="p-3 rounded-lg w-full border text-sm" placeholder="{{ __db('delegate_id') }}"
                            value="{{ $interviewWithDelegationCode }}">
                    </div>
                    <button type="button" id="search-delegation-btn"
                        class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">
                        <svg class="pe-1 text-[#FFF]" width="25" height="25" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="w-[150px]">{{ __db('search_delegation_id') }}</span>
                    </button>
                </div>
                <div id="membersbox">
                    <label class="form-label">Interview With (Member):</label>
                    {{-- Name changed to to_delegate_id to match controller --}}
                    <select name="to_delegate_id" class="p-3 rounded-lg w-full border text-sm" id="members-select">
                        <option value="">Select a delegation code first...</option>
                    </select>
                </div>

                {{-- Other Type Input --}}
                <div id="other-input" class="hidden col-span-1">
                    <label class="form-label">Select Other Member:</label>
                    <select name="other_member_id" class="p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select_other_member') }}</option>
                        @foreach ($otherMembers as $member)
                            <option value="{{ $member->id }}" @if (old('other_member_id', $interview->other_member_id) == $member->id) selected @endif>
                                {{ $member->name_en ?? $member->name_ar }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Common Fields --}}
                <div id="statusbox">
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    {{-- Name changed to status_id to match controller --}}
                    <select name="status_id" class="p-3 rounded-lg w-full border text-sm" required>
                        <option value="" selected disabled>{{ __db('select_status') }}</option>
                        @foreach (getDropdown('interview_status')->options as $status)
                            <option value="{{ $status->id }}" @if (old('status_id', $interview->status_id) == $status->id) selected @endif>
                                {{ $status->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" rows="3">{{ old('comment', $interview->comment) }}</textarea>
                </div>
            </div>
        </div>

        {{-- 3. Simplified Single Submit Button for AJAX Flow --}}
        <div class="flex justify-start">
            <button type="submit" class="btn text-md !bg-[#B68A35] text-white rounded-lg px-8 py-3">
                {{ $isEditMode ? __db('update_interview') : __db('submit_interview') }}
            </button>
        </div>
    </form>

    {{-- Search Modal (from your old file) --}}
    <div id="default-modal4" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black bg-opacity-50 p-4">
        {{-- ... Your entire modal HTML ... --}}
        <div class="bg-white rounded-lg w-full max-w-lg p-6">
            <h3 class="text-xl font-semibold mb-4">{{ __db('search_delegations') }}</h3>
            <div class="grid grid-cols-2 gap-4 mb-6">
                @php
                    $continentOptions = getDropDown('continents');
                    $countryOptions = getDropDown('country');
                @endphp
                <div>
                    <label class="form-label">Continents:</label>
                    <select id="modal-continent" class="p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select_continent') }}</option>
                        @if ($continentOptions)
                            @foreach ($continentOptions->options as $option)
                                <option value="{{ $option->id }}">{{ $option->value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="form-label">Country:</label>
                    <select id="modal-country" class="p-3 rounded-lg w-full border text-sm">
                        <option value="" selected disabled>{{ __db('select_country') }}</option>
                        @if ($countryOptions)
                            @foreach ($countryOptions->options as $option)
                                <option value="{{ $option->id }}">{{ $option->value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div id="modal-search-results"
                class="mb-6 max-h-60 overflow-auto border border-gray-300 rounded p-3 hidden">
                <ul id="modal-delegations-list" class="divide-y divide-gray-300"></ul>
            </div>
            <div class="flex justify-end gap-3">
                <button id="modal-search-btn"
                    class="btn !bg-[#B68A35] !text-white rounded-lg px-5 py-2 disabled:opacity-50">Search</button>
                <button id="modal-select-btn"
                    class="btn !bg-[#B68A35] !text-white rounded-lg px-5 py-2 disabled:opacity-50 hidden">Select</button>
                <button id="modal-close-btn"
                    class="btn border !border-[#B68A35] !text-[#B68A35] rounded-lg px-5 py-2">Cancel</button>
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
            delegationSearchByCode: @json(getRouteForPage('delegation.searchByCode')),
            delegationSearchByFilters: @json(getRouteForPage('delegation.search')),
            delegationMembers: @json(getRouteForPage('delegation.members'))
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
