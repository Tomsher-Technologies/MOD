@extends('layouts.admin_account', ['title' => getActiveLanguage() === 'ar' ? 'الإشعارات' : __db('notifications')])

@section('content')
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ getActiveLanguage() === 'ar' ? 'الإشعارات' : __db('notifications') }}</h2>
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
                            $title = '';
                            if (isset($data['title'])) {
                                if (is_array($data['title'])) {
                                    $lang = getActiveLanguage();
                                    if ($lang !== 'en' && isset($data['title']['ar'])) {
                                        $title = $data['title']['ar'];
                                    } else {
                                        $title = $data['title']['en'] ?? '';
                                    }
                                } else {
                                    $title = $data['title'];
                                }
                            } else {
                                if (isset($data['changes']['title'])) {
                                    if (is_array($data['changes']['title'])) {
                                        $lang = getActiveLanguage();
                                        if ($lang !== 'en' && isset($data['changes']['title']['ar'])) {
                                            $title = $data['changes']['title']['ar'];
                                        } else {
                                            $title = $data['changes']['title']['en'] ?? '';
                                        }
                                    } else {
                                        $title = $data['changes']['title'];
                                    }
                                }
                            }
                            $module = $data['module'] ?? null;
                            $action = $data['action'] ?? null;
                            $delegationId = $data['delegation_id'] ?? null;
                            $url = '#';

                            if ($module && $delegationId) {
                                switch (strtolower($module)) {
                                    case 'escorts':
                                        $url = route('escorts.index');
                                        break;
                                    case 'drivers':
                                        $url = route('drivers.index');
                                        break;
                                    case 'delegations':
                                        $url = route('delegations.show', $delegationId);
                                        break;
                                    default:
                                        $url = '#';
                                }
                            } elseif ($delegationId) {
                                $url = route('delegations.show', $delegationId);
                            }

                            $changes = $data['changes'] ?? [];
                            if (is_string($changes)) {
                                $changes = json_decode($changes, true) ?: [];
                            }
                        @endphp
                        <a href="{{ $url }}">
                            <div class=" border-b border-neutral-200 p-3 hover:bg-gray-50 }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-medium text-neutral-900">{{ $title ?: ($module ?? (getActiveLanguage() === 'ar' ? 'إشعار' : 'Notification')) }}</h3>
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
                                                                    @if(getActiveLanguage() === 'ar')
                                                                        تمت الإضافة {{ $change['added'] }}
                                                                    @else
                                                                        Added {{ $change['added'] }}
                                                                    @endif
                                                                </li>
                                                            @endif
                                                            @if (!empty($change['removed']))
                                                                <li>{{ $label }}: 
                                                                    @if(getActiveLanguage() === 'ar')
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
                                                                @if(getActiveLanguage() === 'ar')
                                                                    تم التغيير من '{{ $change['old'] }}' إلى '{{ $change['new'] }}'
                                                                @else
                                                                    Changed from '{{ $change['old'] }}' to '{{ $change['new'] }}'
                                                                @endif
                                                            </li>
                                                        @else
                                                            @php
                                                                $label = $change['label'] ?? $field;
                                                            @endphp
                                                            <li>{{ $label }}: {{ json_encode($change) }}</li>
                                                        @endif
                                                    @else
                                                        @if(getActiveLanguage() === 'ar')
                                                            <li>{{ $field }}: {{ $change }}</li>
                                                        @else
                                                            <li>{{ $field }}: {{ $change }}</li>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                        <p class="text-xs text-neutral-500">
                                            @if(getActiveLanguage() === 'ar')
                                                {{ $notification->created_at->diffForHumans() }}
                                            @else
                                                {{ $notification->created_at->diffForHumans() }}
                                            @endif
                                        </p>
                                    </div>
                                    {{-- <div class="flex gap-2">
                                    @if ($url !== '#')
                                        <a href="{{ $url }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                            {{ __db('view') }}
                                        </a>
                                    @endif
                                </div> --}}
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
                    @if(getActiveLanguage() === 'ar')
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">{{ __db('no') . ' ' . __db('notifications') }}</h3>
                        <p class="mt-1 text-sm text-neutral-500">{{ __db('all_notifications_read') }}</p>
                    @else
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">{{ __db('no') . ' ' . __db('notifications') }}</h3>
                        <p class="mt-1 text-sm text-neutral-500">{{ __db('all_notifications_read') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
