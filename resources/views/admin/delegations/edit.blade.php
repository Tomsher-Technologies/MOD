@extends('layouts.admin_account', ['title' => __db('create_delegation')])

@section('content')
    <x-back-btn title="" back-url="{{ route('delegations.index') }}" />

    <form action="{{ route('delegations.update', $delegation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white h-full w-full rounded-lg border-0 p-6">
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-3">
                    <label class="form-label">{{ __db('delegate_id') }}:</label>
                    <input type="text" name="code"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0 bg-gray-200"
                        value="{{ old('code', $delegation->code ?? '') }}" readonly disabled>
                    @error('code')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('invitation_from') }}:</label>
                    <select name="invitation_from_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Invitation From') }}</option>
                        @foreach (getDropDown('internal_ranking')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('invitation_from_id', $delegation->invitation_from_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('invitation_from_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('continent') }}:</label>
                    <select name="continent_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Continent') }}</option>
                        @foreach (getDropDown('continents')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('continent_id', $delegation->continent_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('continent_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('country') }}:</label>
                    <select name="country_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Country') }}</option>
                        @foreach (getDropDown('country')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('country_id', $delegation->country_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
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
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Invitation Status') }}</option>
                        @foreach (getDropDown('invitation_status')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('invitation_status_id', $delegation->invitation_status_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('invitation_status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('participation_status') }}:</label>
                    <select name="participation_status_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled>{{ __('Select Participation Status') }}</option>
                        @foreach (getDropDown('participation_status')->options as $option)
                            <option value="{{ $option->id }}"
                                {{ old('participation_status_id', $delegation->participation_status_id) == $option->id ? 'selected' : '' }}>
                                {{ $option->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('participation_status_id')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-6">
                    <label class="form-label">{{ __db('note1') }}:</label>
                    <textarea name="note1" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500"
                        placeholder="Enter Note 1">{{ old('note1', $delegation->note1) }}</textarea>
                    @error('note1')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-6">
                    <label class="form-label">{{ __db('note2') }}:</label>
                    <textarea name="note2" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-neutral-300 focus:border-blue-500"
                        placeholder="Enter Note 2">{{ old('note2', $delegation->note2) }}</textarea>
                    @error('note2')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 mt-6">
                    <button type="submit"
                        class="btn !bg-[#B68A35] text-white rounded-lg py-3 px-6 font-semibold hover:shadow-lg transition">
                        {{ __db('update_delegation') }}
                    </button>
                </div>
            </div>
        </div>
    </form>



    <div class="flex items-center justify-between mt-6">

        <h2 class="font-semibold mb-0 !text-[22px] ">Delegates (20)</h2>

        <div class="flex items-center gap-3">

            <a href="delegate-new-add.html" id="add-attachment-btn"
                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-3 px-5">

                <span>Add Delegate</span>
            </a>

            <a href="submit-add-flight-details.html" id="add-attachment-btn"
                class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">

                <span>Add Group Arrival</span>
            </a>

            <a href="submit-add-flight-details.html" id="add-attachment-btn"
                class="btn text-sm border !border-[#B68A35] !text-[#B68A35] flex items-center rounded-lg py-3 px-5">

                <span>Add Group Departure</span>
            </a>
        </div>


    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <table class="table-auto mb-0 border-collapse border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Sl.No</th>
                            <th scope="col" class="p-3  !bg-[#B68A35] text-start text-white border !border-[#cbac71] ">
                                Title
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Name</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Designation</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Internal
                                Ranking</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Gender
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Parent ID
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Relationship</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Badge
                                Printed</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Participation Status</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Accommodation</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Arrival
                                Status </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">1</td>
                            <td class="px-4 border border-gray-200 py-3">Mr</td>
                            <td class="px-4 border border-gray-200 py-3">
                                <span
                                    class="bg-[#B68A35] font-semibold text-[10px] px-3 py-[1px] rounded-lg text-white">TH</span>
                                <div class="block">Mohammed Al Obaidi</div>
                            </td>
                            <td class="px-4 border border-gray-200 py-3">Technical Officer
                            </td>
                            <td class="px-4 border border-gray-200 py-3">Member</td>
                            <td class="px-4 border border-gray-200 py-3">Male</td>
                            <td class="px-4 border border-gray-200 py-3">-</td>
                            <td class="px-4 border border-gray-200 py-3">-</td>
                            <td class="px-4 border border-gray-200 py-3">Yes</td>
                            <td class="px-4 border border-gray-200 py-3">Waiting</td>
                            <td class="px-4 border border-gray-200 py-3">Marriott Hotel - Single Room - 409</td>
                            <td class="px-4 border border-gray-200 py-2">
                                <svg class=" cursor-pointer" width="30" height="30"
                                    data-modal-target="default-modal3" data-modal-toggle="default-modal3"
                                    viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <rect width="480" height="32" x="16" y="464"
                                            fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect>
                                        <path fill="var(--ci-primary-color, #B68A35)"
                                            d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z"
                                            class="ci-primary"></path>
                                    </g>
                                </svg>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">2</td>
                            <td class="px-4 py-3 border border-gray-200">Ms</td>
                            <td class="px-4 py-3 border border-gray-200">Fatima Al Zahra
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                Senior Engineer
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Major</td>
                            <td class="px-4 py-3 border border-gray-200">Female</td>
                            <td class="px-4 py-3 border border-gray-200">
                                Mohammed Al Obaidi
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Wife</td>
                            <td class="px-4 py-3 border border-gray-200">No</td>
                            <td class="px-4 py-3 border border-gray-200">Not Yet Arrived</td>
                            <td class="px-4 py-3 border border-gray-200">Hilton - Double Room - 203</td>
                            <td class="px-4 py-2 border border-gray-200">
                                <svg class=" cursor-pointer" width="30" height="30"
                                    data-modal-target="default-modal3" data-modal-toggle="default-modal3"
                                    viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <rect width="480" height="32" x="16" y="464"
                                            fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect>
                                        <path fill="var(--ci-primary-color, #B68A35)"
                                            d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z"
                                            class="ci-primary"></path>
                                    </g>
                                </svg>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">3</td>
                            <td class="px-4 py-3 border border-gray-200">Dr</td>
                            <td class="px-4 py-3 border border-gray-200">Ahmed Khalid
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                Military Doctor
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">Male</td>
                            <td class="px-4 py-3 border border-gray-200">-</td>
                            <td class="px-4 py-3 border border-gray-200">-</td>
                            <td class="px-4 py-3 border border-gray-200">Yes</td>
                            <td class="px-4 py-3 border border-gray-200">Arrived</td>
                            <td class="px-4 py-3 border border-gray-200">Ritz - Single Room - 110</td>
                            <td class="px-4 py-2 border border-gray-200">
                                <svg class=" cursor-pointer" width="30" height="30"
                                    data-modal-target="default-modal3" data-modal-toggle="default-modal3"
                                    viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <rect width="480" height="32" x="16" y="464"
                                            fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect>
                                        <path fill="var(--ci-primary-color, #B68A35)"
                                            d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z"
                                            class="ci-primary"></path>
                                    </g>
                                </svg>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">4</td>
                            <td class="px-4 py-3 border border-gray-200">Mr</td>
                            <td class="px-4 py-3 border border-gray-200">Laila Al Nuaimi
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                Special Advisor
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Minister</td>
                            <td class="px-4 py-3 border border-gray-200">Female</td>
                            <td class="px-4 py-3 border border-gray-200">-</td>
                            <td class="px-4 py-3 border border-gray-200">-</td>
                            <td class="px-4 py-3 border border-gray-200">Yes</td>
                            <td class="px-4 py-3 border border-gray-200">Departured</td>
                            <td class="px-4 py-3 border border-gray-200">Guesthouse - Double Room - 325</td>
                            <td class="px-4 py-2 border border-gray-200">
                                <svg class=" cursor-pointer" width="30" height="30"
                                    data-modal-target="default-modal3" data-modal-toggle="default-modal3"
                                    viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <rect width="480" height="32" x="16" y="464"
                                            fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect>
                                        <path fill="var(--ci-primary-color, #B68A35)"
                                            d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z"
                                            class="ci-primary"></path>
                                    </g>
                                </svg>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">5</td>
                            <td class="px-4 py-3 border border-gray-200">Dr</td>
                            <td class="px-4 py-3 border border-gray-200">Saeed Al Nuaimi
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Admin Assistant
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Member</td>
                            <td class="px-4 py-3 border border-gray-200">Male</td>
                            <td class="px-4 py-3 border border-gray-200">-</td>
                            <td class="px-4 py-3 border border-gray-200">-</td>
                            <td class="px-4 py-3 border border-gray-200">No</td>
                            <td class="px-4 py-3 border border-gray-200">Not Yet Arrived</td>
                            <td class="px-4 py-3 border border-gray-200">Marriott – Room 409</td>
                            <td class="px-4 py-2 border border-gray-200">
                                <svg class=" cursor-pointer" width="30" height="30"
                                    data-modal-target="default-modal3" data-modal-toggle="default-modal3"
                                    viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#B68A35">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <rect width="480" height="32" x="16" y="464"
                                            fill="var(--ci-primary-color, #B68A35)" class="ci-primary"></rect>
                                        <path fill="var(--ci-primary-color, #B68A35)"
                                            d="M455.688,152.164c-23.388-6.515-48.252-6.053-70.008,1.3l-.894.3-65.1,30.94L129.705,109.176a47.719,47.719,0,0,0-49.771,8.862L54.5,140.836a24,24,0,0,0,2.145,37.452l117.767,83.458-45.173,23.663L93.464,252.722a48.067,48.067,0,0,0-51.47-8.6l-19.455,8.435a24,24,0,0,0-11.642,33.3L83.718,422.684,480.3,227.21c23.746-11.177,26.641-29.045,21.419-42.059C495.931,170.723,479.151,158.7,455.688,152.164Zm10.9,46.133-.149.07L97.394,380.267l-54.176-101.8,11.5-4.987a16.021,16.021,0,0,1,17.157,2.867l52.336,47.819,111.329-58.318L83.322,157.974l17.971-16.108a15.908,15.908,0,0,1,16.59-2.954l202.943,80.681,75.95-36.095c15.456-5.009,33.863-5.165,50.662-.413,13.834,3.914,21.182,9.6,23.672,12.582A24.211,24.211,0,0,1,466.59,198.3Z"
                                            class="ci-primary"></path>
                                    </g>
                                </svg>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">Escorts</h2>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Sl.No</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Military
                                Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Title</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Name</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Mobile
                                Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Gender
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Known
                                Languages</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">1</td>
                            <td class="px-4 py-3 border border-gray-200">UM123</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">Amar Preet Singh</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 50 123 4567
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Male</td>
                            <td class="px-4 py-3 border border-gray-200">Arabic, English</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">2</td>
                            <td class="px-4 py-3 border border-gray-200">DX456</td>
                            <td class="px-4 py-3 border border-gray-200">HH</td>
                            <td class="px-4 py-3 border border-gray-200">Laila Al Kaabi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 55 234 7890
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Female</td>
                            <td class="px-4 py-3 border border-gray-200">Arabic</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">3</td>
                            <td class="px-4 py-3 border border-gray-200">AB789</td>
                            <td class="px-4 py-3 border border-gray-200">Major</td>
                            <td class="px-4 py-3 border border-gray-200">Yousef Al Ali</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 52 345 6789
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Male</td>
                            <td class="px-4 py-3 border border-gray-200">Arabic, English, French</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">4</td>
                            <td class="px-4 py-3 border border-gray-200">SH321</td>
                            <td class="px-4 py-3 border border-gray-200">Ms</td>
                            <td class="px-4 py-3 border border-gray-200">Sara Mansour</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 56 987 6543
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Female</td>
                            <td class="px-4 py-3 border border-gray-200">English</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr class="mx-6 border-neutral-200 h-10">
    <h2 class="font-semibold mb-0 !text-[22px] ">Drivers
    </h2>
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Sl.No</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Military
                                Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Title</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Name</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Mobile
                                Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Vehicle
                                Type</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Plate
                                Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Capacity
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">1</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-1024</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">Saeed Al Kaabi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 55 789 3210
                            </td>
                            <td class="px-4 py-3 border border-gray-200">SUV</td>
                            <td class="px-4 py-3 border border-gray-200">DXB 4567</td>
                            <td class="px-4 py-3 border border-gray-200">5</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">2</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-2548</td>
                            <td class="px-4 py-3 border border-gray-200">Mr</td>
                            <td class="px-4 py-3 border border-gray-200">Mohammed Al Obaidi</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 50 112 3344
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Sedan</td>
                            <td class="px-4 py-3 border border-gray-200">AUH 2345</td>
                            <td class="px-4 py-3 border border-gray-200">4</td>

                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">3</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-8789</td>
                            <td class="px-4 py-3 border border-gray-200">Ms</td>
                            <td class="px-4 py-3 border border-gray-200">Fatima Al Zahra</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 52 223 4455
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Hatchback</td>
                            <td class="px-4 py-3 border border-gray-200">SHJ 9876</td>
                            <td class="px-4 py-3 border border-gray-200">5</td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">4</td>
                            <td class="px-4 py-3 border border-gray-200">MIL-0024</td>
                            <td class="px-4 py-3 border border-gray-200">Captain</td>
                            <td class="px-4 py-3 border border-gray-200">John Doe</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">+971 58 667 8899
                            </td>
                            <td class="px-4 py-3 border border-gray-200">Crossover</td>
                            <td class="px-4 py-3 border border-gray-200">RAK 1234</td>
                            <td class="px-4 py-3 border border-gray-200">4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr class="mx-6 border-neutral-200 h-10">

    <div class="flex items-center justify-between mt-6">

        <h2 class="font-semibold mb-0 !text-[22px] ">Interviews</h2>

        <a href="submit-add-interview.html" id="add-attachment-btn"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-3 px-5">

            <span>Add Interview</span>
        </a>


    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Sl.No</th>
                            <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Date &
                                Time</th>
                            <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Attended
                                By</th>
                            <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Interview
                                With</th>
                            <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">Status
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">1</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">2025-06-18 10:00
                                AM</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <span class="block">Sr.Sara Al-Suwaidi</span>
                                <span class="block">Mr.Dalia Al-Hassan</span>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <a href="#" class="!text-[#B68A35]" data-modal-target="DelegationModal"
                                    data-modal-toggle="DelegationModal"> Delegation ID : DA25-002</a>
                                <span class="block">Khalid</span>
                                <span class="block">Omar</span>
                            </td>
                            <td class="px-4 py-3 text-black border border-gray-200">Pending</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">2</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">2025-07-11 11:30
                                AM</td>
                            <td class="px-4 py-3 border border-gray-200">Dr.Bandar bin Abdulaziz </td>
                            <td class="px-4 py-3 border border-gray-200">Hadi</td>
                            <td class="px-4 py-3 text-black border border-gray-200">Accepted</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">3</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">2025-06-26 01:00
                                PM</td>
                            <td class="px-4 py-3 border border-gray-200">Mr. Crispus Kiyonga, </td>
                            <td class="px-4 py-3 border border-gray-200">Rashed</td>
                            <td class="px-4 py-3 text-black border border-gray-200">Canceled</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-sm align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">4</td>
                            <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">2025-09-14 02:30
                                PM</td>
                            <td class="px-4 py-3 border border-gray-200">Mr.Mark Carney </td>
                            <td class="px-4 py-3 border border-gray-200">Zayed</td>
                            <td class="px-4 py-3 text-black border border-gray-200">Completed</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                        <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </a>
                                    <a href="delegate-view-edit.html">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
