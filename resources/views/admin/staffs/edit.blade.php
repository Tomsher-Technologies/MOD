@extends('layouts.admin_account', ['title' => __db('update_staff')])

@section('content')
<div class="">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('update_staff') }}</h2>
        <a href="{{ Session::has('staffs_last_url') ? Session::get('staffs_last_url') : route('staffs.index') }}" id="add-attachment-btn" class="float-left btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <form action="{{ route('staffs.update', $staff->id) }}" method="POST" autocomplete="off">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white p-6 grid grid-cols-1 gap-5 mb-4">
                <div>
                    <h2 class="font-semibold mb-0 !text-[22px] mb-3 mt-5">{{ __db('staff_details') }}
                    </h2>
                    <div class="delegate-row border bg-white p-6 rounded bg-gray-100 mb-2">
                        <div class="grid grid-cols-12 gap-5">

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('username') }} <span class="text-red-600">*</span></label>
                                <input type="text" id="username" name="username" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('username', $staff->username) }}">
                                @error('username')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('name') }} <span class="text-red-600">*</span></label>
                                <input type="text" id="name" name="name" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('name', $staff->name) }}">
                                @error('name')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('email') }} </label>
                                <input type="text" id="email" name="email" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('email', $staff->email) }}">
                                @error('email')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('phone') }}</label>
                                <input type="text" id="mobile" name="mobile" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('mobile', $staff->phone) }}">
                                @error('mobile')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                             <div class="col-span-3">
                                <label class="form-label">{{ __db('module') }} <span class="text-red-600">*</span></label>
                                <select name="module" id="module" class=" p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    <option value="" {{ (old('module') == "") ? 'selected' : '' }}>{{ __db('choose_option') }}</option>
                                    <option value="admin" {{ (($staff->user_type) == "staff" || old('module') == "admin") ? 'selected' : '' }}>{{ __db('admin') }}</option>
                                    <option value="delegate" {{ (old('module', $staff->user_type) == "delegate") ? 'selected' : '' }}>{{ __db('delegate') }}</option>
                                    <option value="escort" {{ (old('module', $staff->user_type) == "escort") ? 'selected' : '' }}>{{ __db('escort') }}</option>
                                    <option value="driver" {{ (old('module', $staff->user_type) == "driver") ? 'selected' : '' }}>{{ __db('driver') }}</option>
                                    <option value="hotel" {{ (old('module', $staff->user_type) == "hotel") ? 'selected' : '' }}>{{ __db('hotel') }}</option>
                                    <option value="top-management" {{ (old('module', $staff->user_type) == "top-management") ? 'selected' : '' }}>{{ __db('top_management') }}</option>
                                </select>
                                @error('module')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('role') }} <span class="text-red-600">*</span></label>
                                <select name="role"  id="role" class=" p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ $staff->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3 relative">
                                <label class="form-label">{{ __db('password') }} </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" autocomplete="new-password" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('password') }}">

                                    <button type="button" onclick="togglePassword('password')" 
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-primary-600">
                                        <svg id="eye_open_password" xmlns="http://www.w3.org/2000/svg" 
                                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 
                                                4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="eye_closed_password" xmlns="http://www.w3.org/2000/svg" 
                                            class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 
                                                0-8.268-2.943-9.542-7a9.956 9.956 0 012.807-4.419M6.18 
                                                6.18A9.956 9.956 0 0112 5c4.477 0 8.268 
                                                2.943 9.542 7a9.956 9.956 0 01-4.038 5.223M15 
                                                12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                        </svg>
                                    </button>
                                    
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('confirm_password') }}</label>
                                <input type="text" id="password_confirmation" name="password_confirmation" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('password_confirmation') }}">
                                @error('password')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="flex justify-start items-center gap-5">
                <button type="submit" class="btn text-md  !bg-[#B68A35] text-white rounded-lg h-12 mr-4">{{
                    __db('submit') }}</button>

                <a href="{{ Session::has('staffs_last_url') ? Session::get('staffs_last_url') : route('staffs.index') }}" class="btn text-md  !bg-[#637a85] border !border-[#637a85] !text-[#fff] rounded-lg h-12 mr-1">{{
                    __db('cancel') }}</a>
            </div>
        </div>

    </form>
</div>
@endsection

@section('style')
<style>

</style>
@endsection

@section('script')
<script>
    document.getElementById('module').addEventListener('change', function () {
        const module = this.value;
        const roleSelect = document.getElementById('role');

        // Clear existing options
        roleSelect.innerHTML = '<option value="">{{ @__db('choose_option') }}</option>';

        if (module) {
            fetch(`/mod-events/get-roles-by-module/${module}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(role => {
                        const option = document.createElement('option');
                        option.value = role.name;
                        option.text = role.name;
                        roleSelect.appendChild(option);
                    });
                });
        }
    });

    function togglePassword(id) {
        const input = document.getElementById(id);
        const eyeOpen = document.getElementById("eye_open_" + id);
        const eyeClosed = document.getElementById("eye_closed_" + id);

        if (input.type === "password") {
            input.type = "text";
            eyeOpen.classList.add("hidden");
            eyeClosed.classList.remove("hidden");
        } else {
            input.type = "password";
            eyeOpen.classList.remove("hidden");
            eyeClosed.classList.add("hidden");
        }
    }
</script>
@endsection
