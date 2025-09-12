@extends('layouts.admin_account', ['title' => __db('view') . ' ' . __db('external_accommodations')])

@section('content')
    <div class="">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('external_accommodations') }}</h2>
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
       
        <!-- DAdd Delegation -->
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

            <div class=" mb-4 flex items-center justify-between gap-3">
                <form class="w-[50%] me-4" action="{{ route('admin.view-external-members') }}" method="GET">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="default-search" name="search" value="{{ request('search') }}"
                            class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg "
                            placeholder="{{ __db('search_by_name') }}" />
                        <button type="submit"
                            class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>

                         <a href="{{ route('admin.view-external-members') }}"  class="absolute end-[80px]  bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
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
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('sl_no') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('name') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('coming_from') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('hotel') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('room_type') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('room_number') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($externalMembers as $key => $member)
                        <tr class="text-[12px] align-[middle]">
                            <td class="px-4 py-2 border border-gray-200">{{ $externalMembers->firstItem() + $key }}</td>
                            <td class="px-4 py-3 border border-gray-200">
                                {{ $member->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                {{ $member->coming_from ?? '' }}
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                {{ $member->hotel?->hotel_name ?? '' }}
                            </td>
                            <td class="px-4 border border-gray-200 py-3">
                                {{ $member->roomType?->roomType?->value ?? '' }}
                            </td>
                            <td class="px-4 border border-gray-200 py-3">
                                {{ $member->room_number ?? '' }}
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="flex items-center gap-5">
                                    @directCanany(['assign_external_members','hotel_assign_external_members'])
                                        <a href="{{ route('external-members.edit', $member->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                    fill="#B68A35"></path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.external-members.destroy', $member->id) }}"
                                            method="POST" class="delete-external-form">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-external text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @enddirectCanany
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class=" text-sm align-[middle]">
                            <td class="px-4 py-3 text-center " colspan="10" dir="ltr">
                                {{ __db('no_data_found') }}
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
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

            <form action="{{ route('admin.view-external-members') }}" method="GET">
                <div class="flex flex-col gap-2 mt-2">
                    <div class="flex flex-col">
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('hotel') }}</label>
                        <select name="hotel_id" id="hotel_id" data-placeholder="{{ __db('select') }}"
                            class="select2 w-full rounded-lg border border-gray-300 text-sm">
                            <option value="">{{ __db('select') }}</option>
                            @foreach ($hotels as $hotel)
                                <option value="{{ $hotel->id }}" @if (request('hotel_id') == $hotel->id) selected @endif>
                                    {{ $hotel->hotel_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('room_type') }}</label>
                        <select name="room_type_id" id="room_type_id" data-placeholder="{{ __db('select') }}"
                            class="select2 w-full rounded-lg border border-gray-300 text-sm">
                            <option value=" ">{{ __db('all') }}</option>
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" @if (request('room_type_id') == $roomType->id) selected @endif>
                                    {{ $roomType->roomType?->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('room_number') }}</label>
                        <input type="text" name="room_number" id="room_number" autocomplete="off"
                            class="block w-full p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg"
                            placeholder="{{ __db('search') }}" value="{{ request('room_number') }}">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('admin.view-external-members') }}"
                        class="px-4 py-2 text-sm font-medium text-center !text-[#B68A35] bg-white border !border-[#B68A35] rounded-lg focus:outline-none hover:bg-gray-100">{{ __db('reset') }}</a>
                    <button type="submit"
                        class="justify-center inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-[#B68A35] rounded-lg hover:bg-[#A87C27]">{{ __db('filter') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.delete-external-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{ __db('are_you_sure') }}',
                    text: "{{ __db('this_will_permanently_delete_the_external_member') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#B68A35',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                placeholder: "{{ __db('select') }}",
                allowClear: true
            });

            $('#hotel_id').on('change', function() {
                let hotelId = $(this).val();
                let dropdown = $('#room_type_id');
                let url = "{{ route('accommodation.rooms', ':id') }}";
                url = url.replace(':id', hotelId);

                $.get(url, function(data) {
                    dropdown.empty().append('<option value="">{{ __db('select') }}</option>');
                    data.forEach(function(room) {
                        dropdown.append('<option value="' + room.id + '">' + room.room_type?.value + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
