@extends('layouts.admin_account', ['title' => __db('all_delegations')])

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('all_delegations') }}</h2>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full">
        <div class="xl:col-span-12 h-full">
            <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
                <div class="flex items-center justify-between gap-12 mb-4">
                    <form class="w-[75%]" action="{{ route('delegations.index') }}" method="GET">
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

                                @php
                                    $statusDropdown = getDropDown('participation_status');
                                @endphp

                                <select name="participation_status"
                                    class="block w-[20%] mr-2 p-2.5 !ps-10 text-secondary-light text-sm !border-[#d1d5db] rounded-lg">
                                    <option value="">{{ __db('select_status') }}</option>
                                    @if ($statusDropdown)
                                        @foreach ($statusDropdown->options as $option)
                                            <option value="{{ $option->value }}"
                                                {{ request('participation_status') == $option->value ? 'selected' : '' }}>
                                                {{ $option->value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>

                            <div class="flex">
                                <a href="{{ route('delegations.index') }}"
                                    class="absolute end-[80px] bottom-[3px] border !border-[#B68A35] !text-[#B68A35] font-medium rounded-lg text-sm px-4 py-2 ">
                                    {{ __db('reset') }}</a>

                                <button type="submit"
                                    class="!text-[#5D471D] absolute end-[3px] bottom-[3px] !bg-[#E6D7A2] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    {{ __db('search') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="table-auto mb-0 !border-[#F9F7ED] w-full">
                    <thead>
                        <tr>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Delegation ID</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Invitation From</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Team Head</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Escorts</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Drivers</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Invitation Status</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Participation Status</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Note</th>
                            <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($delegations as $delegation)
                            <tr class="odd:bg-[#F9F7ED]">
                                <td class="p-3 text-start">{{ $delegation->delegate_id }}</td>
                                <td class="p-3 text-start">{{ $delegation->invitationFrom->value ?? '-' }}</td>

                                <td class="p-3 text-start">
                                    @php
                                        $teamHeads = $delegation->delegates->filter(fn($d) => $d->team_head);
                                    @endphp
                                    @if ($teamHeads->isNotEmpty())
                                        @foreach ($teamHeads as $head)
                                            <div>{{ $head->name_en }}</div>
                                        @endforeach
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>

                                <td class="p-3 text-start">
                                    @php
                                        $escorts = $delegation->delegates->pluck('escorts')->filter()->unique();
                                    @endphp
                                    @if ($escorts->isNotEmpty())
                                        @foreach ($escorts as $escort)
                                            <div>{{ $escort }}</div>
                                        @endforeach
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>

                                <td class="p-3 text-start">
                                    @php
                                        $drivers = $delegation->delegates->pluck('drivers')->filter()->unique();
                                    @endphp
                                    @if ($drivers->isNotEmpty())
                                        @foreach ($drivers as $driver)
                                            <div>{{ $driver }}</div>
                                        @endforeach
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>

                                <td class="p-3 text-start">{{ $delegation->invitationStatus->value ?? '-' }}</td>
                                <td class="p-3 text-start">{{ $delegation->participationStatus->value ?? '-' }}</td>
                                <td class="p-3 text-start">
                                    {{ $delegation->note1 ?? '-' }}
                                    @if ($delegation->note2)
                                        <br>{{ $delegation->note2 }}
                                    @endif
                                </td>
                                <td class="p-3 text-start">
                                    @can('view_delegations')
                                        <a href="{{ route('delegations.show', $delegation->id) }}"
                                            class="text-blue-600 hover:underline">{{ __db('view') }}</a>
                                    @endcan
                                    @can('edit_delegations')
                                        | <a href="{{ route('delegations.edit', $delegation->id) }}"
                                            class="text-green-600 hover:underline">{{ __db('edit') }}</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        @if ($delegations->isEmpty())
                            <tr>
                                <td colspan="9" class="p-3 text-center text-gray-500">{{ __db('no_data_found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $delegations->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
