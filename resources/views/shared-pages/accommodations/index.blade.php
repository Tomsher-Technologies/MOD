<div class="dashboard-main-body ">

    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('accommodations') }}</h2>

        <div>
            @canany(['add_accommodations', 'hotel_add_accommodations'])
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                    class="btn !text-[#B68A35] !bg-[#E6D7A2]  text-md rounded-lg px-6 py-3 text-center inline-flex items-center"
                    type="button">
                    {{ __db('add') . ' ' . __db('hotel') }}
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44">
                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                        <li>
                            <a href="{{ route('accommodations.create') }}"
                                class="block px-4 py-2 hover:bg-gray-100">{{ __db('add') . ' ' . __db('hotel') .' '.
                                __db('manually') }}</a>
                        </li>
                        @can('import_accommodations')
                            <li>
                                <a href="{{ route('accommodations.import.form') }}" class="block px-4 py-2 hover:bg-gray-100">{{ __db('add') . ' ' .
                                    __db('hotel') .' '. __db('bulk') }}</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany


            @canany(['view_external_members', 'assign_external_members'])
                <a href="{{ route('admin.view-external-members') }}" class="btn text-md ms-4 mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg ">{{ __db('view'). ' ' . __db('external_member') }}</a>
            @endcan

        </div>

    </div>

    <!-- Escorts -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr class="text-[10px]">
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('sl_no') }}
                            </th>
                            <th scope="col" class="p-3 w-[15%] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('hotel_name') }}
                            </th>
                            <th scope="col" class="p-3 w-[15%] !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
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
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                {{ __db('actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ( $accommodations as $key => $hotel )
                            <tr class="text-xs align-[middle]">
                                <td class="px-3 py-3 border border-gray-200">
                                    {{ $accommodations->firstItem() + $key }}
                                </td>
                                <td class="px-3 py-3 border border-gray-200 !text-[#B68A35]">
                                    <a href="#">
                                        {{ $hotel->hotel_name ?? "-" }}
                                    </a>
                                </td>
                                <td class="px-3 py-3 border border-gray-200">
                                    {{ $hotel->address ?? "-" }}
                                </td>
                                <td class="px-3 py-3 text-end border border-gray-200" dir="ltr">
                                    {{ $hotel->contact_number ?? "-" }}
                                </td>
                                <td class="px-3 py-3 border border-gray-200">
                                    @if($hotel->contacts)
                                        @foreach ($hotel->contacts as $contact_person)
                                            <div class="mb-2">{{ $contact_person->name ?? "-" }} - {{ $contact_person->phone ?? "-" }}</div>
                                        @endforeach
                                    @endif
                                </td>

                                <td class="px-3 py-3 border border-gray-200">
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
                                <td class="px-3 py-3 border border-gray-200">
                                    {{ $assigned_rooms }}/{{ $total_rooms }}
                                </td>
                                
                                <td class="px-3 py-2 border border-gray-200">
                                    <div class="flex align-center gap-4">
                                        @canany(['edit_accommodations', 'hotel_edit_accommodations'])
                                            <a href="{{ route('accommodations.edit', ['id' => base64_encode($hotel->id)]) }}" title="{{ __db('edit_hotel') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 512 512">
                                                    <path
                                                        d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                        fill="#B68A35"></path>
                                                </svg>
                                            </a>
                                        @endcanany
                                        
                                        @canany(['assign_external_members'])
                                            <a href="{{ route('external_accommodations.add', ['id' => base64_encode($hotel->id)]) }}" title="{{ __db('add_external_accommodation') }}">
                                                <svg class=" text-[#B68A35]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"  width="20" height="20" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778"/>
                                                </svg>
                                            </a>
                                        @endcanany

                                        {{-- <a href="#" data-modal-target="deleteModal" data-modal-toggle="deleteModal">
                                            <svg class="w-5.5 h-5.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="#B68A35" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z">
                                                </path>
                                            </svg>
                                        </a> --}}

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td class="px-3 py-3 text-center " colspan="5" dir="ltr">
                                    {{ __db('no_data_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $accommodations->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')

@endsection