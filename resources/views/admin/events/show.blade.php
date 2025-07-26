@extends('layouts.admin_account', ['title' => __db('event_details')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('event_details') }}</h2>
        <a href="{{ Session::has('events_last_url') ? Session::get('events_last_url') : route('events.index') }}" id="add-attachment-btn"
            class="float-left btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        {{-- Event Header --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-2xl font-bold text-primary-700 mb-6">
                {{ __db('event_information') }}
                @if ($event->is_default)
                <span
                    class="inline-block rounded bg-green-500 px-2 py-1 text-xs font-semibold text-white">Default</span>
                @endif
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <div class="bg-white rounded-lg  p-6 space-y-4 max-w-md">

                        <div class="space-y-3 text-gray-700">
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('name') }} (EN) :</span>
                                <span>{{ $event->name_en }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('name') }} (AR) :</span>
                                <span>{{ $event->name_ar }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('code') }} :</span>
                                <span>{{ $event->code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('start_date') }} :</span>
                                <span>{{ $event->start_date }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ __db('end_date') }} :</span>
                                <span>{{ $event->end_date }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold">{{ __db('status') }} :</span>
                                @if ($event->status)
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">{{
                                    __db('completed') }}</span>
                                @else
                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">{{
                                    __db('not_completed') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Logo Card -->
                        <div
                            class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col items-center text-center">
                            <div class="text-sm font-semibold text-gray-700 mb-2">
                                {{ __db('logo') }}
                            </div>
                            <div class="w-full h-32 flex items-center justify-center rounded overflow-hidden">
                                <img src="{{ getUploadedImage($event->logo) }}" alt="Logo"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                        </div>

                        <!-- Image Card -->
                        @if ($event->image)
                        <div
                            class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col items-center text-center">
                            <div class="text-sm font-semibold text-gray-700 mb-2">
                                {{ __db('image') }}
                            </div>
                            <div class="w-full h-32 flex items-center justify-center rounded overflow-hidden">
                                <img src="{{ getUploadedImage($event->image) }}" alt="Image"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        @if (!empty($assignedUsers))
            @foreach($assignedUsers as $module => $users)
                <div class="bg-white rounded-lg shadow p-6 mb-10">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-2">
                        {{ __db($module) }} {{ __db('module') }}
                    </h3>
                    @if($users->count())
                        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($users as $row)
                                <div
                                    class="border border-gray-200 rounded-lg p-4 flex flex-col items-center text-center hover:shadow-lg transition-shadow">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-tr from-[#aa853e40] to-[#B68A35] text-black text-xl font-extrabold shadow-md">
                                        {{ strtoupper(substr($row->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 text-center truncate w-full">{{ $row->user->name ??
                                        '-' }}</h4>
                                    <p class="text-sm text-gray-600 mt-1 truncate w-full text-center">{{ $row->user->email ?? '-' }}</p>
                                    <span
                                        class="mt-2 inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $row->role->name ?? '-' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 italic">{{ __db('no_users_assigned') }}</p>
                    @endif
                </div>
            @endforeach
        @endif
        
    </div>


</div>
@endsection

@section('style')
<style>

</style>
@endsection

@section('script')
<script>

</script>
@endsection