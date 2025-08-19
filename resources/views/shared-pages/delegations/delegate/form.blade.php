<div class="">
    @php
        $isEditMode = $delegate->exists;
    @endphp

    <x-back-btn title="{{ $isEditMode ? __db('edit_delegate') : __db('add_delegate') }}"
        back-url="{{ route('delegations.edit', $delegation->id) }}" />

    @if ($errors->any())
        <div class="p-4 my-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <span class="font-medium">Please fix the errors below:</span>
            <ul class="mt-1.5 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
        action="{{ $isEditMode ? route('delegations.updateDelegate', [$delegation, $delegate]) : route('delegations.storeDelegate', $delegation) }}" data-ajax-form="true">
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
                    <label class="form-label">{{ __db('title') }}:</label>
                    <select name="title_id" class="p-3 rounded-lg w-full text-sm border border-neutral-300">
                        <option value="" disabled>{{ __db('select_title') }}</option>
                        @foreach (getDropDown('title')->options as $option)
                            <option value="{{ $option->id }}" @if (old('title_id', $delegate->title_id) == $option->id) selected @endif>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_en') }} : <span class="text-red-600">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en', $delegate->name_en) }}" required
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_ar') }} : <span class="text-red-600">*</span></label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $delegate->name_ar) }}" required
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
                    <select name="gender_id" required class="p-3 rounded-lg w-full border border-neutral-300 text-sm">
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
                    <select name="parent_id" class="p-3 rounded-lg w-full border border-neutral-300 text-sm">
                        <option value="">{{ __db('select_parent') }}</option>
                        @foreach ($delegation->delegates as $parentDelegate)
                            @if ($parentDelegate->id !== $delegate->id)
                                <option value="{{ $parentDelegate->id }}"
                                    @if (old('parent_id', $delegate->parent_id) == $parentDelegate->id) selected @endif>
                                    {{ $parentDelegate->name_en }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('relationship') }}:</label>
                    <select name="relationship_id" class="p-3 rounded-lg w-full border border-neutral-300 text-sm">
                        <option value="">{{ __db('select_relationship') }}</option>
                        @foreach (getDropDown('relationship')->options as $option)
                            <option value="{{ $option->id }}" @if (old('relationship_id', $delegate->relationship_id) == $option->id) selected @endif>
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
                <div class="flex items-center gap-3">
                    <input id="team-head" name="team_head" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                        @if (old('team_head', $delegate->team_head)) checked @endif />
                    <label for="team-head" class="text-sm text-gray-700">{{ __db('team_head') }}</label>
                </div>
                <div class="flex items-center gap-3">
                    <input id="badge-printed" name="badge_printed" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                        @if (old('badge_printed', $delegate->badge_printed)) checked @endif>
                    <label for="badge-printed" class="text-sm text-gray-700">{{ __db('badge_printed') }}</label>
                </div>
                <div class="flex items-center gap-3">
                    <input id="accommodation" name="accommodation" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                        @if (old('accommodation', $delegate->accommodation)) checked @endif>
                    <label for="accommodation" class="text-sm text-gray-700">{{ __db('accommodation') }}</label>
                </div>
            </div>

            <hr class="border-neutral-200 my-6">

            @include('shared-pages.delegations.delegate.partials.transport_section', [
                'type' => 'arrival',
                'title' => __db('arrival'),
                'transport' => $arrival,
            ])

            <hr class="my-6">

            @include('shared-pages.delegations.delegate.partials.transport_section', [
                'type' => 'departure',
                'title' => __db('departure'),
                'transport' => $departure,
            ])

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
        });
    </script>
@endpush
