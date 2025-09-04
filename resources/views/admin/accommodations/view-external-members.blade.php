@extends('layouts.admin_account', ['title' => __db('view') . ' ' . __db('external_accommodations')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{__db('external_accommodations') }}</h2>
        <a href="{{ route('accommodations.index')  }}"
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
                <tr class="text-[13px]">
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ __db('sl_no') }}
                    </th>
                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                        {{ __db('name') }}
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
                @forelse ( $externalMembers as $key => $member )
                <tr class="text-[12px] align-[middle]">
                    <td class="px-4 py-2 border border-gray-200">{{ $externalMembers->firstItem() + $key }}</td>
                    <td class="px-4 py-3 border border-gray-200">
                        {{ $member->name ?? '' }}
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
                            @canany(['assign_external_members'])
                                <a href="{{ route('external-members.edit', $member->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                        <path
                                            d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                            fill="#B68A35"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.external-members.destroy', $member->id) }}" method="POST" class="delete-external-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-external text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </button>
                                </form>
                            @endcanany
                        </div>
                    </td>
                </tr>
                @empty

                @endforelse

            </tbody>
        </table>
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
</script>
@endsection