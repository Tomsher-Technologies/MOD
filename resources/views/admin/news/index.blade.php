@extends('layouts.admin_account', ['title' => __db('all') . ' ' . __db('news')])

@section('content')
    <div class="">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{__db('all') . ' ' . __db('news') }}</h2>
            
        </div>
        <!-- DAdd Delegation -->
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">

            <div class=" mb-4 flex items-center justify-between gap-3">
                <form class="w-[50%] me-4" action="{{ route('news.index') }}" method="GET">
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
                            placeholder="{{ __db('search_by_title') }}" />

                        <div class="flex">
                            <a href="{{ route('news.index') }}"  class="absolute end-[80px]  bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                    {{ __db('reset') }}</a>

                            <button type="submit"
                                class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-4 py-2">{{ __db('search') }}</button>
                        </div>
                    </div>
                </form>

                <div class="text-center">
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
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('title') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('image') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                            {{ __db('event') }}
                        </th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                            {{ __db('date') }}
                        </th>

                        <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">{{ __db('status') }}</th>
                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                            {{ __db('actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($news as $key => $new)
                        <tr class="text-[12px] align-[middle]">
                            <td class="px-4 py-2 border text-center border-gray-200">{{ $news->firstItem() + $key }}</td>
                            <td class="px-4 py-3 border border-gray-200">
                                {{ $new->getTranslation('title') ?? '' }}
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <img src="{{ asset(getUploadedImage($new->image)) }}" class="h-20 w-20">
                            </td>
                            <td class="px-4 border text-center border-gray-200 py-3">
                                {{ $new->event?->name_en ?? '' }}
                            </td>
                            <td class="px-4 border text-center border-gray-200 py-3">
                                {{ $new->news_date ? \Carbon\Carbon::parse($new->news_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 border border-gray-200 text-center">
                                @can('edit_news')
                                    <div class=" items-center">
                                        <label for="switch-{{ $key }}" class="relative inline-block w-11 h-6">
                                            <input type="checkbox" id="switch-{{ $key }}" onchange="update_status(this)" value="{{ $new->id }}"
                                                class="sr-only peer" {{ $new->status == 1 ? 'checked' : '' }} />

                                            <div class="block bg-gray-300 peer-checked:bg-[#009448] w-11 h-6 rounded-full transition"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                                        </label>
                                    </div>
                                @endcan
                            </td>
                            
                            <td class="px-4 py-3 border text-center border-gray-200">
                                <div class="flex items-center gap-5">
                                    @canany(['edit_news'])
                                        <a href="{{ route('news.edit', $new->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                    fill="#B68A35"></path>
                                            </svg>
                                        </a>
                                    @endcanany

                                    @canany(['delete_news'])
                                        <form action="{{ route('news.destroy', $new->id) }}"
                                            method="POST" class="delete-news-form">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-news text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcanany
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="px-4 py-2 text-center text-neutral-500">
                            <td colspan="10">
                                {{ __db('no_data_found') }}
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            <div class="mt-4">
                {{ $news->appends(request()->input())->links() }}
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

            <form action="{{ route('news.index') }}" method="GET">
                <div class="flex flex-col gap-2 mt-2">
                    <div class="flex flex-col">
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('event') }}</label>
                        <select name="event_id" id="event_id" class="select2 w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0" data-placeholder="{{ __db('select') . ' ' . __db('event') }}">
                            <option value="">{{ __db('select') . ' ' . __db('event') }}</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="form-label block mb-1 text-gray-700 font-medium">{{ __db('news_date') }}</label>
                        <input type="text" class="form-control date-range" id="date_range" name="date_range"
                    placeholder="{{ 'date' }}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss"
                    data-separator=" to " autocomplete="off"  value="{{ request('date_range') ?? '' }}">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('news.index') }}"
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
    function update_status(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('news.status') }}', {
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

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".delete-news").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();

                let form = this.closest("form");

                Swal.fire({
                    title: "{{ __db('are_you_sure') }}",
                    text: "{{ __db('you_will_not_be_able_to_revert_this') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "{{ __db('yes') }}",
                    cancelButtonText: "{{ __db('cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection