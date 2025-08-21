<div class="dashboard-main-body">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">Edit Driver</h2>
        <a href="{{ route('drivers.index') }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>Back</span>
        </a>
    </div>
    <!-- Drivers -->
    <div class="bg-white h-full w-full rounded-lg border-0 p-6">
        <form id="driver-form" action="{{ route('drivers.update', $driver->id) }}" method="POST" data-ajax-form="true">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-4">
                    <label class="form-label">Military Number:</label>
                    <input type="text" name="military_number"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('military_number', $driver->military_number) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Name AR:</label>
                    <input type="text" name="name_ar"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('name_ar', $driver->name_ar) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Name EN:</label>
                    <input type="text" name="name_en"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('name_en', $driver->name_en) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Mobile Number:</label>
                    <input type="text" name="mobile_number"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('mobile_number', $driver->mobile_number) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Driver ID:</label>
                    <input type="text" name="driver_id"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('driver_id', $driver->driver_id) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Car Type:</label>
                    <input type="text" name="car_type"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('car_type', $driver->car_type) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Car Number:</label>
                    <input type="text" name="car_number"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('car_number', $driver->car_number) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Capacity:</label>
                    <input type="text" name="capacity"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        value="{{ old('capacity', $driver->capacity) }}">
                </div>
                <div class="col-span-4">
                    <label class="form-label">Status:</label>
                    <select name="status"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="1" {{ old('status', $driver->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $driver->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-between items-center mt-8">
                <button type="submit"
                    class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">Save</button>
            </div>
        </form>
    </div>

    <h2 class="font-semibold mb-0 !text-[22px] mt-6">Reassign</h2>
    <x-assign-delegation-to-driver :driver="$driver" />
</div>