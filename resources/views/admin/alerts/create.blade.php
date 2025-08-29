@extends('layouts.admin_account', ['title' => __db('create_alert')])

@section('content')
    <div class="dashboard-main-body">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('create_alert') }}</h2>
            <a href="{{ route('alerts.index') }}" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/>
                </svg>
                <span>{{ __db('back_to_alerts') }}</span>
            </a>
        </div>

        <div class="bg-white h-full w-full rounded-lg border-0 p-6">
            <form action="{{ route('alerts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-12 gap-5">
                    <div class="col-span-12">
                        <label class="form-label">{{ __db('title') }}:</label>
                        <input type="text" name="title" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('message') }}:</label>
                        <textarea name="message" rows="5" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('attachment') }} ({{ __db('optional') }}):</label>
                        <input type="file" name="attachment" class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        @error('attachment')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('recipients') }}:</label>
                        <select name="users[]" id="usersSelect" multiple class="select2 w-full p-3 rounded-lg border border-gray-300 text-sm" required>
                            <option value="all">{{ __db('all_users') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('users')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center mt-8">
                    <button type="submit" class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg py-[1px] h-12">
                        {{ __db('send_alert') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize select2
            $('#usersSelect').select2({
                placeholder: "{{ __db('select_recipients') }}",
                allowClear: true
            });
            
            // Handle "All Users" selection
            $('#usersSelect').on('select2:select', function(e) {
                var selectedValue = e.params.data.id;
                if (selectedValue === 'all') {
                    // If "All Users" is selected, deselect all other options
                    $(this).val(['all']).trigger('change');
                } else {
                    // If any other option is selected, deselect "All Users" if it's selected
                    var selectedValues = $(this).val();
                    if (selectedValues && selectedValues.includes('all')) {
                        var filteredValues = selectedValues.filter(function(value) {
                            return value !== 'all';
                        });
                        $(this).val(filteredValues).trigger('change');
                    }
                }
            });
        });
    </script>
@endsection