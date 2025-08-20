<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('Add Escorts') }}</h2>
        <a href="{{ route('escorts.index') }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('Back') }}</span>
        </a>
    </div>
    <div class="bg-white h-full w-full rounded-lg border-0 p-6 mb-10">
        <form action="{{ route('escorts.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-4">
                    <label class="form-label">{{ __db('Military Number') }}:</label>
                    <input type="text" name="military_number"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('Enter Military Number') }}">

                </div>

                {{-- <div class="col-span-4">
                    <label class="form-label">{{ __db('Delegation') }}:</label>
                    <select name="delegation_id"
                        class="p-3 rounded-lg w-full text-sm border border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected="" disabled="">{{ __db('Select Delegation') }}</option>
                        @foreach ($delegations as $delegation)
                            <option value="{{ $delegation->id }}">{{ $delegation->code }}</option>
                        @endforeach
                    </select>
                </div> --}}


                <div class="col-span-3">
                    <label class="form-label">{{ __db('Name AR') }}:</label>
                    <input type="text" name="name_ar"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('أدخل الاسم هنا') }}">
                </div>
                <div class="col-span-3">
                    <label class="form-label">{{ __db('Name EN') }}:</label>
                    <input type="text" name="name_en"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('Enter Name Here') }}">
                </div>

                <div class="col-span-3">
                    <label class="form-label">{{ __db('Gender') }}:</label>
                    <select name="gender_id"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('Select Gender') }}</option>
                        @foreach (getDropDown('gender')->options as $gender)
                            <option value="{{ $gender->id }}">{{ $gender->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('Spoken Languages') }}:</label>
                    <select name="spoken_languages[]" id="multiSelect" multiple
                        placeholder="{{ __db('Select Languages') }}"
                        class="w-full p-3 rounded-lg border border-gray-300 text-sm">
                        {{-- @foreach (getDropDown('languages')->options as $language)
                            <option value="{{ $language->id }}">{{ $language->value }}</option>
                        @endforeach --}}
                    </select>
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('Rank') }}:</label>
                    <select name="rank"
                        class=" p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('Select Rank') }}</option>
                        {{-- Assuming rank is a dropdown option --}}
                        @foreach (getDropDown('internal_ranking')->options as $rank)
                            <option value="{{ $rank->id }}">{{ $rank->value }}</option>
                        @endforeach
                    </select>
                </div>


            </div>

            <div class="flex justify-between items-center mt-8">
                <!-- Add Delegate Button -->
                <button type="submit" id="add-delegates"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">{{ __db('Add Escorts') }}</button>

            </div>
        </form>
    </div>
</div>
