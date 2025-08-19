<div>

    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px] ">Escortes</h2>
    </div>
    <!-- Escorts -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class="flex items-center justify-between gap-12 mb-4">
                    <form class="w-[100%]" action="{{ route('escorts.index') }}" method="GET">
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
                                    class="block w-[20%] p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg"
                                    placeholder="{{ __db('search') }}" value="{{ request('search') }}">

                                <a href="{{ route('escorts.index') }}"
                                    class="absolute end-[80px]  bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                    {{ __db('reset') }}</a>

                                <button type="submit"
                                    class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __db('search') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Sl.No</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Military Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Title</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Name</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Mobile Number</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Gender</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Known Languages</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Assigned Delegation</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Status</th> <!-- New column -->
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($escorts as $key => $escort)
                            <tr class="text-sm align-[middle]">
                                <td class="px-4 py-2 border border-gray-200">
                                    {{ $key + 1 + ($escorts->currentPage() - 1) * $escorts->perPage() }}</td>
                                <td class="px-4 py-3 border border-gray-200">{{ $escort->military_number }}</td>
                                <td class="px-4 py-3 border border-gray-200">{{ $escort->title }}</td>
                                <td class="px-4 py-3 border border-gray-200">{{ $escort->name_en }}</td>
                                <td class="px-4 py-3 text-end border border-gray-200" dir="ltr">
                                    {{ $escort->phone_number }}</td>
                                <td class="px-4 py-3 border border-gray-200">{{ $escort->gender->name_en ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 border border-gray-200">{{ $escort->known_languages }}</td>
                                <td class="px-4 py-3 !text-[#B68A35] border border-gray-200">
                                    @if ($escort->delegation)
                                        <a
                                            href="{{ route('delegations.show', $escort->delegation->id) }}">{{ $escort->delegation->code }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-3 border border-gray-200">{{ $escort->status ?? 'N/A' }}</td>
                                <!-- New column -->
                                <td class="px-4 py-3 text-start " dir="ltr">
                                    {{-- @can('edit_escorts') --}}
                                    <a href="{{ route('escorts.edit', $escort->id) }}" title="{{ __db('edit') }}"
                                        class="w-8 h-8 bg-[#FBF3D6] text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 512 512">
                                            <path
                                                d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"
                                                fill="#B68A35"></path>
                                        </svg>
                                    </a>
                                    {{-- @endcan --}}
                                    {{-- @can('delete_escorts') --}}
                                    <form action="{{ route('escorts.destroy', $escort->id) }}" method="POST"
                                        class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="{{ __db('delete') }}"
                                            class="w-8 h-8 bg-[#FBF3D6] text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 448 512">
                                                <path
                                                    d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"
                                                    fill="#B68A35"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    {{-- @endcan --}}
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td class="px-4 py-3 text-center " colspan="10" dir="ltr"> {{-- Changed colspan from 9 to 10 --}}
                                    {{ __db('no__data_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $escorts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;

                    Swal.fire({
                        title: '{{ __db('are_you_sure') }}',
                        text: '{{ __db('you_wont_be_able_to_revert_this') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B68A35',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __db('yes_delete_it') }}'
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
