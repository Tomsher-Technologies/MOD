@extends('layouts.admin_account', ['title' => __db('edit') . ' ' . __db('external_accommodations')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{__db('edit') . ' ' . __db('external_accommodations') }}</h2>
        <a href="{{ session()->get('external_members_last_url') ? session()->get('external_members_last_url') : route('external_members.index') }}"
            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>
    <!-- DAdd Delegation -->
    <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
        <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
            <thead>
                <tr>
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('hotel_name') }}
                    </th>
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ __db('address') }}
                    </th>
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ __db('contact_number') }}
                    </th>
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ __db('contact_point') }}
                    </th>

                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ __db('room_type') }}
                    </th>
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ __db('total_capacity') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-xs align-[middle]">
                    <td class="px-4 py-3 border border-gray-200">
                        {{ $hotel->hotel_name ?? '' }}
                    </td>
                    <td class="px-4 py-3 border border-gray-200">{{ $hotel->address ?? "-" }}</td>
                    <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">{{ $hotel->contact_number ?? "-" }}</td>
                    <td class="px-4 py-3 border border-gray-200">
                        @if($hotel->contacts)
                            @foreach ($hotel->contacts as $contact_person)
                                <div class="mb-2">{{ $contact_person->name ?? "-" }} - {{ $contact_person->phone ?? "-" }}</div>
                            @endforeach
                        @endif
                    </td>

                    <td class="px-4 py-3 border border-gray-200">
                        @php
                            $total_rooms = 0;
                            $assigned_rooms = 0;
                        @endphp
                        @if($hotel->rooms)
                            @foreach ($hotel->rooms as $room)
                                <div class="mb-2">{{ $room->roomType->value ?? "-" }} - {{ $room->assigned_rooms }}/{{ $room->total_rooms }}</div>
                                @php
                                    $total_rooms += $room->total_rooms;
                                    $assigned_rooms += $room->assigned_rooms;
                                @endphp
                            @endforeach
                        @endif
                    </td>
                    <td class="px-4 py-3 border border-gray-200">{{ $assigned_rooms }}/{{ $total_rooms }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="MembersContainer" class="space-y-4 mt-6 mb-5"></div>

    <form action="{{ route('admin.external-members.update', $externalMember->id) }}" method="POST" class="space-y-4 mt-6 mb-5" id="MembersForm">
        @csrf
        @method('PUT')
        <div class="member-block bg-white w-full rounded-lg border p-4 mb-3">
            <div class="grid grid-cols-12 md:grid-cols-12 gap-4 items-end">
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                <div class="col-span-12 md:col-span-3">
                    <label class="block text-sm text-gray-700 mb-1">{{ __db('name') }}</label>
                    <input type="text" placeholder="{{ __db('enter') }}"  name="name"  value="{{ old('name', $externalMember->name ?? '') }}"
                        class="w-full text-secondary-light p-3 text-sm !border-[#d1d5db] rounded-lg" />
                     @error('name')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="block text-sm text-gray-700 mb-1">{{ __db('room_type') }}</label>
                    <select name="room_type" id="room_type" class="p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <option value="">{{ __db('select') }}</option>
                        @foreach ($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ old('room_type', $externalMember->room_type_id) == $roomType->id ? 'selected' : '' }}>{{ $roomType->roomType?->value }}</option>
                            {{-- <option value="'.$roomType->id.'" '.($roomType->id == $room->room_type_id ? 'selected' : '').'>'..'</option> --}}
                        @endforeach
                    </select>
                    @error('room_type')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="block text-sm text-gray-700 mb-1">Room</label>
                    <input type="text"  name="room_number" id="room_number" placeholder="{{ __db('enter') }}" class="w-full text-secondary-light p-3 text-sm !border-[#d1d5db] rounded-lg" value="{{ old('room_number', $externalMember->room_number ?? '') }}"/>
                    @error('room_number')
                        <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                {{-- 
                <div class="mt-4 col-span-12 md:col-span-3" id="buttonsRepeat">
                    <button type="button" onclick="removeMember(this)"
                        class=" bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 h-12 ms-2">
                        {{ __db('remove') }}
                    </button>
                </div> --}}
            </div>

            @error('room_error')
                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn text-md !bg-[#B68A35] text-white rounded-lg h-12">
            {{ __db('update') . ' ' . __db('member') }}
        </button>
    </form>

</div>
@endsection

@section('script')
<script>
    
</script>
@endsection