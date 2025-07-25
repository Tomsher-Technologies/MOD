@extends('layouts.admin_account', ['title' => __db('create_new_staff')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('create_new_staff') }}</h2>
        <a href="{{ route('staffs.index') }}" id="add-attachment-btn" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <form action="{{ route('staffs.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white p-6 grid grid-cols-1 gap-5 mb-4">
                <div>
                    <h2 class="font-semibold mb-0 !text-[22px] mb-3 mt-5">{{ __db('staff_details') }}
                    </h2>
                    <div class="delegate-row border bg-white p-6 rounded bg-gray-100 mb-2">
                        <div class="grid grid-cols-12 gap-5">

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('name') }} <span class="text-red-600">*</span></label>
                                <input type="text" id="name" name="name" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('email') }} <span class="text-red-600">*</span></label>
                                <input type="text" id="email" name="email" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('phone') }}</label>
                                <input type="text" id="mobile" name="mobile" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('mobile') }}">
                                @error('mobile')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('role') }} <span class="text-red-600">*</span></label>
                                <select name="role" class=" p-3 rounded-lg w-full border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ (old('role') == $role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('password') }} <span class="text-red-600">*</span></label>
                                <input type="password" id="password" name="password" autocomplete="new-password" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('password') }}">
                                @error('password')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('confirm_password') }} <span class="text-red-600">*</span></label>
                                <input type="text" id="password_confirmation" name="password_confirmation" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
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

                <a href="{{ route('staffs.index') }}" class="btn text-md  !bg-[#637a85] border !border-[#637a85] !text-[#fff] rounded-lg h-12 mr-1">{{
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

</script>
@endsection
