@extends('layouts.admin_account', ['title' => __db('event')])

@section('content')
<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('update_event_details') }}</h2>
        <a href="{{ route('events.index') }}" id="add-attachment-btn" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <form action="{{ route('events.update', $event) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg p-6 mb-10 mt-4">
            <div class="bg-white p-6 grid grid-cols-1 gap-5 mb-4">
                <div>
                    <h2 class="font-semibold mb-0 !text-[22px] mb-3 mt-5">{{ __db('event_details') }}
                    </h2>
                    <div class="delegate-row border bg-white p-6 rounded bg-gray-100 mb-2">
                        <div class="grid grid-cols-12 gap-5">

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('name') }} (English) <span class="text-red-600">*</span></label>
                                <input type="text" id="name_en" name="name_en" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('name_en', $event->name_en) }}">
                                @error('name_en')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-6">
                                <label class="form-label">{{ __db('name') }} (Arabic) <span class="text-red-600">*</span></label>
                                <input type="text" id="name_ar" name="name_ar" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('name_ar', $event->name_ar) }}">
                                @error('name_ar')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('logo') }} <span class="text-red-600">*</span></label>
                                <input type="file" name="logo" id="logo" class=" rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                @error('logo')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                                @if($event->logo)
                                    <img src="{{ asset($event->logo) }}" alt="Logo" width="100" height="100" class="mb-2 mt-4"><br>
                                @endif
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('login_image') }}</label>
                                <input type="file" name="image" id="image" class=" rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                                @error('image')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                                @if($event->image)
                                    <img src="{{ asset($event->image) }}" alt="Image" width="100" height="100" class="mb-2 mt-4"><br>
                                @endif
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('start_date') }} <span class="text-red-600">*</span></label>
                                <input type="date" name="start_date" id="start_date" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('start_date',$event->start_date) }}">
                                @error('start_date')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('end_date') }} <span class="text-red-600">*</span></label>
                                <input type="date" name="end_date" id="end_date" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('end_date', $event->end_date) }}">
                                @error('end_date')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3 form-group">
                                <label class="form-label">{{ __db('status') }} <span class="text-red-600">*</span></label>
                                <select name="status" id="status" class="w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    <option value="0" {{ (old('status', $event->status) === 0) ? 'selected' : '' }}>{{ __db('not_completed') }}</option>
                                    <option value="1" {{ (old('status', $event->status) === 1) ? 'selected' : '' }}>{{ __db('completed') }}</option>
                                </select>

                                @error('status')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-span-3">
                                <label class="form-label">{{ __db('Set as Default Event') }}</label>
                                
                                <select name="is_default" id="is_default" class="w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0">
                                    <option value="0" {{ (old('is_default', $event->is_default) === 0) ? 'selected' : '' }}>{{ __db('no') }}</option>
                                    <option value="1" {{ (old('is_default', $event->is_default) === 1) ? 'selected' : '' }}>{{ __db('yes') }}</option>
                                </select>

                                @error('is_default')
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

                <a href="{{ route('events.index') }}" class="btn text-md  !bg-[#637a85] border !border-[#637a85] !text-[#fff] rounded-lg h-12 mr-1">{{
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
