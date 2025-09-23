@extends('layouts.admin_account', ['title' => __db('alerts')])

@section('content')
    <div  >
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('alerts') }}</h2>

            @directCanany(['add_alerts'])
                <a href="{{ route('alerts.create') }}"
                    class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                    <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-7 7V5" />
                    </svg>
                    <span>{{ __db('create_alert') }}</span>
                </a>
            @enddirectCanany

        </div>

        <div class="bg-white h-full w-full rounded-lg border-0 p-6">
            @if ($alerts->count() > 0)
                <div class="space-y-4">
                    @foreach ($alerts as $alert)
                        <div class="border border-neutral-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-medium text-neutral-900">{{ $alert->title }}</h3>
                                    </div>
                                    <p class="text-neutral-700 mb-2">{{ Str::limit($alert->message, 100) }}</p>
                                    <div class="flex items-center text-sm text-neutral-500 gap-4">
                                        <span>{{ __db('by') }}: {{ $alert->creator->name }}</span>
                                        <span>{{ $alert->created_at->diffForHumans() }}</span>
                                        <span>
                                            @if ($alert->send_to_all)
                                                {{ __db('sent_to_all_users') }}
                                            @else
                                                {{ __db('sent_to') }} {{ $alert->alertRecipients()->count() }}
                                                {{ __db('users') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('alerts.show', $alert) }}"
                                        class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                                        
                                        <span>{{ __db('view_details') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $alerts->links() }}
                </div>
            @else
                @directCanany(['add_alerts'])
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">{{ __db('no_alerts') }}</h3>
                        <p class="mt-1 text-sm text-neutral-500">{{ __db('create_your_first_alert') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('alerts.create') }}"
                                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3 mx-auto w-fit">
                                <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14m-7 7V5" />
                                </svg>
                                <span>{{ __db('create_alert') }}</span>
                            </a>
                        </div>
                    </div>
                @enddirectCanany
                <div class="text-center py-12">
                    <h3 class="mt-2 text-sm font-medium text-neutral-900">{{ __db('no_alerts') }}</h3>
                </div>
            @endif
        </div>
    </div>
@endsection
