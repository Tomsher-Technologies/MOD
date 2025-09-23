@extends('layouts.admin_account', ['title' => __db('create_alert')])

@section('content')
    <div  >
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('create_alert') }}</h2>
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

        @if ($errors->any())
            <div class="mb-6 p-4 border border-red-400 bg-red-100 text-red-700 rounded">
                <h4 class="font-semibold mb-2">{{ __db('please_fix_the_following_errors') }}</h4>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white h-full w-full rounded-lg border-0 p-6">
            <form action="{{ route('alerts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-12 gap-5">
                    <div class="col-span-12">
                        <label class="form-label">{{ __db('title') }} ({{ __db('english') }}):</label>
                        <input type="text" name="title"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('title') }}" required>
                        @error('title')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('title') }} ({{ __db('arabic') }}):</label>
                        <input type="text" name="title_ar"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('title_ar') }}" required>
                        @error('title_ar')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('message') }} ({{ __db('english') }}):</label>
                        <textarea name="message" rows="5"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('message') }} ({{ __db('arabic') }}):</label>
                        <textarea name="message_ar" rows="5"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            required>{{ old('message_ar') }}</textarea>
                        @error('message_ar')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('attachment') }} ({{ __db('optional') }}):</label>
                        <input type="file" name="attachment"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        @error('attachment')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('recipients') }}:</label>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('recipient_type') }}:</label>
                                <select name="recipient_type" id="recipientTypeSelect" 
                                    class="select2 w-full p-3 rounded-lg border border-gray-300 text-sm">
                                    <option value="">{{ __db('select_recipient_type') }}</option>
                                    <option value="all" {{ old('recipient_type') == 'all' ? 'selected' : '' }}>
                                        {{ __db('all_users') }}</option>
                                    <option value="module" {{ old('recipient_type') == 'module' ? 'selected' : '' }}>
                                        {{ __db('select_module') }}</option>
                                    <option value="users" {{ old('recipient_type') == 'users' ? 'selected' : '' }}>
                                        {{ __db('select_users') }}</option>
                                </select>
                            </div>

                            <div id="moduleSelectContainer"
                                style="display: {{ old('recipient_type') == 'module' ? 'block' : 'none' }};">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('select_module') }}:</label>
                                <select name="module" id="moduleSelect"
                                    class="select2 w-full p-3 rounded-lg border border-gray-300 text-sm">
                                    <option value="">{{ __db('select_module') }}</option>
                                    @foreach($modules as $key => $label)
                                        <option value="{{ $key }}" {{ old('module') == $key ? 'selected' : '' }}>{{ __db(strtolower(str_replace(' ', '_', $label))) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="usersSelectContainer"
                                style="display: {{ old('recipient_type') == 'users' ? 'block' : 'none' }};">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __db('select_users') }}:</label>
                                <select name="users[]" id="usersSelect" multiple
                                    class="select2 w-full p-3 rounded-lg border border-gray-300 text-sm"
                                    data-placeholder="{{ __db('select_users') }}">q
                                    @foreach ($assignedUsers as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('users')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-between items-center mt-8">
                    <button type="submit" class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">
                        {{ __db('send_alert') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all Select2 elements
            $('#recipientTypeSelect').select2({
                placeholder: "{{ __db('select_recipient_type') }}",
                allowClear: true
            });
            
            $('#moduleSelect').select2({
                placeholder: "{{ __db('select_module') }}",
                allowClear: true
            });
            
            $('#usersSelect').select2({
                placeholder: "{{ __db('select_users') }}",
                allowClear: true
            });

            $('#recipientTypeSelect').on('change', function() {
                var selectedType = $(this).val();

                if (selectedType === 'module') {
                    $('#moduleSelectContainer').show();
                    $('#usersSelectContainer').hide();
                } else if (selectedType === 'users') {
                    $('#moduleSelectContainer').hide();
                    $('#usersSelectContainer').show();
                } else {
                    $('#moduleSelectContainer').hide();
                    $('#usersSelectContainer').hide();
                }
            });
        });
    </script>
@endsection
