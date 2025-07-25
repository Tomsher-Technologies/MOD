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
                                <option value="1" {{ request()->status == 1 ? 'selected' : '' }}>{{ __db('active') }}
                                </option>
                                <option value="2" {{ request()->status == 2 ? 'selected' : '' }}>{{ __db('inactive') }}
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
                    <tr>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">#</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('name') }} (EN)</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('name') }} (AR)</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('logo') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('login_image') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('start_date') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('end_date') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('status') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-center text-white">{{ __db('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $key => $event)
                        <tr class="odd:bg-[#F9F7ED] align-[middle]">
                            <td class="px-4 py-3 text-end" dir="ltr">
                                {{ ($key+1) + ($events->currentPage() - 1)*$events->perPage() }}
                            </td>
                            <td class="px-4 py-3 text-black">{{ $event->name_en }}
                                @if ($event->is_default)
                                    <span class="inline-block rounded bg-green-500 px-2 py-1 text-xs font-semibold text-white">Default</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-black  text-center">{{ $event->name_ar }}</td>
                            <td class="px-4 py-3 text-black  text-center">
                                @if ($event->logo)
                                    <img src="{{ getUploadedImage($event->logo) }}" alt="Logo" width="100" height="100" />
                                @endif
                            </td>
                            <td class="px-4 py-3 text-black  text-center">
                                @if ($event->image)
                                    <img src="{{ getUploadedImage($event->image) }}" alt="Image" width="100" height="100" />
                                @endif
                            </td>
                            <td class="px-4 py-3 text-black  text-center">{{ ($event->start_date != NULL) ? date('d-m-Y', strtotime($event->start_date)) : '' }}</td>
                            <td class="px-4 py-3 text-black  text-center">{{ ($event->end_date != NULL) ? date('d-m-Y', strtotime($event->end_date)) : '' }}</td>

                            <td class="px-4 py-3">
                                <div class=" text-center gap-5">
                                    @if ($event->status)
                                        <span class="badge badge-success">{{ __db('completed') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __db('not_completed') }}</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-info">Edit</a>

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