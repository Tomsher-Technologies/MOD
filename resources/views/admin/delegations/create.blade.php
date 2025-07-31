@extends('layouts.admin_account', ['title' => __db('create_delegation')])

@section('content')
    <div class="dashboard-main-body ">

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

        <form action="{{ route('delegations.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="bg-white h-full w-full rounded-lg border-0 p-6 mb-10">

                <div class="grid grid-cols-12 gap-5">
                    <div class="col-span-3">
                        <label class="form-label">{{ __db('delegate_id') }}:</label>
                        <input type="text" name="delegate_id" value="{{ old('delegate_id', $uniqueDelegateId) }}"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0 bg-gray-200"
                            readonly />
                    </div>
                    <div class="col-span-3">
                        <div class="flex align-center justify-between">
                            <label class="form-label">{{ __db('invitation_from') }}:</label>
                        </div>

                        @php
                            $departmentOptions = getDropDown('departments');
                            $continentOptions = getDropDown('continents');
                            $countryOptions = getDropDown('country');
                            $invitationStatusOptions = getDropDown('invitation_status');
                            $participationStatusOptions = getDropDown('participation_status');
                        @endphp

                        <select name="invitation_from_id"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
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
                        <select name="continent_id"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
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
                        <select name="country_id"
                            class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                            <option value="">{{ __db('select_country') }}</option>
                            @if ($countryOptions)
                                @foreach ($countryOptions->options as $option)
                                    <option value="{{ $option->id }}"
                                        {{ old('country_id', request('country_id')) == $option->id ? 'selected' : '' }}>
                                        {{ $option->value }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                        @error('country_id')
                            <div class="text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">{{ __db('invitation_status') }}:</label>
                        <select name="invitation_status_id"
                            class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
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
                            class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
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
                    @endphp

                    <div id="attachment-container" class="col-span-12">
                        @if (old('attachments'))
                            @foreach (old('attachments') as $i => $oldAttachment)
                                <div class="grid grid-cols-12 gap-5 mb-2 attachment-row">
                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('title') }}</label>
                                        <select name="attachments[{{ $i }}][title]"
                                            class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                            <option value="">{{ __db('select_title') }}</option>
                                            {!! $attachmentTitleOptionsHtml !!}
                                        </select>

                                        @error('attachments.' . $i . '.title')
                                            <div class="text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('file') }}</label>
                                        <input
                                            class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                                            type="file" name="attachments[{{ $i }}][file]">
                                        @error('attachments.' . $i . '.file')
                                            <div class="text-red-600">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label">{{ __db('document_date') }}</label>
                                        <input type="date" name="attachments[{{ $i }}][document_date]"
                                            value="{{ $oldAttachment['document_date'] ?? '' }}"
                                            class="w-full border border-gray-300 text-sm rounded-lg px-3 py-3 text-sm">

                                        @error('attachments.' . $i . '.document_date')
                                            <div class="text-red-600">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="col-span-3 flex items-end">
                                        <button type="button"
                                            class="delete-row bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="grid grid-cols-12 gap-5 mb-2 attachment-row">
                                <div class="col-span-3">
                                    <label class="form-label">{{ __db('title') }}</label>
                                    <select name="attachments[0][title]"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                        <option value="">{{ __db('select_title') }}</option>
                                        {!! $attachmentTitleOptionsHtml !!}
                                    </select>

                                    @error('attachments.0.title')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-span-3">
                                    <label class="form-label">{{ __db('file') }}</label>
                                    <input
                                        class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                                        type="file" name="attachments[0][file]">
                                    @error('attachments.0.file')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="col-span-3">
                                    <label class="form-label">{{ __db('document_date') }}</label>
                                    <input type="date" name="attachments[0][document_date]"
                                        class="w-full border border-gray-300 text-sm rounded-lg px-3 py-3 text-sm">
                                    @error('attachments.0.document_date')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="col-span-3 flex items-end">
                                    <button type="button"
                                        class="delete-row bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-span-12 mb-10">
                        <button type="button" id="add-attachment-btn"
                            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                            <svg class="w-6 h-6 text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>
                            <span>{{ __db('add_attachments') }}</span>
                        </button>
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
                @endphp

                <div id="delegate-container" class="space-y-4">
                    @if (old('delegates'))
                        @foreach (old('delegates') as $i => $oldDelegate)
                            <div class="delegate-row border rounded p-4 grid grid-cols-12 gap-4 relative">
                                <button type="button"
                                    class="delete-row absolute top-2 end-2 text-red-600 hover:text-red-800"
                                    title="Remove delegate">&times;</button>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Title') }}</label>
                                    <select name="delegates[{{ $i }}][title]"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                        <option value="">{{ __db('select_title') }}</option>
                                        {!! $titleOptionsHtml !!}
                                    </select>
                                    @error('delegates.' . $i . '.title')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Name (AR)') }}</label>
                                    <input type="text" name="delegates[{{ $i }}][name_ar]"
                                        value="{{ $oldDelegate['name_ar'] ?? '' }}"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    @error('delegates.' . $i . '.name_ar')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Name (EN)') }}</label>
                                    <input type="text" name="delegates[{{ $i }}][name_en]"
                                        value="{{ $oldDelegate['name_en'] ?? '' }}"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    @error('delegates.' . $i . '.name_en')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Designation (EN)') }}</label>
                                    <input type="text" name="delegates[{{ $i }}][designation_en]"
                                        value="{{ $oldDelegate['designation_en'] ?? '' }}"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    @error('delegates.' . $i . '.designation_en')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Designation (AR)') }}</label>
                                    <input type="text" name="delegates[{{ $i }}][designation_ar]"
                                        value="{{ $oldDelegate['designation_ar'] ?? '' }}"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    @error('delegates.' . $i . '.designation_ar')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Gender') }}</label>
                                    <select name="delegates[{{ $i }}][gender_id]"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                        <option value="">{{ __db('select_gender') }}</option>
                                        {!! $genderOptionsHtml !!}
                                    </select>
                                    @error('delegates.' . $i . '.gender_id')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Parent') }}</label>
                                    <select name="delegates[{{ $i }}][parent_id]"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                        <option value="">{{ __db('select_parent_id') }}</option>
                                        @if (isset($parentDelegates))
                                            @foreach ($parentDelegates as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ ($oldDelegate['parent_id'] ?? '') == $p->id ? 'selected' : '' }}>
                                                    {{ $p->name_en }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('delegates.' . $i . '.parent_id')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Relationship') }}</label>
                                    <select name="delegates[{{ $i }}][relationship]"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                        <option value="">{{ __db('select_relationship') }}</option>
                                        {!! $relationshipOptionsHtml !!}
                                    </select>
                                    @error('delegates.' . $i . '.relationship')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-3">
                                    <label class="form-label">{{ __('Internal Ranking') }}</label>
                                    <select name="delegates[{{ $i }}][internal_ranking]"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                        <option value="">{{ __db('select_internal_ranking') }}</option>
                                        {!! $internalRankingOptionsHtml !!}
                                    </select>
                                    @error('delegates.' . $i . '.internal_ranking')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-span-6">
                                    <label class="form-label">{{ __('Note') }}</label>
                                    <textarea name="delegates[{{ $i }}][note]" rows="3"
                                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">{{ $oldDelegate['note'] ?? '' }}</textarea>
                                    @error('delegates.' . $i . '.note')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <span class="col-span-12 border-t border-neutral-200 pt-6 mt-6 flex gap-8">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="team-head-{{ $i }}"
                                            name="delegates[{{ $i }}][team_head]" value="1"
                                            class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                        <label for="team-head-{{ $i }}"
                                            class="text-sm text-gray-700">{{ __('Team Head') }}</label>
                                    </div>
                                    <span class="col-span-12 pt-">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" id="badge-printed-{{ $i }}"
                                                name="delegates[{{ $i }}][badge_printed]" value="1"
                                                class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                            <label for="badge-printed-{{ $i }}"
                                                class="text-sm text-gray-700">{{ __('Badge Printed') }}</label>
                                        </div>
                                    </span>
                                </span>

                            </div>
                        @endforeach
                    @else
                        <div class="delegate-row border rounded p-4 grid grid-cols-12 gap-4 relative">
                            <button type="button"
                                class="delete-row absolute top-2 end-2 text-red-600 hover:text-red-800 hidden"
                                title="Remove delegate">&times;</button>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Title') }}</label>
                                <select name="delegates[0][title]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    <option value="">{{ __db('select_title') }}</option>
                                    {!! $titleOptionsHtml !!}
                                </select>
                                @error('delegates.0.title')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Name (AR)') }}</label>
                                <input type="text" name="delegates[0][name_ar]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                @error('delegates.0.name_ar')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Name (EN)') }}</label>
                                <input type="text" name="delegates[0][name_en]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                @error('delegates.0.name_en')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Designation (EN)') }}</label>
                                <input type="text" name="delegates[0][designation_en]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                @error('delegates.0.designation_en')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Designation (AR)') }}</label>
                                <input type="text" name="delegates[0][designation_ar]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                @error('delegates.0.designation_ar')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Gender') }}</label>
                                <select name="delegates[0][gender_id]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    <option value="">{{ __db('select_gender') }}</option>
                                    {!! $genderOptionsHtml !!}
                                </select>
                                @error('delegates.0.gender_id')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Parent') }}</label>
                                <select name="delegates[0][parent_id]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    <option value="">{{ __db('select_parent_id') }}</option>
                                </select>
                                @error('delegates.0.parent_id')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Relationship') }}</label>
                                <select name="delegates[0][relationship]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    <option value="">{{ __db('select_relationship') }}</option>
                                    {!! $relationshipOptionsHtml !!}
                                </select>
                                @error('delegates.0.relationship')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __('Internal Ranking') }}</label>
                                <select name="delegates[0][internal_ranking]"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                                    <option value="">{{ __db('select_internal_ranking') }}</option>
                                    {!! $internalRankingOptionsHtml !!}
                                </select>
                                @error('delegates.0.internal_ranking')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __('Note') }}</label>
                                <textarea name="delegates[0][note]" rows="3"
                                    class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"></textarea>
                                @error('delegates.0.note')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <span class="col-span-12 border-t border-neutral-200 pt-6 mt-6 flex gap-8">
                                <div class="flex items-center gap-3">
                                    <input id="team-head-0" type="checkbox" name="delegates[0][team_head]"
                                        value="1"
                                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                    <label for="team-head-0" class="text-sm text-gray-700">{{ __('Team Head') }}</label>
                                </div>
                                <span class="col-span-12 pt-">
                                    <div class="flex items-center gap-3">
                                        <input id="badge-printed" id="badge-printed-0" name="delegates[0][badge_printed]"
                                            value="1" type="checkbox"
                                            class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                        <label for="badge-printed"
                                            class="text-sm text-gray-700">{{ __('Badge Printed') }}</label>
                                    </div>
                                </span>
                            </span>

                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-center mt-5">
                    <button type="button" id="add-delegate-btn"
                        class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-6">Add
                        Delegate</button>
                    <div class="flex gap-4">

                        <button type="submit"
                            class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-8">{{ __db('submit') }}</button>
                        <button type="submit" name="submit_add_flight" value="1"
                            class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12 px-8">{{ __db('submit_add_flight') }}</button>
                        <button type="submit" name="submit_add_delegate" value="1"
                            class="btn text-md !bg-[#D7BC6D] text-white rounded-lg h-12 px-8">{{ __db('submit_add_delegate') }}</button>
                    </div>

                </div>
            </div>

        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let index = {{ old('attachments') ? count(old('attachments')) : 0 }};
            let attachmentOptionsHtml = `{!! $attachmentTitleOptionsHtml !!}`;

            document.getElementById('add-attachment-btn').onclick = function(e) {
                e.preventDefault();
                let container = document.getElementById('attachment-container');
                let html = `<div class="grid grid-cols-12 gap-5 mb-2 attachment-row">
            <div class="col-span-3">
                <label class="form-label">{{ __db('title') }}</label>
                <select name="attachments[${index}][title]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                    <option value="">{{ __db('select_title') }}</option>
                    ${attachmentOptionsHtml}
                </select>
            </div>
            <div class="col-span-3">
                <label class="form-label">{{ __db('file') }}</label>
                <input class="h-[46px] block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                    type="file" name="attachments[${index}][file]">
            </div>
            <div class="col-span-3">
                <label class="form-label">{{ __db('document_date') }}</label>
                <input type="date" name="attachments[${index}][document_date]"
                    class="w-full border border-gray-300 text-sm rounded-lg px-3 py-3 text-sm">
            </div>
            <div class="col-span-3 flex items-end">
                <button type="button" class="delete-row bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
            </div>
        </div>`;
                container.insertAdjacentHTML('beforeend', html);
                index++;
            };

            document.getElementById('attachment-container').addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-row')) {
                    e.preventDefault();
                    e.target.closest('.attachment-row').remove();
                }
            });
        });
    </script>

    <script>
        const genderOptionsHtml = `{!! $genderOptionsHtml !!}`;
        const relationshipOptionsHtml = `{!! $relationshipOptionsHtml !!}`;
        const titleOptionsHtml = `{!! $titleOptionsHtml !!}`;
        const internalRankingOptionsHtml = `{!! $internalRankingOptionsHtml !!}`;

        document.addEventListener('DOMContentLoaded', function() {
            const delegateContainer = document.getElementById('delegate-container');
            let delegateIndex = {{ old('delegates') ? count(old('delegates')) : 1 }};

            document.getElementById('add-delegate-btn').addEventListener('click', function(e) {
                e.preventDefault();

                const html = `
                <div class="delegate-row border rounded p-4 grid grid-cols-12 gap-4 relative">
                    <button type="button" class="delete-row absolute top-2 end-2 text-red-600 hover:text-red-800" title="Remove delegate">&times;</button>

                    <div class="col-span-3">
                        <label class="form-label">Title</label>
                        <select name="delegates[\${delegateIndex}][title]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                            <option value="">Select Title</option>
                            ${titleOptionsHtml}
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Name (AR)</label>
                        <input type="text" name="delegates[\${delegateIndex}][name_ar]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600" />
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Name (EN)</label>
                        <input type="text" name="delegates[\${delegateIndex}][name_en]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600" />
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Designation (EN)</label>
                        <input type="text" name="delegates[\${delegateIndex}][designation_en]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600" />
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Designation (AR)</label>
                        <input type="text" name="delegates[\${delegateIndex}][designation_ar]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600" />
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Gender</label>
                        <select name="delegates[\${delegateIndex}][gender_id]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                            <option value="">Select Gender</option>
                            ${genderOptionsHtml}
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Parent</label>
                        <select name="delegates[\${delegateIndex}][parent_id]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                            <option value="">Select Parent</option>
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Relationship</label>
                        <select name="delegates[\${delegateIndex}][relationship]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                            <option value="">Select Relationship</option>
                            ${relationshipOptionsHtml}
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label class="form-label">Internal Ranking</label>
                        <select name="delegates[\${delegateIndex}][internal_ranking]" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600">
                            <option value="">Select Ranking</option>
                            ${internalRankingOptionsHtml}
                        </select>
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">Note</label>
                        <textarea name="delegates[\${delegateIndex}][note]" rows="3" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600"></textarea>
                    </div>


                     <span class="col-span-12 border-t border-neutral-200 pt-6 mt-6 flex gap-8">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="team-head-\${delegateIndex}" name="delegates[\${delegateIndex}][team_head]" value="1"
                                class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                            <label for="team-head-\${delegateIndex}"
                                class="text-sm text-gray-700">{{ __('Team Head') }}</label>
                        </div>
                        <span class="col-span-12 pt-">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="badge-printed-\${delegateIndex}" name="delegates[\${delegateIndex}][badge_printed]" value="1"
                                    class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                <label for="badge-printed-\${delegateIndex}"
                                    class="text-sm text-gray-700">{{ __('Badge Printed') }}</label>
                            </div>
                        </span>
                    </span>

                </div>
                `;

                delegateContainer.insertAdjacentHTML('beforeend', html);
                delegateIndex++;
            });

            delegateContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-row')) {
                    e.target.closest('.delegate-row').remove();
                }
            });
        });
    </script>
@endsection
