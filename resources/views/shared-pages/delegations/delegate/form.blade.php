<div class="">
    @php
        $isEditMode = $delegate->exists;

        $existingTeamHead = $delegation->delegates
            ->where('team_head', true)
            ->when($isEditMode, fn($coll) => $coll->where('id', '!=', $delegate->id))
            ->first();
    @endphp

    <x-back-btn title="{{ $isEditMode ? __db('edit_delegate') : __db('add_delegate') }}"
        back-url="{{ url()->previous() ?: route('delegations.edit', $delegation->id) }}" />

    @if ($errors->any())
        <div class="p-4 my-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <ul class="mt-1.5 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
        action="{{ $isEditMode ? route('delegations.updateDelegate', [$delegation, $delegate]) : route('delegations.storeDelegate', $delegation) }}"
        data-ajax-form="true">
        @csrf
        @if ($isEditMode)
            @method('PUT')
        @endif

        @php
            $arrival = $delegate->delegateTransports->where('type', 'arrival')->first();
            $departure = $delegate->delegateTransports->where('type', 'departure')->first();
        @endphp

        <div class="delegate-row border bg-white p-6 rounded mb-2">
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title_ar') }} : <span class="text-red-600">*</span></label>
                    <input type="text" name="title_ar" value="{{ old('title_ar', $delegate->title_ar) }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_ar') }} : <span class="text-red-600">*</span></label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $delegate->name_ar) }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('designation_ar') }} :</label>
                    <input type="text" name="designation_ar"
                        value="{{ old('designation_ar', $delegate->designation_ar) }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title_en') }} : <span class="text-red-600">*</span></label>
                    <input type="text" name="title_en" value="{{ old('title_en', $delegate->title_en) }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300">
                </div>


                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_en') }} : <span class="text-red-600">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en', $delegate->name_en) }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300">
                </div>


                <div class="col-span-4">
                    <label class="form-label">{{ __db('designation_en') }} :</label>
                    <input type="text" name="designation_en"
                        value="{{ old('designation_en', $delegate->designation_en) }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300">
                </div>



                <div class="col-span-4">
                    <label class="form-label">{{ __db('gender') }}: <span class="text-red-600">*</span></label>
                    <select name="gender_id" required
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm">
                        <option value="" disabled>{{ __db('select_gender') }}</option>
                        @foreach (getDropDown('gender')->options as $option)
                            <option value="{{ $option->id }}" @if (old('gender_id', $delegate->gender_id) == $option->id) selected @endif>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('parent_id') }}:</label>
                    <select name="parent_id" class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm">
                        <option value="">{{ __db('select_parent') }}</option>
                        @foreach ($delegation->delegates as $parentDelegate)
                            @if ($parentDelegate->id !== $delegate->id)
                                <option value="{{ $parentDelegate->id }}"
                                    @if (old('parent_id', $delegate->parent_id) == $parentDelegate->id) selected @endif>
                                    {{ $parentDelegate?->getTranslation('title') . ' ' . $parentDelegate?->getTranslation('name') }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4" id="relationship-field">
                    <label class="form-label">{{ __db('relationship') }}:</label>
                    <select name="relationship_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm">
                        <option value="">{{ __db('select_relationship') }}</option>
                        @foreach (getDropDown('relationship')->options as $option)
                            <option value="{{ $option->id }}" @if (old('relationship_id', $delegate->relationship_id) == $option->id) selected @endif>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-span-4">
                    <label class="form-label">{{ __db('internal_ranking') }}:</label>
                    <select name="internal_ranking_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm">
                        <option value="">{{ __db('select_ranking') }}</option>
                        @foreach (getDropDown('internal_ranking')->options as $option)
                            <option value="{{ $option->id }}" @if (old('internal_ranking_id', $delegate->internal_ranking_id) == $option->id) selected @endif>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <div class="col-span-12">
                    <label class="form-label">{{ __db('note') }}:</label>
                    <textarea name="note" rows="3" placeholder="Type here..."
                        class="block p-2.5 w-full text-sm rounded-lg border border-neutral-300">{{ old('note', $delegate->note) }}</textarea>
                </div>
            </div>

            <div class="pt-6 mt-6 flex flex-wrap gap-8">
                @if (!$existingTeamHead)
                    <div class="flex items-center gap-3">
                        <input id="team-head" name="team_head" type="checkbox" value="1"
                            class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                            @if (old('team_head', $delegate->team_head)) checked @endif />
                        <label for="team-head" class="text-sm text-gray-700">{{ __db('team_head') }}</label>
                    </div>
                @endif
                <div class="flex items-center gap-3">
                    <input id="badge-printed" name="badge_printed" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                        @if (old('badge_printed', $delegate->badge_printed)) checked @endif>
                    <label for="badge-printed" class="text-sm text-gray-700">{{ __db('badge_printed') }}</label>
                </div>
                <div class="flex items-center gap-3">
                    <input id="accommodation" name="accommodation" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                        @if (old('accommodation', isset($delegate->accommodation) ? $delegate->accommodation : true)) checked @endif>
                    <label for="accommodation" class="text-sm text-gray-700">{{ __db('accommodation') }}</label>
                </div>
            </div>

            <hr class="border-neutral-200 my-6">

            @include('shared-pages.delegations.delegate.partials.transport_section', [
                'type' => 'arrival',
                'title' => __db('arrival'),
                'transport' => $arrival,
            ])

            @if ($arrival)
                <div class="mt-4 flex justify-end">
                    <button type="button" id="clear-arrival-btn"
                        class="btn !bg-red-600 text-white rounded-lg px-6 py-2.5">
                        {{ __db('clear_arrival_data') }}
                    </button>
                </div>
            @endif


            <hr class="my-6">

            @include('shared-pages.delegations.delegate.partials.transport_section', [
                'type' => 'departure',
                'title' => __db('departure'),
                'transport' => $departure,
            ])

            @if ($departure)
                <div class="mt-4 flex justify-end">
                    <button type="button" id="clear-departure-btn"
                        class="btn !bg-red-600 text-white rounded-lg px-6 py-2.5">
                        {{ __db('clear_departure_data') }}
                    </button>
                </div>
            @endif


            <div class="flex justify-start items-center mt-8">
                <button type="submit" class="btn !bg-[#B68A35] text-white rounded-lg px-8 py-3">
                    {{ $isEditMode ? __db('update_delegate') : __db('add_delegate') }}
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const setupTransportModeToggle = (sectionType) => {
                const transportSection = document.querySelector(`[data-transport-section="${sectionType}"]`);
                if (!transportSection) return;

                const modeRadios = transportSection.querySelectorAll(`input[name="${sectionType}[mode]"]`);

                const toggleFields = () => {
                    const selectedMode = transportSection.querySelector(
                        `input[name="${sectionType}[mode]"]:checked`).value;
                    const allModeFields = transportSection.querySelectorAll('[data-mode-fields]');

                    allModeFields.forEach(fieldSet => {
                        const applicableModes = fieldSet.getAttribute('data-mode-fields').split(
                            ' ');
                        fieldSet.style.display = applicableModes.includes(selectedMode) ? '' :
                            'none';
                    });
                };

                modeRadios.forEach(radio => radio.addEventListener('change', toggleFields));
                toggleFields();
            };

            setupTransportModeToggle('arrival');
            setupTransportModeToggle('departure');


            const parentSelect = document.querySelector('select[name="parent_id"]');
            const relationshipField = document.getElementById('relationship-field');
            const relationshipSelect = document.querySelector('select[name="relationship_id"]');

            const toggleRelationshipField = () => {
                const parentSelected = parentSelect.value !== '';

                if (parentSelected) {
                    relationshipField.style.display = '';
                } else {
                    relationshipField.style.display = 'none';
                    relationshipSelect.value = '';
                    if ($(relationshipSelect).hasClass('select2-hidden-accessible')) {
                        $(relationshipSelect).val('').trigger('change');
                    }
                }
            };

            toggleRelationshipField();

            parentSelect.addEventListener('change', toggleRelationshipField);

            if (typeof $ !== 'undefined' && $.fn.select2) {
                $(parentSelect).on('change', toggleRelationshipField);
            }

            // Clear arrival data button
            const clearArrivalBtn = document.getElementById('clear-arrival-btn');
            if (clearArrivalBtn) {
                clearArrivalBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to clear the arrival data?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, clear it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('delegations.clearArrivalData', [$delegation, $delegate]) }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    },
                                    body: JSON.stringify({
                                        _token: document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Cleared!',
                                            text: data.message ||
                                                'Arrival data has been cleared.',
                                            icon: 'success'
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: data.message ||
                                                'An error occurred while clearing arrival data.',
                                            icon: 'error'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'An error occurred while processing your request.',
                                        icon: 'error'
                                    });
                                });
                        }
                    });
                });
            }

            // Clear departure data button
            const clearDepartureBtn = document.getElementById('clear-departure-btn');
            if (clearDepartureBtn) {
                clearDepartureBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to clear the departure data?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, clear it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Make AJAX request to clear departure data
                            fetch("{{ route('delegations.clearDepartureData', [$delegation, $delegate]) }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    },
                                    body: JSON.stringify({
                                        _token: document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Cleared!',
                                            text: data.message ||
                                                'Departure data has been cleared.',
                                            icon: 'success'
                                        }).then(() => {
                                            // Reload the page after successful deletion
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: data.message ||
                                                'An error occurred while clearing departure data.',
                                            icon: 'error'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'An error occurred while processing your request.',
                                        icon: 'error'
                                    });
                                });
                        }
                    });
                });
            }

        });
    </script>
@endpush
