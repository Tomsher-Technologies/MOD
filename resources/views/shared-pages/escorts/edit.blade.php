<div class="dashboard-main-body">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('edit') . ' ' . __db('escort') }}</h2>
        <a href="{{ route('escorts.index') }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>Back</span>
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

    <!-- Escorts -->
    <div class="bg-white h-full w-full rounded-lg border-0 p-6">
        <form id="escort-form" action="{{ route('escorts.update', $escort->id) }}" method="POST"
            data-ajax-form="true">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title') }}:</label>
                    <select name="title_id" class="select2 p-3 rounded-lg w-full text-sm border border-neutral-300">
                        <option value="" disabled>{{ __db('select_title') }}</option>
                        @foreach (getDropDown('title')->options as $option)
                            <option value="{{ $option->id }}" @if (old('title_id', $escort->title_id) == $option->id) selected @endif>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('military_number') }}:</label>
                    <input type="text" name="military_number"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('military_number', $escort->military_number) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_ar') }}:</label>
                    <input type="text" name="name_ar"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('name_ar', $escort->name_ar) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_en') }}:</label>
                    <input type="text" name="name_en"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('name_en', $escort->name_en) }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('phone_number') }}:</label>
                    <input type="text" name="phone_number"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}" value="{{ old('phone_number', $escort->phone_number) }}">
                </div>


                <div class="col-span-4">
                    <label class="form-label">{{ __db('gender') }}:</label>
                    <select name="gender_id"
                        class="select2 p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('select') . ' ' . __db('gender') }}</option>
                        @foreach (getDropDown('gender')->options as $gender)
                            <option value="{{ $gender->id }}"
                                {{ old('gender_id', $escort->gender_id) == $gender->id ? 'selected' : '' }}>
                                {{ $gender->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('spoken') . ' ' . __db('languages') }}:</label>
                    <select name="language_id[]" id="multiSelect" multiple placeholder="Select Languages"
                        class="select2 w-full p-3 rounded-lg border border-gray-300 text-sm">
                        @php
                            $selectedLanguages = old(
                                'language_id',
                                $escort->spoken_languages ? explode(',', $escort->spoken_languages) : [],
                            );
                        @endphp
                        @foreach (getDropDown('spoken_languages')->options as $language)
                            <option value="{{ $language->id }}"
                                {{ in_array($language->id, $selectedLanguages) ? 'selected' : '' }}>
                                {{ $language->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('rank') }}:</label>
                    <select name="internal_ranking_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('select') . ' ' . __db('rank') }}</option>
                        @foreach (getDropDown('internal_ranking')->options as $rank)
                            <option value="{{ $rank->id }}"
                                {{ old('internal_ranking_id', $escort->internal_ranking_id) == $rank->id ? 'selected' : '' }}>
                                {{ $rank->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('unit') }}:</label>
                    <select name="unit_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('select') . ' ' . __db('unit') }}</option>
                        @foreach (getDropDown('unit')->options ?? [] as $unit)
                            <option value="{{ $unit->id }}"
                                {{ old('unit', $escort->unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('status') }}:</label>
                    <select name="status"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="1" {{ old('status', $escort->status) == 1 ? 'selected' : '' }}>
                            {{ __db('active') }}
                        </option>
                        <option value="0" {{ old('status', $escort->status) == 0 ? 'selected' : '' }}>
                            {{ __db('inactive') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="flex justify-between items-center mt-8">
                <button type="submit"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">{{ __db('save') }}</button>
            </div>
        </form>
    </div>

    <h2 class="font-semibold mb-0 !text-[22px] mt-6">{{ __db('reassign') }}</h2>
    <x-assign-delegation-with-search :escort="$escort" />
</div>
