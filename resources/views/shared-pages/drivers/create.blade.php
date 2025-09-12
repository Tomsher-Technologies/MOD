<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('add') . ' ' . __db('driver') }}</h2>
        <a href="{{ route('drivers.index') }}"
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

    <div class="bg-white h-full w-full rounded-lg border-0 p-6">

        <form action="{{ route('drivers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title_en') }}:</label>
                    <input type="text" name="title_en" value="{{ old('title_en') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">

                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title_ar') }}:</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">

                </div>


                <div class="col-span-4">
                    <label class="form-label">{{ __db('military_number') }}:</label>
                    <input type="text" name="military_number" value="{{ old('military_number') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">

                </div>

                <div class="col-span-4">

                    <label class="form-label">{{ __db('name_en') }}: <span class="text-red-600">*</span></label>

                    <input type="text" name="name_en" value="{{ old('name_en') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                </div>

                <div class="col-span-4">

                    <label class="form-label">{{ __db('name_ar') }}: <span class="text-red-600">*</span></label>

                    <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('أدخل الاسم هنا') }}">
                </div>
                

                <div class="col-span-4">

                    <label class="form-label">{{ __db('phone_number') }}:</label>
                    <div class="flex">
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                            class="p-3 w-full border text-sm border-neutral-300 border-l-0 text-neutral-600 focus:border-primary-600 focus:ring-0 ltr"
                            placeholder="501234567" inputmode="numeric" pattern="[0-9]{9}" maxlength="9" minlength="9"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)" dir="ltr" />
                        <span class="inline-flex items-center px-3  border-neutral-300 bg-gray-50 border   border-r-0 border-l-1 text-gray-500 text-sm">+971</span>
                    </div>
                </div>

                {{-- <div class="col-span-4">
                    <label class="form-label">{{ __db('driver') . ' ' . __db('id') }}:</label>
                    <input type="text" name="driver_id"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                </div> --}}

                <div class="col-span-4">
                    <label class="form-label">{{ __db('vehicle') . ' ' . __db('type') }}:</label>
                    <input type="text" name="car_type" value="{{ old('car_type') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('vehicle') . ' ' . __db('number') }}:</label>
                    <input type="text" name="car_number" value="{{ old('car_number') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('capacity') }}:</label>
                    <input type="text" name="capacity" value="{{ old('capacity') }}"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') . ' ' . __db('capacity') }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('unit') }}:</label>
                    <select name="unit_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option disabled {{ old('unit_id') ? '' : 'selected' }}>{{ __db('Select Unit') }}
                        </option>
                        @foreach (getDropDown('unit')->options ?? [] as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->value }}
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

            <div class="col-span-4 mt-5">
                <label class="form-label">{{ __db('note_1') }}:</label>
                <textarea id="message" rows="4" name="note1"
                    class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white">{{ old('note1', '') }}</textarea>
                @error('note1')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-between items-center mt-8">
                <!-- Add Delegate Button -->
                <button type="submit" id="add-delegates"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">{{ __db('add') . ' ' . __db('driver') }}</button>

            </div>
        </form>
    </div>
</div>
