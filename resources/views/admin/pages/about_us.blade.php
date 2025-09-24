@extends('layouts.admin_account', ['title' => __db('edit') . ' ' . __db('about_us') .' '. __db('page')])

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold text-2xl">{{ __db('edit') . ' ' . __db('about_us') .' '. __db('page') }} - {{ $page->event?->getTranslation('name') }}</h2>
        <a href="{{ route('event_pages.index') }}"
            class="flex items-center bg-[#B68A35] text-white rounded-lg py-2 px-3 text-sm">
            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/>
            </svg>
            {{ __db('back') }}
        </a>
    </div>

    <div class="bg-white rounded-lg p-6 shadow space-y-4" x-data="{ tab: 'en' }">

        <div class="flex border-b border-gray-300 mb-4">
            @php
                $languages = [
                    'en' => ['name' => 'English', 'code' => 'en'],
                    'ar' => ['name' => 'Arabic', 'code' => 'ar'],
                ];
            @endphp
            @foreach ($languages as $lang)
                <button
                    class="px-4 py-2 -mb-px font-medium text-gray-600 border-b-2 transition-colors duration-200"
                    :class="tab === '{{ $lang['code'] }}' ? 'border-[#B68A35] text-black' : 'border-transparent hover:text-gray-800'"
                    @click="tab = '{{ $lang['code'] }}'">
                    {{ $lang['name'] }}
                </button>
            @endforeach
        </div>

        <form action="{{ route('event_pages.update', base64_encode($page->id)) }}" method="POST" enctype="multipart/form-data">
            @csrf
           
            @foreach ($languages as $lang)
                @php
                    $trans = $page->translations->firstWhere('lang', $lang['code']);
                @endphp
                <div x-show="tab === '{{ $lang['code'] }}'" class="space-y-4" x-cloak>
                    <div>
                        <label class="form-label">{{ __db('title') }} ({{ $lang['name'] }}) <span class="text-red-600">*</span></label>
                        <input type="text" name="translations[{{ $lang['code'] }}][title1]" value="{{ $trans->title1 ?? '' }}"
                               @if($lang['code']=='en') dir="ltr" @endif
                               class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" 
                               @if($lang['code']=='en')  @endif>
                    </div>

                    <div>
                        <label class="form-label">{{ __db('content') }} ({{ $lang['name'] }}) <span class="text-red-600">*</span></label>
                        <textarea name="translations[{{ $lang['code'] }}][content1]"
                                  @if($lang['code']=='en') dir="ltr" @else dir="rtl" @endif
                                  rows="5"
                                  class="texteditor p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">{!! old('translations.' . $lang['code'] . '.content1', $trans->content1 ?? '') !!}</textarea>
                    </div>

                    <div>
                        <label class="form-label">{{ __db('image') }} ({{ $lang['name'] }}) <span class="text-red-600">*</span></label>
                        <input type="file" name="translations[{{ $lang['code'] }}][image]" class="rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">

                        <img id="imagePreview" src="{{ asset(getUploadedImage($trans->image)) }}" alt="{{ $trans->title1 ?? '' }}" class="mt-3 h-30 w-20 img-thumbnail  {{ $trans->image ? '' : 'd-none' }}"/>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                <button type="submit" class="bg-[#B68A35] text-white py-2 px-6 rounded-lg hover:bg-[#a5752b]">
                    {{ __db('submit') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        
    });
</script>
@endsection
