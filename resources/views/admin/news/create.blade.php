@extends('layouts.admin_account', ['title' => __db('create') . ' ' . __db('news')])

@section('content')
    <div class="">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 !text-[22px]">{{__db('add') . ' ' . __db('news') }}</h2>
            <a href="{{ route('news.index') }}"
                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                <svg class="w-6 h-6 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
                <span>{{ __db('back') }}</span>
            </a>
        </div>
       
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
            <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-12 gap-5">

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('event') }} <span class="text-red-500">*</span> :</label>
                        <select name="event_id" id="event_id" class="select2 w-full p-3 rounded-lg border border-neutral-300 text-sm text-neutral-600 focus:border-primary-600 focus:ring-0" >
                            <option value="">{{ __db('select') . ' ' . __db('event') }}</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id', session('current_event_id')) == $event->id ? 'selected' : '' }}>{{ $event->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('news_date') }} <span class="text-red-500">*</span> :</label>
                        <input type="date" name="news_date" class=" rounded-lg h-11  w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('news_date') }}">
                        @error('news_date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-4">
                        <label class="form-label">{{ __db('image') }} <span class="text-red-500">*</span> :</label>
                        <input type="file" name="image"
                            class=" rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                        <small class="text-gray-500 text-xs block mt-1">
                            ({{ __db('news_image_recommended_size') }})
                        </small>
                        @error('image')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">{{ __db('title') }} ({{ __db('english') }}) <span class="text-red-500">*</span> :</label>
                        <input type="text" name="title_en" dir="ltr"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('title_en') }}" >
                        @error('title_en')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('title') }} ({{ __db('arabic') }}) <span class="text-red-500">*</span> :</label>
                        <input type="text" name="title_ar"
                            class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            value="{{ old('title_ar') }}" >
                        @error('title_ar')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('description') }} ({{ __db('english') }}) <span class="text-red-500">*</span> :</label>
                        <textarea name="description_en" rows="5" dir="ltr"
                            class="texteditor p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            >{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">{{ __db('description') }} ({{ __db('arabic') }}) <span class="text-red-500">*</span> :</label>
                        <textarea name="description_ar" rows="5" dir="rtl"
                            class="texteditor p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                            >{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    
                </div>

                <div class="flex justify-between items-center mt-8">
                    <button type="submit" class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg h-12">
                        {{ __db('submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection