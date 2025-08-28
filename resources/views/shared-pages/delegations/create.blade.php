<div>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('add_delegation') }}</h2>
        <a href="{{ route('delegations.index') }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

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

    <form id="create-delegation-form" action="{{ route('delegations.store') ?? '#' }}" method="POST"
        autocomplete="off" enctype="multipart/form-data" class="bg-white h-full w-full rounded-lg border-0 p-6 mb-10">
        @csrf
        <div>

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-3">
                    <label class="form-label">{{ __db('code') }}:</label>
                    <input type="text" name="code" value="{{ old('code', 'Auto-Generated') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0 bg-gray-200"
                        readonly />
                </div>

                @php
                    $departmentOptions = getDropDown('departments');
                    $continentOptions = getDropDown('continents');
                    $countryOptions = getDropDown('country');
                    $invitationStatusOptions = getDropDown('invitation_status');
                    $participationStatusOptions = getDropDown('participation_status');
                @endphp

                <div class="col-span-3">
                    <label class="form-label">{{ __db('invitation_from') }}:</label>

                    <select name="invitation_from_id"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select_invitation_from') }}</option>
                        @if ($departmentOptions)
                            @foreach ($departmentOptions->options as $option)
                                <option value="{{ $option->id }}"
                                    {{ old('invitation_from_id', request('invitation_from_id')) == $option->id ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                    @error('invitation_from_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('continent') }}:</label>
                    <select name="continent_id" id="continent-select"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select_continent') }}</option>
                        @if ($continentOptions)
                            @foreach ($continentOptions->options as $option)
                                <option value="{{ $option->id }}"
                                    {{ old('continent_id', request('continent_id')) == $option->id ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                    @error('continent_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('country') }}:</label>
                    <select name="country_id" id="country-select"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select_country') }}</option>
                        @foreach (getAllCountries() as $option)
                            <option value="{{ $option->id }}"
                                {{ old('country_id', request('country_id')) == $option->id ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('country_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('invitation_status') }}:</label>
                    <select name="invitation_status_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select_invitation_status') }}</option>
                        @if ($invitationStatusOptions)
                            @foreach ($invitationStatusOptions->options as $option)
                                <option value="{{ $option->id }}"
                                    {{ old('invitation_status_id', request('invitation_status_id')) == $option->id ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                    @error('invitation_status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('participation_status') }}:</label>
                    <select name="participation_status_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select_participation_status') }}</option>
                        @if ($participationStatusOptions)
                            @foreach ($participationStatusOptions->options as $option)
                                <option value="{{ $option->id }}"
                                    {{ old('participation_status_id', request('participation_status_id')) == $option->id ? 'selected' : '' }}>
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                    @error('participation_status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 grid grid-cols-12 gap-5">
                    <div class="col-span-6">
                        <label class="form-label">{{ __db('note_1') }}:</label>
                        <textarea id="message" rows="4" name="note1"
                            class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white">{{ old('note1', '') }}</textarea>
                        @error('note1')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-6">
                        <label class="form-label">{{ __db('note_2') }}:</label>
                        <textarea id="message" rows="4" name="note2"
                            class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white">{{ old('note2', '') }}</textarea>

                        @error('note2')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <span class="col-span-12 border-t border-neutral-200 pt-8 mt-8">
                    <h4 class="text-lg font-semibold">{{ __db('attachments') }}</h4>
                </span>

                @php
                    $attachmentTitleDropdown = getDropDown('attachment_title');

                    $attachmentTitleOptionsHtml = '';
                    if ($attachmentTitleDropdown && $attachmentTitleDropdown->options) {
                        foreach ($attachmentTitleDropdown->options as $option) {
                            $attachmentTitleOptionsHtml .=
                                '<option value="' . $option->id . '">' . e($option->value) . '</option>';
                        }
                    }

                    $attachmentsData = old('attachments')
                        ? array_map(function ($att) {
                            return [
                                'title_id' => $att['title_id'] ?? '',
                                'file' => null,
                                'document_date' => $att['document_date'] ?? '',
                            ];
                        }, old('attachments'))
                        : [];
                @endphp


                <div class="col-span-12" x-data="attachmentsComponent()">

                    <div id="attachment-container">
                        <template x-for="(attachment, index) in attachments" :key="index">
                            <div class="grid grid-cols-12 gap-5 mb-2 attachment-row">
                                <div class="col-span-3">
                                    <label class="form-label">{{ __db('title') }}</label>
                                    <select :name="`attachments[${index}][title_id]`"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                        x-model="attachment.title_id">
                                        <option value="">{{ __db('select_title') }}</option>
                                        {!! $attachmentTitleOptionsHtml !!}
                                    </select>
                                    <span class="text-red-600"
                                        x-text="window.attachmentsFieldErrors?.[`attachments.${index}.title_id`]?.[0] ?? ''"></span>
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __db('file') }}</label>
                                    <input
                                        class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                                        type="file" :name="`attachments[${index}][file]`"
                                        @change="e => attachment.file = e.target.files[0]">
                                    <span class="text-red-600"
                                        x-text="window.attachmentsFieldErrors?.[`attachments.${index}.file`]?.[0] ?? ''"></span>
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __db('document_date') }}</label>
                                    <input type="date" :name="`attachments[${index}][document_date]`"
                                        class="w-full border border-gray-300 text-sm rounded-lg px-3 py-3"
                                        x-model="attachment.document_date">
                                    <span class="text-red-600"
                                        x-text="window.attachmentsFieldErrors?.[`attachments.${index}.document_date`]?.[0] ?? ''"></span>
                                </div>

                                <div class="col-span-3 flex items-center">
                                    <button type="button"
                                        class="delete-row bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                        @click="removeAttachment(index)"
                                        x-show="attachments.length > 0">{{ __db('delete') ?: 'Delete' }}</button>
                                </div>
                            </div>
                        </template>

                        <div class="col-span-12 mb-10">
                            <button type="button" id="add-attachment-btn"
                                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3"
                                @click="attachments.push({title_id:'', file:null, document_date:''})">
                                <svg class="w-6 h-6 text-white me-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 12h14m-7 7V5" />
                                </svg>
                                <span>{{ __db('add_attachments') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <hr class="mx-6 border-neutral-200 h-10">

        @php
            function buildOptionsHtml($items)
            {
                $html = '';
                foreach ($items as $item) {
                    if (is_object($item)) {
                        $value = $item->id;
                        $label = $item->value;
                    } else {
                        $value = $label = $item;
                    }
                    $html .= '<option value="' . e($value) . '">' . e($label) . '</option>';
                }
                return $html;
            }

            $genderDropdown = getDropDown('gender');
            $genderOptions = $genderDropdown?->options ?? collect();
            $genderOptionsHtml = buildOptionsHtml($genderOptions);

            $relationshipDropdown = getDropDown('relationship');
            $relationshipOptions = $relationshipDropdown?->options ?? collect();
            $relationshipOptionsHtml = buildOptionsHtml($relationshipOptions);

            $titleDropdown = getDropDown('title');
            $titleOptions = $titleDropdown?->options ?? collect();
            $titleOptionsHtml = buildOptionsHtml($titleOptions);

            $internalRankingDropdown = getDropDown('internal_ranking');
            $internalRankingOptions = $internalRankingDropdown?->options ?? collect();
            $internalRankingOptionsHtml = buildOptionsHtml($internalRankingOptions);

            $delegatesData = old('delegates')
                ? array_values(
                    array_map(
                        function ($d, $idx) {
                            return [
                                'tmp_id' => $d['tmp_id'] ?? $idx + 1, // Ensure tmp_id is always set
                                'title_id' => $d['title_id'] ?? '',
                                'name_ar' => $d['name_ar'] ?? '',
                                'name_en' => $d['name_en'] ?? '',
                                'designation_en' => $d['designation_en'] ?? '',
                                'designation_ar' => $d['designation_ar'] ?? '',
                                'gender_id' => $d['gender_id'] ?? '',
                                'parent_id' => $d['parent_id'] ?? '',
                                'relationship' => $d['relationship'] ?? '',
                                'internal_ranking_id' => $d['internal_ranking_id'] ?? '',
                                'note' => $d['note'] ?? '',
                                'team_head' => !empty($d['team_head']),
                                'badge_printed' => !empty($d['badge_printed']),
                            ];
                        },
                        old('delegates'),
                        array_keys(old('delegates')),
                    ),
                )
                : [];
        @endphp

        <div class="space-y-4" x-data="delegateComponent()">

            <div id="delegate-container">
                <template x-for="(delegate, index) in delegates" :key="`delegate-${delegate.tmp_id}`">
                    <div>
                        <input type="hidden" :name="`delegates[${index}][tmp_id]`" :value="delegate.tmp_id" />

                        <div class="delegate-row border rounded p-4 grid grid-cols-12 gap-4 relative">
                            <button type="button"
                                class="delete-row absolute top-2 end-2 text-red-600 hover:text-red-800"
                                title="Remove delegate" @click="removeDelegate(index)"
                                x-show="delegates.length > 0">&times;</button>

                            <!-- Title -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('title') }}</label>
                                <select :name="`delegates[${index}][title_id]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.title_id">
                                    <option value="">{{ __db('select_title') }}</option>
                                    {!! $titleOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.title_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.title_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Name (Arabic) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('name_ar') }}</label>
                                <input type="text" :name="`delegates[${index}][name_ar]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.name_ar">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.name_ar`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.name_ar`][0]"></span>
                                </div>
                            </div>

                            <!-- Name (English) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('name_en') }}</label>
                                <input type="text" :name="`delegates[${index}][name_en]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.name_en">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.name_en`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.name_en`][0]"></span>
                                </div>
                            </div>

                            <!-- Designation (English) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('designation_en') }}</label>
                                <input type="text" :name="`delegates[${index}][designation_en]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.designation_en">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.designation_en`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.designation_en`][0]"></span>
                                </div>
                            </div>

                            <!-- Designation (Arabic) -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('designation_ar') }}</label>
                                <input type="text" :name="`delegates[${index}][designation_ar]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.designation_ar">
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.designation_ar`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.designation_ar`][0]"></span>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('gender') }}</label>
                                <select :name="`delegates[${index}][gender_id]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.gender_id">
                                    <option value="">{{ __db('select_gender') }}</option>
                                    {!! $genderOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.gender_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.gender_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Parent -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('parent') }}</label>
                                <select :name="`delegates[${index}][parent_id]`" x-model="delegate.parent_id"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    <option value="">{{ __db('select_parent_id') }}</option>
                                    <template x-for="(parentDelegate, parentIndex) in delegates"
                                        :key="`parent-${parentDelegate.tmp_id}`">
                                        <template x-if="parentDelegate.tmp_id !== delegate.tmp_id">
                                            <option :value="parentDelegate.tmp_id">
                                                Parent #<span x-text="parentIndex + 1"></span> - <span
                                                    x-text="parentDelegate.name_en || parentDelegate.name_ar || 'Unnamed'"></span>
                                            </option>
                                        </template>
                                    </template>
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.parent_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.parent_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Relationship -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('relationship') }}</label>
                                <select :name="`delegates[${index}][relationship]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.relationship">
                                    <option value="">{{ __db('select_relationship') }}</option>
                                    {!! $relationshipOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.relationship`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.relationship`][0]"></span>
                                </div>
                            </div>

                            <!-- Internal Ranking -->
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('internal_ranking') }}</label>
                                <select :name="`delegates[${index}][internal_ranking_id]`"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"
                                    x-model="delegate.internal_ranking_id">
                                    <option value="">{{ __db('select_internal_ranking') }}</option>
                                    {!! $internalRankingOptionsHtml !!}
                                </select>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.internal_ranking_id`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.internal_ranking_id`][0]"></span>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="col-span-6">
                                <label class="form-label">{{ __db('note') }}</label>
                                <textarea :name="`delegates[${index}][note]`" rows="3"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600" x-model="delegate.note"></textarea>
                                <div x-show="window.delegatesFieldErrors[`delegates.${index}.note`]">
                                    <span class="text-red-600"
                                        x-text="window.delegatesFieldErrors[`delegates.${index}.note`][0]"></span>
                                </div>
                            </div>

                            <!-- Checkboxes -->
                            <span class="col-span-12 border-t border-neutral-200 pt-6 mt-6 flex gap-8">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" :id="`team-head-${index}`"
                                        :name="`delegates[${index}][team_head]`" value="1"
                                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        x-model="delegate.team_head" />
                                    <label :for="`team-head-${index}`"
                                        class="text-sm text-gray-700">{{ __db('team_head') }}</label>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" :id="`badge-printed-${index}`"
                                        :name="`delegates[${index}][badge_printed]`" value="1"
                                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        x-model="delegate.badge_printed" />
                                    <label :for="`badge-printed-${index}`"
                                        class="text-sm text-gray-700">{{ __db('badge_printed') }}</label>
                                </div>
                            </span>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-between items-center mt-5">
                <button type="button" id="add-delegate-btn"
                    class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-6" @click="addDelegate()">
                    {{ __db('add_delegate') }}
                </button>

                <div class="flex gap-4">

                    @canany(['add_delegations', 'delegate_add_delegations'])
                        <button type="submit" name="submit_exit" value="1"
                            class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-8 submit-btn">{{ __db('submit') }}</button>
                    @endcanany

                    @canany(['add_travels', 'delegate_add_delegates'])
                        <button type="submit" name="submit_add_travel" value="2"
                            class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-8 submit-btn">{{ __db('submit_add_flight') }}</button>
                    @endcanany


                    @canany(['add_interviews', 'delegate_add_delegates'])
                        <button type="submit" name="submit_add_interview" value="3"
                            class="btn text-md !bg-[#D7BC6D] text-white rounded-lg h-12 px-8 submit-btn">{{ __db('submit_add_interview') }}</button>
                    @endcanany

                </div>
            </div>
        </div>

    </form>
</div>


@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const attachmentTitleDropdown = @json($attachmentTitleOptionsHtml);
            initializeAttachmentsComponent(attachmentTitleDropdown);

            // Initialize Select2 for all select elements
            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });

            // Handle continent change to load countries
            $('#continent-select').on('change', function() {
                const continentId = $(this).val();
                const countrySelect = $('#country-select');

                // Clear current options except the default
                countrySelect.find('option[value!=""]').remove();

                if (continentId) {
                    // Load countries for selected continent
                    $.get('{{ route('countries.by-continent') }}', {
                        continent_ids: continentId
                    }, function(data) {
                        // Add new options
                        $.each(data, function(index, country) {
                            countrySelect.append(new Option(country.name, country.id, false,
                                false));
                        });

                        // Refresh Select2
                        countrySelect.trigger('change');
                    }).fail(function() {
                        console.log('Failed to load countries');
                    });
                }
            });

            // Trigger continent change on page load if a continent is already selected
            const selectedContinent = $('#continent-select').val();
            if (selectedContinent) {
                $('#continent-select').trigger('change');
            }
        });

        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('delegations.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data.status == 'success') {
                    toastr.success("{{ __db('status_updated') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } else {
                    toastr.error("{{ __db('something_went_wrong') }}");
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        }


        function attachmentsComponent() {
            return {
                attachments: @json($attachmentsData),

                addAttachment() {
                    this.attachments.push({
                        title_id: '',
                        document_date: '',
                        file: null
                    });
                },

                removeAttachment(index) {
                    // Mark as deleted instead of removing from array to preserve indices
                    this.attachments[index]._destroy = true;
                }
            };
        }

        function initializeAttachmentsComponent(dropdownHtml) {
            window.attachmentsComponent = function() {
                return {
                    attachments: @json($attachmentsData),

                    addAttachment() {
                        this.attachments.push({
                            title_id: '',
                            document_date: '',
                            file: null
                        });
                    },

                    removeAttachment(index) {
                        // Mark as deleted instead of removing from array to preserve indices
                        this.attachments[index]._destroy = true;
                    }
                };
            };
        }
    </script>

    <script>
        window.attachmentsData = @json($attachmentsData);
        window.attachmentsFieldErrors = @json($errors->getBag('default')->toArray());
        window.delegatesData = @json($delegatesData);
        window.delegatesFieldErrors = @json($errors->getBag('default')->toArray());

        // document.addEventListener('DOMContentLoaded', function() {
        //     const form = document.getElementById('create-delegation-form');
        //     const submitButtons = form.querySelectorAll('.submit-btn');

        //     form.addEventListener('submit', function() {
        //         submitButtons.forEach(button => {
        //             button.disabled = true;
        //         });
        //     });
        // });
    </script>

    <script>
        function delegateComponent() {
            return {
                delegates: window.delegatesData && window.delegatesData.length > 0 ?
                    window.delegatesData : [],
                addDelegate() {
                    // Generate a unique tmp_id based on existing tmp_ids
                    const maxTmpId = Math.max(...this.delegates.map(d => d.tmp_id || 0), 0);
                    const newTmpId = maxTmpId + 1;
                    this.delegates.push({
                        tmp_id: newTmpId,
                        title_id: '',
                        name_ar: '',
                        name_en: '',
                        designation_en: '',
                        designation_ar: '',
                        gender_id: '',
                        parent_id: '',
                        relationship: '',
                        internal_ranking_id: '',
                        note: '',
                        team_head: false,
                        badge_printed: false
                    });
                },
                removeDelegate(idx) {
                    this.delegates.splice(idx, 1);
                }
            }
        }
    </script>

    <script>
        function attachmentsComponent() {
            return {
                attachments: window.attachmentsData && window.attachmentsData.length > 0 ?
                    window.attachmentsData : [],
                addAttachment() {
                    this.attachments.push({
                        title_id: '',
                        file: null,
                        document_date: ''
                    });
                },
                removeAttachment(idx) {
                    if (this.attachments.length > 1) {
                        this.attachments.splice(idx, 1);
                    }
                }
            }
        }
    </script>
@endsection
