<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('add') . ' ' . __db('escorts') }}</h2>
        <a href="{{ getRouteForPage('escorts.index') }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
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

    <div class="bg-white h-full w-full rounded-lg border-0 p-6 mb-10">
        <form action="{{ getRouteForPage('escorts.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-4">
                    <label class="form-label">{{ __db('military_number') }}:</label>
                    <input type="text" name="military_number" value="{{ old('military_number') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') . ' ' . __db('military_number') }}">
                </div>

                {{--
                <div class="col-span-4">
                    <label class="form-label">{{ __db('Delegation') }}:</label>
                    <select name="delegation_id"
                        class="p-3 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __db('Select Delegation') }}</option>
                        @foreach ($delegations as $delegation)
                            <option value="{{ $delegation->id }}" {{ old('delegation_id') == $delegation->id ? 'selected' : '' }}>
                                {{ $delegation->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                --}}

                <div class="col-span-3">
                    <label class="form-label">{{ __db('name_ar') }}:</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('أدخل الاسم هنا') }}">
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('name_en') }}:</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('Enter Name Here') }}">
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('gender') }}:</label>
                    <select name="gender_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled {{ old('gender_id') ? '' : 'selected' }}>{{ __db('Select Gender') }}</option>
                        @foreach (getDropDown('gender')->options as $gender)
                            <option value="{{ $gender->id }}"
                                {{ old('gender_id') == $gender->id ? 'selected' : '' }}>
                                {{ $gender->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('spoken_languages') }}:</label>
                    <select name="language_id[]" id="multiSelect" multiple
                        class="w-full p-3 rounded-lg border border-gray-300 text-sm"
                        placeholder="{{ __db('Select Languages') }}">
                        @php
                            $oldLanguageIds = old('language_id', []);
                        @endphp
                        @foreach (getDropDown('spoken_languages')->options as $language)
                            <option value="{{ $language->id }}"
                                {{ in_array($language->id, $oldLanguageIds) ? 'selected' : '' }}>
                                {{ $language->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('rank') }}:</label>
                    <select name="internal_ranking_id"
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled {{ old('internal_ranking_id') ? '' : 'selected' }}>{{ __db('Select Rank') }}
                        </option>
                        @foreach (getDropDown('internal_ranking')->options as $rank)
                            <option value="{{ $rank->id }}"
                                {{ old('internal_ranking_id') == $rank->id ? 'selected' : '' }}>
                                {{ $rank->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('status') }}:</label>
                    <select name="status"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                            {{ __db('active') }}
                        </option>
                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                            {{ __db('inactive') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center mt-8">
                <button type="submit" id="add-delegates"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">
                    {{ __db('add') . ' ' . __db('escorts') }}
                </button>
            </div>
        </form>
    </div>
</div>
