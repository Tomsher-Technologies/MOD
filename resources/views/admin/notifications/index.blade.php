@extends('layouts.admin_account', ['title' => getActiveLanguage() === 'ar' ? 'الإشعارات' : __db('notifications')])

@section('content')
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">
                {{ getActiveLanguage() === 'ar' ? 'الإشعارات' : __db('notifications') }}</h2>
        </div>

        <div class="bg-white h-full w-full rounded-lg border-0">
            @if ($notifications->count() > 0)
                <div class="space-y-4 flex flex-col">
                    @foreach ($notifications as $notification)
                        @php
                            $data = is_string($notification->data)
                                ? json_decode($notification->data, true)
                                : $notification->data;

                            $message = '';

                            if (isset($data['message'])) {
                                if (is_array($data['message'])) {
                                    $lang = getActiveLanguage();
                                    if ($lang !== 'en' && isset($data['message']['ar'])) {
                                        $message = $data['message']['ar'];
                                    } else {
                                        $message = $data['message']['en'] ?? '';
                                    }
                                } else {
                                    $message = $data['message'];
                                }
                            }

                            $module = $data['module'] ?? null;
                            $action = $data['action'] ?? null;
                            $delegationId = $data['delegation_id'] ?? null;
                            $submoduleId = $data['submodule_id'] ?? null;

                            $moduleName = null;
                            $moduleCode = null;
                            $moduleDetails = [];

                            $changes = $data['changes'] ?? [];
                            if (is_string($changes)) {
                                $changes = json_decode($changes, true) ?: [];
                            }

                        @endphp
                        <a href="{{ route('notifications.redirect', $notification->id) }}">
                            <div class=" border-b border-neutral-200 p-3 hover:bg-gray-50 }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-medium text-neutral-900">
                                                {{ $module ?? (getActiveLanguage() === 'ar' ? 'إشعار' : 'Notification') }}
                                            </h3>
                                        </div>
                                        <p class="text-neutral-700 mb-2">{{ $message }}</p>

                                        @if (!empty($changes) && is_array($changes))
                                            <div class="text-sm text-neutral-600">
                                                <ul class="list-disc list-inside">
                                                    @foreach ($changes as $field => $change)
                                                        @if (is_array($change))
                                                            @if (isset($change['removed']) && isset($change['added']))
                                                                @php
                                                                    $label = $change['label'] ?? $field;
                                                                    $lang = getActiveLanguage();
                                                                    if ($lang !== 'en') {
                                                                        $label = $lang === 'ar' ? $label : $label;
                                                                    }
                                                                @endphp
                                                                @if (!empty($change['added']))
                                                                    <li>{{ $label }}:
                                                                        @if (getActiveLanguage() === 'ar')
                                                                            تمت الإضافة {{ $change['added'] }}
                                                                        @else
                                                                            Added {{ $change['added'] }}
                                                                        @endif
                                                                    </li>
                                                                @endif
                                                                @if (!empty($change['removed']))
                                                                    <li>{{ $label }}:
                                                                        @if (getActiveLanguage() === 'ar')
                                                                            تمت الإزالة {{ $change['removed'] }}
                                                                        @else
                                                                            Removed {{ $change['removed'] }}
                                                                        @endif
                                                                    </li>
                                                                @endif
                                                            @elseif (isset($change['old']) && isset($change['new']))
                                                                @php
                                                                    $label = $change['label'] ?? $field;
                                                                @endphp
                                                                <li>{{ $label }}:
                                                                    @if (getActiveLanguage() === 'ar')
                                                                        تم التغيير من '{{ $change['old'] }}' إلى
                                                                        '{{ $change['new'] }}'
                                                                    @else
                                                                        Changed from '{{ $change['old'] }}' to
                                                                        '{{ $change['new'] }}'
                                                                    @endif
                                                                </li>
                                                            @else
                                                                @php
                                                                    $label = $change['label'] ?? $field;
                                                                @endphp
                                                                <li>{{ $label }}: {{ json_encode($change) }}</li>
                                                            @endif
                                                        @else
                                                            @if (getActiveLanguage() === 'ar')
                                                                <li>{{ $field }}: {{ $change }}</li>
                                                            @else
                                                                <li>{{ $field }}: {{ $change }}</li>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="bg-gray-50 rounded-lg p-6 border-l-4 border-blue-500">
                                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __db('module_details') }}
                                            </h3>

                                            <dl class="grid grid-cols-1 gap-x-3 gap-y-3 sm:grid-cols-2">
                                                <div class="sm:col-span-1">
                                                    <dt class="text-sm font-medium text-gray-500">{{ __db('module') }}</dt>
                                                    <dd class="mt-1 text-sm text-gray-900">{{ $module ?? 'N/A' }}</dd>
                                                </div>

                                                <div class="sm:col-span-1">
                                                    <dt class="text-sm font-medium text-gray-500">{{ __db('action') }}</dt>
                                                    <dd class="mt-1 text-sm text-gray-900">{{ $action ?? 'N/A' }}</dd>
                                                </div>

                                                <div class="sm:col-span-1">
                                                    <dt class="text-sm font-medium text-gray-500">
                                                        {{ __db('delegation_code') }}</dt>
                                                    <dd class="mt-1 text-sm text-gray-900">
                                                        {{ getModuleCode('delegations', $delegationId) ?? 'N/A' }}
                                                    </dd>
                                                </div>

                                                <div class="sm:col-span-1">
                                                    <dt class="text-sm font-medium text-gray-500">{{ __db('module_code') }}
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900">
                                                        {{ getModuleCode(strtolower($module), $submoduleId) ?? 'N/A' }}
                                                    </dd>
                                                </div>

                                            </dl>
                                        </div>


                                        <p class="text-xs text-neutral-500">
                                            @if (getActiveLanguage() === 'ar')
                                                {{ $notification->created_at->diffForHumans() }}
                                            @else
                                                {{ $notification->created_at->diffForHumans() }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if (getActiveLanguage() === 'ar')
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">
                            {{ __db('no') . ' ' . __db('notifications') }}</h3>
                        <p class="mt-1 text-sm text-neutral-500">{{ __db('all_notifications_read') }}</p>
                    @else
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">
                            {{ __db('no') . ' ' . __db('notifications') }}</h3>
                        <p class="mt-1 text-sm text-neutral-500">{{ __db('all_notifications_read') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
