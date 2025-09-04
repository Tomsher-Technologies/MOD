@extends('layouts.admin_account', ['title' => __db('all_events')])

@section('content')

<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('all_events') }}</h2>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
    <div class="xl:col-span-12 h-full">
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
            <div class="flex items-center justify-between gap-12 mb-4">
                <form class="w-[75%]" action="{{ route('events.index') }}" method="GET">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-3 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <div class="flex">
                            <input type="text" name="search"
                                class="block w-[35%] p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg"
                                placeholder="{{ __db('search') }}" value="{{ request('search') }}">
                            
                            <select name="status" class="block w-[20%] mr-2 p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg">
                                <option value="">{{ __db('select_status') }}</option>
                                <option value="1" {{ request()->status == 1 ? 'selected' : '' }}>{{ __db('completed') }}
                                </option>
                                <option value="2" {{ request()->status == 2 ? 'selected' : '' }}>{{ __db('not_completed') }}
                                </option>
                            </select>
                        </div>

                        <div class="flex">
                            <a href="{{ route('events.index') }}"  class="absolute end-[80px]  bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                {{ __db('reset') }}</a>
                       
                            <button type="submit" class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __db('search') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <table class="w-full text-left border border-gray-300">
                <thead class="bg-gray-100">
                    <tr class="text-[13px]">
                        <th class="p-3 !bg-[#B68A35] text-center text-white">#</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('event_code') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('name') }} (EN)</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('name') }} (AR)</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('logo') }}</th>
                        {{-- <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('login_image') }}</th> --}}
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('start_date') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('end_date') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('status') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $key => $event)
                        <tr class="odd:bg-[#F9F7ED] align-[middle] text-[12px]">
                            <td class="px-4 py-3 text-end" dir="ltr">
                                {{ ($key+1) + ($events->currentPage() - 1)*$events->perPage() }}
                            </td>
                            <td class=" text-center px-4 py-3 text-black">{{ $event->code }}
                                @if ($event->is_default)
                                    <span class="inline-block rounded bg-green-500 px-2 py-1 text-xs font-semibold text-white">Default</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-black">{{ $event->name_en }} </td>
                            <td class="px-4 py-3 text-black  text-center">{{ $event->name_ar }}</td>
                            <td class="px-4 py-3 text-black  text-center">
                                @if ($event->logo)
                                    <img src="{{ getUploadedImage($event->logo) }}" alt="Logo" width="100" height="100" />
                                @endif
                            </td>
                            {{-- <td class="px-4 py-3 text-black  text-center">
                                @if ($event->image)
                                    <img src="{{ getUploadedImage($event->image) }}" alt="Image" width="100" height="100" />
                                @endif
                            </td> --}}
                            <td class="px-4 py-3 text-black  text-center">{{ ($event->start_date != NULL) ? date('d-m-Y', strtotime($event->start_date)) : '' }}</td>
                            <td class="px-4 py-3 text-black  text-center">{{ ($event->end_date != NULL) ? date('d-m-Y', strtotime($event->end_date)) : '' }}</td>

                            <td class="px-4 py-3">
                                <div class=" text-center gap-5">
                                    @if ($event->status)
                                        <span class="inline-block rounded px-2 py-1 text-xs font-semibold text-white bg-green-500">
                                            {{ __db('completed') }}
                                        </span>
                                    @else
                                        <span class="inline-block rounded px-2 py-1 text-xs font-semibold text-black bg-yellow-400">
                                            {{ __db('not_completed') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3 text-center " dir="ltr">
                                @can('edit_event')
                                    <a href="{{ route('events.edit',  ['id' => base64_encode($event->id)]) }}" title="{{ __db('edit') }}" class="w-8 h-8 bg-[#FBF3D6] text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                @endcan

                                @can('view_event')
                                    <a href="{{ route('events.show',  ['id' => base64_encode($event->id)]) }}" class="w-8 h-8 bg-[#FBF3D6] text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 12" fill="none">
                                            <path d="M6.73242 5.98193C6.73242 6.37976 6.89046 6.76129 7.17176 7.04259C7.45307 7.3239 7.8346 7.48193 8.23242 7.48193C8.63025 7.48193 9.01178 7.3239 9.29308 7.04259C9.57439 6.76129 9.73242 6.37976 9.73242 5.98193C9.73242 5.58411 9.57439 5.20258 9.29308 4.92127C9.01178 4.63997 8.63025 4.48193 8.23242 4.48193C7.8346 4.48193 7.45307 4.63997 7.17176 4.92127C6.89046 5.20258 6.73242 5.58411 6.73242 5.98193Z" stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M14.9824 5.98193C13.1824 8.98193 10.9324 10.4819 8.23242 10.4819C5.53242 10.4819 3.28242 8.98193 1.48242 5.98193C3.28242 2.98193 5.53242 1.48193 8.23242 1.48193C10.9324 1.48193 13.1824 2.98193 14.9824 5.98193Z" stroke="#7C5E24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr class="odd:bg-[#F9F7ED] text-sm align-[middle]">
                            <td class="px-4 py-3 text-center " colspan="10" dir="ltr">
                                {{ __db('no_data_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $events->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    
</div>


@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        

    });

    function update_status(el) {
        if (el.checked) {
            var status = 0;
        } else {
            var status = 1;
        }
        $.post('{{ route('staff.status') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        }, function(data) {
            if (data == 1) {
                toastr.success("{{ __db('status_updated') }}");
                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            } else {
                toastr.error("{{ __db('something_went_wrong') }}");
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        });
    }

</script>
@endsection