<div class="dashboard-main-body">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('edit') . ' ' . __db('accommodation') }}</h2>
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
        <form id="accommodationForm" action="{{ route('accommodations.update', $accommodation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-4">
                    <label class="form-label">{{ __db('hotel_name') }} ({{ __db('english') }}) <span class="text-red-600">*</span>:</label>
                    <input type="text" name="hotel_name" id="hotel_name"
                        value="{{ old('hotel_name', $accommodation->hotel_name) }}"
                        class="hotel-name-group p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                    @error('hotel_name')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('hotel_name') }} ({{ __db('arabic') }}) <span class="text-red-600">*</span>:</label>
                    <input type="text" name="hotel_name_ar" id="hotel_name_ar"
                        value="{{ old('hotel_name_ar', $accommodation->hotel_name_ar) }}"
                        class="hotel-name-group p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                    @error('hotel_name_ar')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('contact_number') }}:</label>
                    <input type="text" name="contact_number" id="contact_number"
                        value="{{ old('contact_number', $accommodation->contact_number) }}"
                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                        placeholder="{{ __db('enter') }}">
                    @error('contact_number')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label class="form-label">{{ __db('address') }}:</label>
                    <textarea name="address" id="address" rows="3"
                        class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-neutral-300 focus:border-blue-500 bg-white"
                        placeholder="{{ __db('enter') }}">{{ old('address', $accommodation->address) }}</textarea>
                    @error('address')
                        <div class="text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Existing Rooms --}}
                <div id="room-container" class="col-span-12">
                    @foreach ($accommodation->rooms as $index => $room)
                        <div class="grid grid-cols-12 gap-5 room-row mt-2">
                            <div class="col-span-4">
                                <label class="form-label">{{ __db('room_type') }}:</label>
                                <input type="hidden" name="rooms[{{ $index }}][id]" value="{{ $room->id }}">
                                <select name="rooms[{{ $index }}][room_type]" class="select2 p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    <option value="">{{ __db('choose_option') }}</option>
                                    @foreach ($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}" {{ $roomType->id == $room->room_type ? 'selected' : '' }}>
                                            {{ $roomType->value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("rooms.$index.room_type")
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-4">
                                <label class="form-label">{{ __db('total_rooms') }}:</label>
                                <input type="number" name="rooms[{{ $index }}][total_rooms]" value="{{ $room->total_rooms }}"
                                    class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                @error("rooms.$index.total_rooms")
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3 flex items-end">
                                <button type="button" data-id="{{ $room->id }}" data-total="{{ $room->total_rooms }}" data-assigned="{{ $room->assigned_rooms }}" class="delete-room bg-red-600 text-white px-4 py-2 rounded">
                                    {{ __db('delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
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

                {{-- Existing Contacts --}}
                <div id="attachment-container" class="col-span-12">
                    @foreach ($accommodation->contacts as $cIndex => $contact)
                        <div class="grid grid-cols-12 gap-5 attachment-row mt-2">
                            <div class="col-span-4">
                                <label class="form-label">{{ __db('contact_person_name') }}:</label>
                                <input type="text" name="contacts[{{ $cIndex }}][name]" value="{{ $contact->name }}"
                                    class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                @error("contacts.$cIndex.name")
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-4">
                                <label class="form-label">{{ __db('contact_number') }}:</label>
                                <input type="text" name="contacts[{{ $cIndex }}][phone]" value="{{ $contact->phone }}"
                                    class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                @error("contacts.$cIndex.phone")
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3 flex items-end">
                                <button type="button" class="delete-contact bg-red-600 text-white px-4 py-2 rounded">
                                    {{ __db('delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
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
                <button type="submit" id="update-accommodation" class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg">
                    {{ __db('update') }}
                </button>
            </div>
        </form>
    </div>
</div>