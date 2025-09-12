<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('add') . ' ' . __db('accommodation') }}</h2>
        <a href="{{ route('accommodations.index') }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>
    <div class="bg-white h-full w-full rounded-lg border-0 p-6 mb-10">
        <form id="accommodationForm" action="{{ route('accommodations.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-4">
                    <label class="form-label">{{ __db('hotel_name') }} ({{ __db('english') }}) <span class="text-red-600">*</span>:</label>
                    <input type="text" name="hotel_name" id="hotel_name"
                        class="hotel-name-group p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                    @error('hotel_name')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('hotel_name') }} ({{ __db('arabic') }}) <span class="text-red-600">*</span>:</label>
                    <input type="text" name="hotel_name_ar" id="hotel_name_ar"
                        class="hotel-name-group p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                    @error('hotel_name_ar')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('contact_number') }}:</label>
                    <input type="text" name="contact_number" id="contact_number"
                        class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                    @error('contact_number')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('address') }}:</label>
                    <textarea  name="address" id="address" rows="3" class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white" 
                        placeholder="{{ __db('enter') }}"></textarea>
                    @error('address')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div id="room-container" class="col-span-12">

                    <div class="grid grid-cols-12 gap-5 room-row">
                        
                        <div class="col-span-4">
                            <label class="form-label">{{ __db('room_type') }}:</label>
                            <select name="rooms[0][room_type]" id="room_type" class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                                <option value="">{{ __db('select') }}</option>
                                @foreach ($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}">{{ $roomType->value }}</option>
                                @endforeach
                            </select>
                            @error('rooms.0.room_type')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Rooms -->
                        <div class="col-span-4">
                            <label class="form-label">{{ __db('total_rooms') }}:</label>
                            <input type="text" name="rooms[0][total_rooms]" id="total_rooms"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            placeholder="{{ __db('enter') }}">
                            @error('rooms.0.total_rooms')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Delete Button (hidden on first row) -->
                        <div class="col-span-3 flex items-end">
                            <button type="button"
                            class="hidden delete-row bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            {{ __db('delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 mb-10">
                    <button type="button" id="add-room-btn"
                        class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                        <svg class="w-6 h-6 text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                        </svg>
                        <span>{{ __db('add') . ' ' . __db('room') }}</span>
                    </button>
                </div>

                <div id="attachment-container" class="col-span-12">
                    <div class=" grid grid-cols-12 gap-5 attachment-row">
                        <div class="col-span-4">
                            <label class="form-label">{{ __db('contact_person_name') }}:</label>
                            <input type="text" name="contacts[0][name]" id="name"
                            class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            placeholder="{{ __db('enter') }}">

                            @error('contacts.0.name')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="col-span-4">
                            <label class="form-label">{{ __db('contact_number') }}:</label>
                            <input type="text" name="contacts[0][phone]" id="phone"
                            class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            placeholder="{{ __db('enter') }}">

                            @error('contacts.0.phone')
                                <div class="text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
              
                        <div class="col-span-3 flex items-end">
                            <button type="button"
                            class="hidden delete-row bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            {{ __db('delete') }}
                            </button>
                        </div>
                    </div>
                </div>
         
                <div class="col-span-12 mb-10">
                    <button type="button" id="add-attachment-btn"
                        class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                        <svg class="w-6 h-6 text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                        </svg>
                        <span>{{ __db('add') . ' ' . __db('contact_person') }}</span>
                    </button>
                </div>

            </div>

            <hr>

            <div class="flex justify-between items-center mt-8">
                <button type="submit" id="add-delegates"  class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg ">{{ __db('submit') }}</button>
            </div>
        </form>
    </div>
</div>
