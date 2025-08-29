@extends('layouts.admin_account', ['title' => __db('alert_details')])

@section('content')
    <div class="dashboard-main-body">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('alert_details') }}</h2>
            <a href="{{ route('alerts.index') }}"
                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
                <span>{{ __db('back_to_alerts') }}</span>
            </a>
        </div>

        <div class="bg-white h-full w-full rounded-lg border-0 p-6 mb-6">
            <div class="border-b border-neutral-200 pb-4 mb-4">
                <h3 class="text-xl font-semibold mb-2">{{ $alert->title }}</h3>
                <p class="text-neutral-700 mb-4">{{ $alert->message }}</p>

                @if ($alert->attachment)
                    <div class="mb-4">
                        <a href="{{ asset('storage/' . $alert->attachment) }}" target="_blank"
                            class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12-4-4m4 4 4-4" />
                            </svg>
                            {{ __db('download_attachment') }}
                        </a>
                    </div>
                @endif

                @canany(['add_alerts'])
                    <div class="flex items-center text-sm text-neutral-500 gap-4">
                        <span>{{ __db('created_by') }}: {{ $alert->creator->name }}</span>
                        <span>{{ $alert->created_at->format('M d, Y H:i') }}</span>
                        <span>
                            @if ($alert->send_to_all)
                                {{ __db('sent_to_all_users') }}
                            @else
                                {{ __db('sent_to') }} {{ $alert->alertRecipients()->count() }} {{ __db('users') }}
                            @endif
                        </span>
                    </div>
                @endcanany

            </div>

        </div>

        @canany(['add_alerts'])
            <div class="bg-white h-full w-full rounded-lg border-0 p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __db('recipients') }}</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __db('user') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __db('email') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __db('status') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __db('read_at') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($alert->alertRecipients as $recipient)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $recipient->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $recipient->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($recipient->read_at)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __db('read') }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ __db('unread') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($recipient->read_at)
                                            {{ $recipient->read_at->format('M d, Y H:i') }}
                                        @else
                                            {{ __db('not_read_yet') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endcanany


    </div>
@endsection
