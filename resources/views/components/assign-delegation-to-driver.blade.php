
<div class="bg-white h-full w-full rounded-lg border-0 p-6">
    @props(['driver'])

    @if ($errors->any())
        <div class="mb-6 p-4 border border-red-400 bg-red-100 text-red-700 rounded">
            <h4 class="font-semibold mb-2">{{ __db('please_fix_the_following_errors') }}</h4>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('drivers.assign', $driver->id) }}" method="POST">
        @csrf
        <input type="hidden" name="action" id="actionInput" value="">
        <input type="hidden" name="start_date" id="startDateInput" value="">


        <div class="p-4 md:p-5 space-y-6 px-0">
            <div class="grid  grid-cols-12 gap-2 items-end">
                <div class="col-span-5">
                    <div class="flex col-span-2 items-end gap-3 max-w-2xl" id="delegation-input">
                        <div class="w-full">
                            <div class="flex justify-between ">
                                <label
                                    class="form-label block text-gray-700 font-semibold">{{ __db('delegation') . ' ' . __db('id') }}:</label>
                            </div>
                            <input type="text" id="delegation_code"
                                class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                placeholder="{{ __db('enter') . __db('delegation') . ' ' . __db('id') }}" />
                        </div>
                    </div>
                </div>
                <div class="col-span-1 text-center">
                    <span class="mb-3 font-semibold"> {{ __db('or') }} </span>
                </div>
                <div class="col-span-6">
                    <div class="grid grid-cols-2 gap-5 items-end">
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('country') }}:</label>
                            <select id="country_id" class="select2 p-3 rounded-lg w-full border text-sm">
                                <option value="" selected="">{{ __db('All') }}</option>
                                @foreach (getAllCountries() as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="searchBtn"
                            class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 flex items-center justify-center gap-2">
                            <svg class="pe-1 text-[#FFF]" width="25" height="25" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                            </svg>
                            <span
                                class="w-[150px]">{{ __db('search') . ' ' . __db('delegation') . ' ' . __db('id') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mx-6">
        <div id="delegationTable">
            <hr class="mx-6 border-neutral-200 h-5" />
            <!-- Main Table -->
            <table class="table-auto mb-0 !border-[#F9F7ED] w-full border border-collapse">
                <thead>
                    <tr>
                        <th class="p-3 !bg-[#B68A35] text-start text-white"></th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('delegation') . ' ' . __db('id') }}
                        </th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('continent') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('country') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white">{{ __db('team_head') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
        <div class="flex items-center p-4 md:p-5 border-gray-200 rounded-b px-0 pb-0">
            <button type="button" id="assignBtn"
                class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 hidden">{{ __db('assign') }}</button>

        </div>
    </form>
</div>

<div id="delegationDetailsModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full !max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">{{ __db('delegation_details') }}</h2>
                <button id="closeDelegationModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div id="delegationDetailsContent">
            </div>
        </div>
    </div>
</div>

<div id="assignConfirmationModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-4">{{ __db('already_assigned') }}</h2>
        <p class="mb-6">{{ __db('replace_or_reassign') }}?</p>

        <div id="reassignDateWrapper" class="hidden mb-6">
            <label for="reassignDate" class="block text-gray-700 font-medium mb-2">
                {{ __db('start_date_for_delegation') }}
            </label>
            <input type="date" id="reassignDate"
                class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" />
            <p class="text-red-500 text-sm mt-1 hidden" id="dateError">
                {{ __db('start_date_required') }}
            </p>
        </div>

        <div class="flex justify-end gap-4">
            <button type="button" id="cancelModal"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                {{ __db('cancel') }}
            </button>
            <button type="button" id="reassignBtn"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                {{ __db('reassign') }}
            </button>
            <button type="button" id="replaceBtn"
                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                {{ __db('replace') }}
            </button>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.getElementById('searchBtn');
        const delegationCodeInput = document.getElementById('delegation_code');
        const countryIdInput = document.getElementById('country_id');
        const delegationTableBody = document.querySelector('#delegationTable tbody');
        const assignBtn = document.getElementById('assignBtn');

        const pageRoutes = {
            delegationSearchByCode: "{{ route('delegations.searchByCode') }}",
            delegationSearch: "{{ route('delegations.search') }}",
        };

        searchBtn.addEventListener('click', function() {
            const delegationCode = delegationCodeInput.value.trim();
            const countryId = countryIdInput.value;

            let url = new URL(pageRoutes.delegationSearch, window.location.origin);
            url.searchParams.append('delegates', '1');
            url.searchParams.append('driver_id', "{{ $driver->id }}");

            if (delegationCode) {
                url = new URL(pageRoutes.delegationSearchByCode, window.location.origin);
                url.searchParams.append('code', delegationCode);
                url.searchParams.append('delegates', '1');
            } else if (countryId) {
                url.searchParams.append('country_id', countryId);
                url.searchParams.append('delegates', '1');
            }

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    delegationTableBody.innerHTML = '';
                    if (data.success) {
                        let delegations = data.delegations || (data.delegation ? [data.delegation] :
                            []);
                        console.log("delegations", delegations);

                        if (delegations.length > 0) {
                            delegations.forEach(delegation => {
                                const row = `
                                    <tr class="text-sm align-middle">
                                        <td class="px-4 py-2 border border-gray-200">
                                            <input type="radio" name="delegation_id" value="${delegation.id}"
                                                class="w-4 h-4 !accent-[#B68A35]"  />
                                        </td>
                                        <td class="px-4 py-2 border border-gray-200">
                                            <a href="javascript:void(0)" class="text-[#B68A35] hover:underline delegation-details-link" 
                                               data-delegation-id="${delegation.id}"
                                               data-delegation-code="${delegation.code}">
                                                ${delegation.code}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border border-gray-200">${delegation.continent?.value_ar || delegation.continent?.value || ''}</td>
                                        <td class="px-4 py-2 border border-gray-200">${delegation.country?.name_ar || delegation.country?.name || ''}</td>
                                        <td class="px-4 py-2 border border-gray-200">${delegation.delegates.find((delegate) => delegate.team_head === true )?.name_en || ''}</td>
                                    </tr>
                                `;
                                delegationTableBody.innerHTML += row;
                            });

                            assignBtn.classList.remove('hidden');
                        } else {
                            delegationTableBody.innerHTML =
                                '<tr><td colspan="5" class="text-center py-4">{{ __db('no_delegations_found') }}</td></tr>';
                        }
                    } else {
                        delegationTableBody.innerHTML =
                            `<tr><td colspan="5" class="text-center py-4">${data.message || '{{ __db('no_delegations_found') }}'}</td></tr>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    delegationTableBody.innerHTML =
                        '<tr><td colspan="5" class="text-center py-4">{{ __db('error') }}</td></tr>';
                });
        });

        const delegationModal = document.getElementById('delegationDetailsModal');
        const closeDelegationModal = document.getElementById('closeDelegationModal');
        const delegationDetailsContent = document.getElementById('delegationDetailsContent');

        closeDelegationModal.addEventListener('click', function() {
            delegationModal.classList.add('hidden');
        });

        delegationModal.addEventListener('click', function(e) {
            if (e.target === delegationModal) {
                delegationModal.classList.add('hidden');
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delegation-details-link') || e.target.closest(
                    '.delegation-details-link')) {
                const link = e.target.classList.contains('delegation-details-link') ? e.target : e
                    .target.closest('.delegation-details-link');
                const delegationId = link.getAttribute('data-delegation-id');
                const delegationCode = link.getAttribute('data-delegation-code');

                delegationDetailsContent.innerHTML =
                    '<div class="text-center py-4">{{ __db('loading') }}...</div>';
                delegationModal.classList.remove('hidden');

                fetch(`/mod-events/delegations/${delegationId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            let detailsHtml = `
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">{{ __db('delegation_information') }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="font-medium">{{ __db('delegation') }} ID:</p>
                                        <p>${data.delegation.code}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ __db('country') }}:</p>
                                        <p>${data.delegation.country?.name_ar || data.delegation.country?.name || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ __db('continent') }}:</p>
                                        <p>${data.delegation.continent?.value_ar || data.delegation.continent?.value || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ __db('invitation_from') }}:</p>
                                        <p>${data.delegation.invitation_from?.value_ar || data.delegation.invitation_from?.value || '-'}</p>
                                    </div>
                                     <div>
                                        <p class="font-medium">{{ __db('invitation_status') }}:</p>
                                        <p>${data.delegation.invitation_status?.value_ar || data.delegation.invitation_status?.value || '-'}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold mb-3">{{ __db('delegates') }}</h3>
                                <div class="overflow-x-auto">
                                    <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('name_en') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('name_ar') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('internal_ranking') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('gender') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('parent') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('relationship') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('badge_printed') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('participation_status') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('designation') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('team_head') }}</th>
                                                <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('accommodation') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        `;

                            if (data.delegation.delegates && data.delegation.delegates.length > 0) {
                                data.delegation.delegates.forEach(delegate => {
                                    detailsHtml += `
                                     <tr>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${delegate?.name_en || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${ delegate?.name_ar || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${ delegate?.internal_ranking?.value_ar || delegate?.internal_ranking?.value || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${ delegate?.gender?.value || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${ delegate?.parent?.name_ar || delegate?.parent?.name_en || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${ delegate?.relationship_value_ar || delegate?.relationship_value || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${ delegate?.badge_printed || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${ delegate?.participation_status || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${delegate?.designation_ar || delegate?.designation_en || '-'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${delegate?.team_head ? '{{ __db('yes') }}' : '{{ __db('no') }}'}</td>
                                        <td class="px-4 py-2 border border-gray-200 text-center">${delegate?.accommodation ? '{{ __db('yes') }}' : '{{ __db('no') }}'}</td>
                                    </tr>
                                `;
                                });
                            } else {
                                detailsHtml += `
                                <tr>
                                    <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">{{ __db('no_delegates_found') }}</td>
                                </tr>
                            `;
                            }

                            detailsHtml += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;

                            delegationDetailsContent.innerHTML = detailsHtml;
                        } else {
                            delegationDetailsContent.innerHTML =
                                '<div class="text-center py-4 text-red-500">{{ __db('failed_to_load_delegation_details') }}</div>';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        delegationDetailsContent.innerHTML =
                            '<div class="text-center py-4 text-red-500">{{ __db('error_loading_delegation_details') }}</div>';
                    });
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const assignBtn = document.getElementById('assignBtn');
        const actionInput = document.getElementById('actionInput');
        const startDateInput = document.getElementById('startDateInput');
        const form = assignBtn.closest('form');

        const hasAssignment = @json($driver->delegations()->wherePivot('status', 1)->exists());

        assignBtn.addEventListener('click', function() {
            if (hasAssignment) {
                Swal.fire({
                    title: '{{ __db('driver_already_has_assignment') }}',
                    text: '{{ __db('reassign_or_replace_assignment') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: '{{ __db('reassign') }}',
                    denyButtonText: '{{ __db('replace') }}',
                    cancelButtonText: '{{ __db('cancel') }}',
                    customClass: {
                        confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-center text-white bg-[#B68A35] hover:bg-[#A87C27]',
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: '{{ __db('start_date_for_reassignment') }}',
                            input: 'date',
                            inputLabel: '{{ __db('start_date') }}',
                            inputPlaceholder: '{{ __db('select_date') }}',
                            inputValidator: (value) => {
                                if (!value)
                                    return '{{ __db('start_date_required') }}';
                            },
                            showCancelButton: true,
                            confirmButtonText: '{{ __db('submit') }}',
                            cancelButtonText: '{{ __db('cancel') }}',
                            customClass: {
                                confirmButton: 'justify-center inline-flex items-center px-4 py-3 text-center text-white bg-[#B68A35] hover:bg-[#A87C27]',
                            },
                        }).then((dateResult) => {
                            if (dateResult.isConfirmed) {
                                actionInput.value = 'reassign';
                                startDateInput.value = dateResult.value;
                                form.submit();
                            }
                        });
                    } else if (result.isDenied) {
                        actionInput.value = 'replace';
                        startDateInput.value = '';
                        form.submit();
                    }
                });
            } else {
                actionInput.value = 'reassign';
                form.submit();
            }
        });
    });
</script>
