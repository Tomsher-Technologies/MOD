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

                {{-- Title (AR) --}}
                <div class="col-span-4">
                    <label class="form-label">Title (AR):</label>
                    <select name="title_ar"
                        class="p-3 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>Select Title</option>
                        <option value="Mr." {{ old('title_ar') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                        <option value="Mrs." {{ old('title_ar') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                        <option value="Ms." {{ old('title_ar') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                        <option value="Miss" {{ old('title_ar') == 'Miss' ? 'selected' : '' }}>Miss</option>
                        <option value="Dr." {{ old('title_ar') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        <option value="Prof." {{ old('title_ar') == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                    </select>
                </div>

                {{-- Name (AR) --}}
                <div class="col-span-4">
                    <label class="form-label">Name (AR) :</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                {{-- Designation (AR) --}}
                <div class="col-span-4">
                    <label class="form-label">Designation (AR) :</label>
                    <input type="text" name="designation_ar" value="{{ old('designation_ar') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                {{-- Title (EN) --}}
                <div class="col-span-4">
                    <label class="form-label">Title (EN):</label>
                    <select name="title_en"
                        class="p-3 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>Select Title</option>
                        <option value="Mr." {{ old('title_en') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                        <option value="Mrs." {{ old('title_en') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                        <option value="Ms." {{ old('title_en') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                        <option value="Miss" {{ old('title_en') == 'Miss' ? 'selected' : '' }}>Miss</option>
                        <option value="Dr." {{ old('title_en') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        <option value="Prof." {{ old('title_en') == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                    </select>
                </div>

                {{-- Name (EN) --}}
                <div class="col-span-4">
                    <label class="form-label">Name (EN) : <span class="text-red-600">*</span></label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" required
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                {{-- Designation (EN) --}}
                <div class="col-span-4">
                    <label class="form-label">Designation (EN) :</label>
                    <input type="text" name="designation_en" value="{{ old('designation_en') }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                </div>

                {{-- Gender --}}
                <div class="col-span-3">
                    <label class="form-label">Gender: <span class="text-red-600">*</span></label>
                    <select name="gender_id" required
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>Select Gender</option>
                        {{-- Replace these options with your actual dropdown option IDs & names --}}
                        <option value="1" {{ old('gender_id') == 1 ? 'selected' : '' }}>Male</option>
                        <option value="2" {{ old('gender_id') == 2 ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                {{-- Parent Id --}}
                <div class="col-span-3">
                    <label class="form-label">Parent Id:</label>
                    <select name="parent_id"
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">Select Parent Id</option>
                        {{-- Loop through delegates here if available --}}
                        <option value="1" {{ old('parent_id') == 1 ? 'selected' : '' }}>DA25-001</option>
                    </select>
                </div>

                {{-- Relationship --}}
                <div class="col-span-3">
                    <label class="form-label">Relationship:</label>
                    <select name="relationship"
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">Select Relationship</option>
                        <option value="Father" {{ old('relationship') == 'Father' ? 'selected' : '' }}>Father</option>
                        <option value="Mother" {{ old('relationship') == 'Mother' ? 'selected' : '' }}>Mother</option>
                        <option value="Brother" {{ old('relationship') == 'Brother' ? 'selected' : '' }}>Brother
                        </option>
                        <option value="Sister" {{ old('relationship') == 'Sister' ? 'selected' : '' }}>Sister</option>
                        <option value="Spouse" {{ old('relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                        <option value="Son" {{ old('relationship') == 'Son' ? 'selected' : '' }}>Son</option>
                        <option value="Daughter" {{ old('relationship') == 'Daughter' ? 'selected' : '' }}>Daughter
                        </option>
                    </select>
                </div>

                {{-- Internal Ranking --}}
                <div class="col-span-3">
                    <label class="form-label">Internal Ranking:</label>
                    <select name="internal_ranking_id"
                        class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>Select Internal Ranking</option>
                        <option value="Major" {{ old('internal_ranking_id') == 'Major' ? 'selected' : '' }}>Major
                        </option>
                        <option value="Captain" {{ old('internal_ranking_id') == 'Captain' ? 'selected' : '' }}>Captain
                        </option>
                        <option value="Minister" {{ old('internal_ranking_id') == 'Minister' ? 'selected' : '' }}>
                            Minister</option>
                        <option value="Member" {{ old('internal_ranking_id') == 'Member' ? 'selected' : '' }}>Member
                        </option>
                    </select>
                </div>

                {{-- Note --}}
                <div class="col-span-12">
                    <label class="form-label">Note 1:</label>
                    <textarea name="note" rows="4" placeholder="Type here..."
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white">{{ old('note') }}</textarea>
                </div>
            </div>

            {{-- Checkboxes --}}
            <span class="pt-6 mt-6 flex gap-8 ">
                <div class="flex items-center gap-3">
                    <input id="team-head" name="team_head" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('team_head') ? 'checked' : '' }} />
                    <label for="team-head" class="text-sm text-gray-700">Team Head</label>
                </div>

                <div class="flex items-center gap-3">
                    <input id="badge-printed" name="badge_printed" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('badge_printed') ? 'checked' : '' }}>
                    <label for="badge-printed" class="text-sm text-gray-700">Badge Printed</label>
                </div>

                <div class="flex items-center gap-3">
                    <input id="accommodation" name="accommodation" type="checkbox" value="1"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('accommodation') ? 'checked' : '' }}>
                    <label for="accommodation" class="text-sm text-gray-700">Accommodation</label>
                </div>
            </span>

            <hr class="mx-6 border-neutral-200 !my-[20px]">

            {{-- Arrival Section --}}
            <h4 class="text-lg font-semibold mb-2">Arrival</h4>
            <div class="flex items-center gap-4 mb-5">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="travel_arrival" value="flight" class="form-radio text-blue-600"
                        checked>
                    <span class="text-[15px] text-gray-700">Flight</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="travel_arrival" value="land" class="form-radio text-green-600">
                    <span class="text-[15px] text-gray-700">Land</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="travel_arrival" value="sea" class="form-radio text-purple-600">
                    <span class="text-[15px] text-gray-700">Sea</span>
                </label>
            </div>

            <div id="flight-inputs-arrival" class="grid grid-cols-5 gap-5">
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Arrival Airport:</label>
                    <select name="arrival_airport" class="p-3 rounded-lg w-full border text-sm">
                        <option selected disabled>Select To Airport</option>
                        <option>Dubai International Airport (DXB)</option>
                        <option>Abu Dhabi International Airport (AUH)</option>
                        <option>Sharjah International Airport (SHJ)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Flight No:</label>
                    <input type="text" name="arrival_flight_no"
                        class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" placeholder="Enter Flight No">
                </div>
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Flight Name:</label>
                    <input type="text" name="arrival_flight_name"
                        class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                        placeholder="Enter Flight Name">
                </div>
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Date & Time:</label>
                    <input type="datetime-local" name="arrival_datetime"
                        class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                </div>
                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Arrival Status:</label>
                    <select name="arrival_status" class="p-3 rounded-lg w-full border text-sm">
                        <option selected disabled>Select Status</option>
                        <option>To be Arrived</option>
                        <option>Arrived</option>
                    </select>
                </div>
            </div>
            <div class="col-span-5 mt-4" id="land-sea-arrival">
                <label class="form-label block mb-1 text-gray-700 font-medium">Comment:</label>
                <textarea name="arrival_comment" rows="4"
                    class="block p-2.5 w-full text-sm rounded-lg border !border-[#d1d5db]" placeholder="Type here..."></textarea>
            </div>

            <hr class="mx-6 border-neutral-200 h-5">

            {{-- Departure Section --}}
            <h4 class="text-lg font-semibold mb-2">Departure</h4>
            <div class="flex items-center gap-4 mb-5">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="travel_departure" value="flight" class="form-radio text-blue-600"
                        checked>
                    <span class="text-[15px] text-gray-700">Flight</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="travel_departure" value="land" class="form-radio text-green-600">
                    <span class="text-[15px] text-gray-700">Land</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="radio" name="travel_departure" value="sea" class="form-radio text-purple-600">
                    <span class="text-[15px] text-gray-700">Sea</span>
                </label>
            </div>

            <div id="flight-inputs-departure" class="grid grid-cols-5 gap-5">

                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Departure Airport:</label>
                    <select name="departure_airport" class="p-3 rounded-lg w-full border text-sm">
                        <option selected disabled>Select From Airport</option>
                        <option>Dubai International Airport (DXB)</option>
                        <option>Abu Dhabi International Airport (AUH)</option>
                        <option>Sharjah International Airport (SHJ)</option>
                    </select>
                </div>

                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Flight No:</label>
                    <input type="text" name="departure_flight_no"
                        class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm" placeholder="Enter Flight No">
                </div>

                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Flight Name:</label>
                    <input type="text" name="departure_flight_name"
                        class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm"
                        placeholder="Enter Flight Name">
                </div>

                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Date & Time:</label>
                    <input type="datetime-local" name="departure_datetime"
                        class="p-3 rounded-lg w-full border !border-[#d1d5db] text-sm">
                </div>

                <div>
                    <label class="form-label block mb-1 text-gray-700 font-medium">Departure Status:</label>
                    <select name="departure_status" class="p-3 rounded-lg w-full border text-sm">
                        <option selected disabled>Select Status</option>
                        <option>To be Departed</option>
                        <option>Departed</option>
                    </select>
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
