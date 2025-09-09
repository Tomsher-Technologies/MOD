<div class="dashboard-main-body">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('edit') . ' ' . __db('driver') }}</h2>
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
        <form id="driver-form" action="{{ route('drivers.update', $driver->id) }}" method="POST" data-ajax-form="true">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-5">

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title_en') }}:</label>
                    <input type="text" name="title_en"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('title_en', $driver->title_en) }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('title_ar') }}:</label>
                    <input type="text" name="title_ar"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('title_ar', $driver->title_ar) }}">
                </div>


                <div class="col-span-4">
                    <label class="form-label">{{ __db('military_number') }}:</label>
                    <input type="text" name="military_number"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('military_number', $driver->military_number) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_ar') }}:</label>
                    <input type="text" name="name_ar"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('name_ar', $driver->name_ar) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('name_en') }}:</label>
                    <input type="text" name="name_en"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('name_en', $driver->name_en) }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('phone_number') }}:</label>
                    <input type="text" name="phone_number"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}" inputmode="numeric" pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        value="{{ old('phone_number', $driver->phone_number) }}">
                </div>

                {{-- <div class="col-span-4">
                    <label class="form-label">{{ __db('driver') . ' ' . __db('id') }}:</label>
                    <input type="text" name="driver_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('driver_id', $driver->driver_id) }}">
                </div> --}}
                <div class="col-span-4">
                    <label class="form-label">{{ __db('vehicle_type') }}:</label>
                    <input type="text" name="car_type"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('car_type', $driver->car_type) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('vehicle_number') }}:</label>
                    <input type="text" name="car_number"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('car_number', $driver->car_number) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">{{ __db('capacity') }}:</label>
                    <input type="text" name="capacity"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('capacity', $driver->capacity) }}">
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('unit') }}:</label>
                    <select name="unit_id"
                        class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option selected disabled>{{ __db('select') . ' ' . __db('unit') }}</option>
                        @foreach (getDropDown('unit')->options ?? [] as $unit)
                            <option value="{{ $unit->id }}"
                                {{ old('unit', $driver->unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->value }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-span-4">
                    <label class="form-label">{{ __db('status') }}:</label>
                    <select name="status"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="1" {{ old('status', $driver->status) == 1 ? 'selected' : '' }}>
                            {{ __db('active') }}
                        </option>
                        <option value="0" {{ old('status', $driver->status) == 0 ? 'selected' : '' }}>
                            {{ __db('inactive') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-span-4 mt-5">
                <label class="form-label">{{ __db('note_1') }}:</label>
                <textarea id="message" rows="4" name="note1"
                    class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white">{{ old('note1', $driver->note1) }}</textarea>
                @error('note1')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-between items-center mt-8">
                <button type="submit"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">{{ __db('save') }}</button>
            </div>
        </form>
    </div>

    @php
        $hasActiveAssignment = $driver->delegations()->where('status', 1)->exists();
    @endphp

    <h2 class="font-semibold !text-[22px] mt-6 mb-6">
        {{ $hasActiveAssignment ? __db('reassign') : __db('assign') }}
    </h2>

    <x-assign-delegation-to-driver :driver="$driver" />
</div>
