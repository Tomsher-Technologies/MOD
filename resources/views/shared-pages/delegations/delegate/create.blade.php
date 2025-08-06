<div>
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">Add Delegate</h2>
        <a href="{{ route('delegations.edit', $delegation->id) }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('delegations.storeDelegate', $delegation->id) }}">
        @csrf

        <div class="delegate-row border bg-white p-6 rounded bg-gray-100 mb-2">
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title_en') }}:</label>
                    <select name="title_en"
                        class="p-3 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('select_title') }}</option>
                        @foreach (getDropDown('title')->options as $option)
                            <option value="{{ $option->id }}" {{ old('title_id') == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_ar') }} :</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('designation_ar') }} :</label>
                    <input type="text" name="designation_ar" value="{{ old('designation_ar') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_en') }} : <span class="text-red-600">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" required
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('designation_en') }} :</label>
                    <input type="text" name="designation_en" value="{{ old('designation_en') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('gender') }}: <span class="text-red-600">*</span></label>
                    <select name="gender_id" required
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('select_gender') }}</option>
                        @foreach (getDropDown('gender')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('gender_id') == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('parent_id') }}:</label>
                    <select name="parent_id"
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select_parent') }}</option>
                        @foreach ($delegation->delegates as $delegate)
                            <option value="{{ $delegate->id }}"
                                {{ old('parent_id') == $delegate->id ? 'selected' : '' }}>
                                {{ $delegate->name_en }} ({{ $delegate->id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('relationship') }}:</label>
                    <select name="relationship_id"
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select_relationship') }}</option>
                        @foreach (getDropDown('relationship')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('relationship_id') == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('internal_ranking') }}:</label>
                    <select name="internal_ranking_id"
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('select_internal_ranking') }}</option>
                        @foreach (getDropDown('internal_ranking')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('internal_ranking_id') == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label class="form-label">{{ __db('note') }}:</label>
                    <textarea name="note" rows="4" placeholder="Type here..."
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white">{{ old('note') }}</textarea>
                </div>
            </div>

            <span class="pt-6 mt-6 flex gap-8 ">
                <div class="flex items-center gap-3">
                    <input id="team-head" name="team_head" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('team_head') ? 'checked' : '' }} />
                    <label for="team-head" class="text-sm text-gray-700">{{ __db('team_head') }}</label>
                </div>

                <div class="flex items-center gap-3">
                    <input id="badge-printed" name="badge_printed" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('badge_printed') ? 'checked' : '' }}>
                    <label for="badge-printed" class="text-sm text-gray-700">{{ __db('badge_printed') }}</label>
                </div>

                <div class="flex items-center gap-3">
                    <input id="accommodation" name="accommodation" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('accommodation') ? 'checked' : '' }}>
                    <label for="accommodation" class="text-sm text-gray-700">{{ __db('accommodation') }}</label>
                </div>
            </span>

            <hr class="mx-6 border-neutral-200 !my-[20px]">

            <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('arrival') }}</h2>
            <div class="bg-white rounded-lg p-6 mb-10 mt-4">

                <div class="flex items-center gap-4 mb-5">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="arrival[mode]" value="flight" class="form-radio text-blue-600"
                            checked />
                        <span class="text-[15px] text-gray-700">{{ __db('flight') }}</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="arrival[mode]" value="land"
                            class="form-radio text-green-600" />
                        <span class="text-[15px] text-gray-700">{{ __db('land') }}</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="arrival[mode]" value="sea"
                            class="form-radio text-purple-600" />
                        <span class="text-[15px] text-gray-700">{{ __db('sea') }}</span>
                    </label>
                </div>

                <div class="grid grid-cols-5 gap-5 w-full">
                    <div id="arrival-flight-fields" class="col-span-3 grid grid-cols-3 gap-5">
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('arrival_airport') }}:</label>
                            <select name="arrival[airport_id]"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                                <option selected disabled>{{ __db('select_to_airport') }}</option>
                                @foreach (getDropdown('airports')->options as $airport)
                                    <option value="{{ $airport->id }}">{{ $airport->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_no') }}:</label>
                            <input name="arrival[flight_no]" type="text"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                                placeholder="Enter Flight No" />
                        </div>
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_name') }}:</label>
                            <input name="arrival[flight_name]" type="text"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                                placeholder="Enter Flight Name" />
                        </div>
                    </div>
                    <div class="col-span-2 grid grid-cols-2 gap-5">
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('date_time') }}:</label>
                            <input name="arrival[date_time]" type="datetime-local"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" />
                        </div>
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('arrival_status') }}:</label>
                            <select name="arrival[status_id]"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                                <option selected disabled>{{ __db('select_status') }}</option>
                                @foreach (getDropdown('arrival_status')->options as $status)
                                    <option value="{{ $status->id }}">{{ $status->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-span-5 mt-4" id="land-sea-arrival">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('comment') }}:</label>
                    <textarea name="arrival[comment]" rows="4"
                        class="block p-2.5 w-full text-sm rounded-lg border !border-[#d1d5db]" placeholder="Type here..."></textarea>
                </div>
            </div>


            <hr class="mx-6 border-neutral-200 h-5">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('departure') }}</h2>
            <div class="bg-white rounded-lg p-6 mb-5 mt-4">
                <div class="flex items-center gap-4 mb-5">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="departure[mode]" value="flight" class="form-radio text-blue-600"
                            checked>
                        <span class="text-[15px] text-gray-700">{{ __db('flight') }}</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="departure[mode]" value="land"
                            class="form-radio text-green-600">
                        <span class="text-[15px] text-gray-700">{{ __db('land') }}</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="departure[mode]" value="sea"
                            class="form-radio text-purple-600">
                        <span class="text-[15px] text-gray-700">{{ __db('sea') }}</span>
                    </label>
                </div>

                <div class="grid grid-cols-5 gap-5 w-full">
                    <div id="departure-flight-fields" class="col-span-3 grid grid-cols-3 gap-5">
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('departure_airport') }}:</label>
                            <select name="departure[airport_id]"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                                <option selected disabled>{{ __db('select_from_airport') }}</option>
                                @foreach (getDropdown('airports')->options as $airport)
                                    <option value="{{ $airport->id }}">{{ $airport->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_no') }}:</label>
                            <input name="departure[flight_no]" type="text"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                                placeholder="Enter Flight No" />
                        </div>
                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('flight_name') }}:</label>
                            <input name="departure[flight_name]" type="text"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                                placeholder="Enter Flight Name" />
                        </div>
                    </div>

                    <div class="col-span-2 grid grid-cols-2 gap-5">

                        <div>
                            <label
                                class="form-label block mb-1 text-gray-700 font-medium">{{ __db('date_time') }}:</label>
                            <input name="departure[date_time]" type="datetime-local"
                                class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" />
                        </div>

                        <div>
                            <div>
                                <label
                                    class="form-label block mb-1 text-gray-700 font-medium">{{ __db('departure_status') }}:</label>
                                <select name="departure[status_id]"
                                    class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                                    <option selected disabled>{{ __db('select_status') }}</option>
                                    @foreach (getDropdown('departure_status')->options as $status)
                                        <option value="{{ $status->id }}">{{ $status->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-5 mt-4" id="land-sea-departure">
                    <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('comment') }}:</label>
                    <textarea name="departure[comment]" rows="4"
                        class="block p-2.5 w-full text-sm rounded-lg border !border-[#d1d5db]" placeholder="Type here..."></textarea>
                </div>



            </div>

            <div class="col-span-5 mt-4" id="land-sea-departure">
                <label class="form-label block mb-1 text-gray-700 font-medium">Comment:</label>
                <textarea name="departure_comment" rows="4"
                    class="block p-2.5 w-full text-sm rounded-lg border !border-[#d1d5db]" placeholder="Type here..."></textarea>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="submit" id="add-delegates"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">Submit</button>
            </div>
        </div>
    </form>
</div>
