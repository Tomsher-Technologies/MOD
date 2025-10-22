<div>

    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">{{ __db('accommodations') }}</h2>

        <div>
 
            @directCanany(['add_accommodations', 'hotel_add_accommodations', 'import_accommodations', 'hotel_import_accommodations'])
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
                                class="block px-4 py-2 hover:bg-gray-100">{{ __db('add') . ' ' . __db('hotel') . ' ' . __db('manually') }}</a>
                        </li>
                        @directCanany(['import_accommodations'])
                            <li>
                                <a href="{{ route('accommodations.import.form') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">{{ __db('add') . ' ' . __db('hotel') . ' ' . __db('bulk') }}</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @enddirectCanany

            @directCanany(['view_external_members', 'assign_external_members','hotel_view_external_members', 'hotel_assign_external_members'])
                <a href="{{ route('admin.view-external-members') }}"
                    class="btn text-md ms-4 mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg ">{{ __db('view') . ' ' . __db('external_member') }}</a>
            @enddirectCanany
        </div>
    </div>

    <!-- Escorts -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class=" mb-4 flex items-center justify-between gap-3">
                <form class="w-[50%] me-4" action="{{ route('accommodations.index') }}" method="GET">
                    <div class="relative">

                        @foreach (request()->except(['search', 'page']) as $k => $v)
                            @if (is_array($v))
                                @foreach ($v as $vv)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="default-search" name="search" value="{{ request('search') }}" autocomplete="off"
                            class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                            placeholder="{{ __db('search_by_name') }}" />
                        <button type="submit"
                            class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                         <a href="{{ route('accommodations.index') }}"  class="absolute end-[85px]  bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                        {{ __db('reset') }}</a>
                    </div>
                </form>

                <div class="text-center flex">
                    <form method="GET" class="ml-4 mt-2">
                        @foreach (request()->except('limit', 'page') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $subKey => $subValue)
                                    <input type="hidden" name="{{ $key }}[{{ $subKey }}]"
                                        value="{{ $subValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <select id="limit" name="limit" onchange="this.form.submit()"
                            class="border text-secondary-light text-xs !border-[#d1d5db] rounded px-5 py-1 !pe-7">
                            @foreach ([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('limit', 10) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        <span class="mr-2 text-sm">{{ __db('rows') }}</span>
                    </form>
                    
                    <button
                        class="text-white flex items-center gap-1 !bg-[#B68A35] hover:bg-[#A87C27] focus:ring-4 focus:ring-yellow-300 font-sm rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                        type="button" data-drawer-target="filter-drawer" data-drawer-show="filter-drawer"
                        aria-controls="filter-drawer">
                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                                d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
                        </svg>
                        <span>{{ __db('filter') }}</span>
                    </button>
                </div>
            </div>


                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr class="text-[13px]">
                            <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('sl_no') }}
                            </th>
                            <th scope="col"
                                class="p-3 w-[15%] !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('hotel_name') }} ({{ __db('english') }})
                            </th>
                            <th scope="col"
                                class="p-3 w-[15%] !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('hotel_name') }} ({{ __db('arabic') }})
                            </th>
                            <th scope="col"
                                class="p-3 w-[15%] !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('address') }}
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('contact_number') }}
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('contact_point') }}
                            </th>

                            <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('room_type') }}
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('total_capacity') }}
                            </th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                {{ __db('actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ( $accommodations as $key => $hotel )
                            <tr class="text-[12px] align-[middle]">
                                <td class="text-center px-3 py-3 border border-gray-200">
                                    {{ $accommodations->firstItem() + $key }}
                                </td>
                                <td class="text-center px-3 py-3 border border-gray-200">
                                    @directCanany(['view_accommodations', 'delegate_view_accommodations', 'escort_view_accommodations','driver_view_accommodations','hotel_view_accommodations'])
                                        <a href="{{ route('accommodations.show', base64_encode($hotel->id)) }}" class="text-[#B68A35] hover:underline">
                                            
                                    @enddirectCanany

                                    {{ $hotel->getRawOriginal('hotel_name') ?? '-' }}

                                    @directCanany(['view_accommodations', 'delegate_view_accommodations', 'escort_view_accommodations','driver_view_accommodations','hotel_view_accommodations'])
                                        </a>
                                    @enddirectCanany
                                </td>

                                <td class="text-center px-3 py-3 border border-gray-200">
                                    @directCanany(['view_accommodations', 'delegate_view_accommodations', 'escort_view_accommodations','driver_view_accommodations','hotel_view_accommodations'])
                                        <a href="{{ route('accommodations.show', base64_encode($hotel->id)) }}" class="text-[#B68A35] hover:underline">
                                            
                                    @enddirectCanany

                                    {{ $hotel->getRawOriginal('hotel_name_ar') ?? '-' }}
                                    
                                    @directCanany(['view_accommodations', 'delegate_view_accommodations', 'escort_view_accommodations','driver_view_accommodations','hotel_view_accommodations'])
                                        </a>
                                    @enddirectCanany
                                </td>

                                <td class="text-center px-3 py-3 border border-gray-200">
                                    {{ $hotel->address ?? '-' }}
                                </td>
                                <td class="text-center px-3 py-3 border border-gray-200" dir="ltr">
                                    {{ $hotel->contact_number ?? '-' }}
                                </td>
                                <td class="text-center px-3 py-3 border border-gray-200">
                                    @if ($hotel->contacts)
                                        @foreach ($hotel->contacts as $contact_person)
                                            <div class="mb-2">{{ $contact_person->name }} -
                                                {{ $contact_person->phone  }}</div>
                                        @endforeach
                                    @endif
                                </td>

                                <td class="text-center px-3 py-3 border border-gray-200">
                                    @php
                                        $total_rooms = 0;
                                        $assigned_rooms = 0;
                                    @endphp
                                    @if ($hotel->rooms)
                                        @foreach ($hotel->rooms as $room)
                                            <div class="mb-2">{{ $room->roomType?->value ?? "N/A" }} -
                                                {{ $room->assigned_rooms ?? 0 }}/{{ $room->total_rooms ?? 0 }}</div>
                                            @php
                                                $total_rooms += $room->total_rooms;
                                                $assigned_rooms += $room->assigned_rooms;
                                            @endphp
                                        @endforeach
                                    @endif

                                </td>
                                <td class="text-center px-3 py-3 border border-gray-200">
                                    {{ $assigned_rooms }}/{{ $total_rooms }}
                                </td>

                                <td class="text-center px-3 py-2 border border-gray-200">
                                    <div class="flex align-center gap-1">
                                        @directCanany(['edit_accommodations', 'hotel_edit_accommodations'])
                                            <a href="{{ route('accommodations.edit', ['id' => base64_encode($hotel->id)]) }}"
                                                title="{{ __db('edit_hotel') }}" class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 512 512">
                                                    <path
                                                        d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                        fill="#B68A35"></path>
                                                </svg>
                                            </a>
                                        @enddirectCanany

                                        @directCanany(['assign_external_members','hotel_assign_external_members'])
                                            <a href="{{ route('external_accommodations.add', ['id' => base64_encode($hotel->id)]) }}"
                                                title="{{ __db('add_external_accommodation') }}" class="w-8 h-8 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                                <svg class=" text-[#B68A35]" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778" />
                                                </svg>
                                            </a>
                                        @enddirectCanany

                                        @directCanany(['view_accommodations', 'delegate_view_accommodations', 'escort_view_accommodations','driver_view_accommodations','hotel_view_accommodations'])
                                            <a href="{{ route('accommodations.show', base64_encode($hotel->id)) }}" class="w-8 h-8  text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                                <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 16 12' fill='none'><path d='M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z' stroke='#7C5E24' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' /><path d='M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z' stroke='#7C5E24' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></svg>
                                            </a>
                                        @enddirectCanany
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td class="text-center px-3 py-3" colspan="5" dir="ltr">
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

    <div id="filter-drawer"
            class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80"
            tabindex="-1" aria-labelledby="drawer-label">
            <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
                {{ __db('filter') }}</h5>
            <button type="button" data-drawer-hide="filter-drawer" aria-controls="filter-drawer"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">{{ __db('close_menu') }}</span>
            </button>

            <form action="{{ route('accommodations.index') }}" method="GET">

                @foreach (request()->except(['page']) as $k => $v)
                    @if (is_array($v))
                        @foreach ($v as $vv)
                            <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endif
                @endforeach
                <div class="flex flex-col gap-2 mt-2">
                    <div class="flex flex-col">
                        <label class="form-label block mb-1 text-gray-700 font-bold">{{ __db('room_type') }}</label>
                        <select name="room_type_id" id="room_type_id" data-placeholder="{{ __db('select') }}"
                            class="select2 w-full rounded-lg border border-gray-300 text-sm">
                            <option value=" ">{{ __db('all') }}</option>
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" @if (request('room_type_id') == $roomType->id) selected @endif>
                                    {{ $roomType->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('accommodations.index') }}"
                        class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
                    <button type="submit"
                        class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
                </div>
            </form>
        </div>
</div>
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                placeholder: "{{ __db('select') }}",
                allowClear: true
            });

          
        });
    </script>
@endsection
