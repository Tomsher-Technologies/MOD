@extends('layouts.frontend_account', ['title' => __db('news_details')])

@section('content')
<section class="py-10 pb-0 bg-white">
    <div class="container mx-auto">
        <div class="grid md:grid-cols-1 gap-6">
            <div class="p-0">
                <h3 class="text-sm text-[#b68a35]">{{ date('M d, Y', strtotime($news->news_date)) }}</h3>
                <h2 class="text-[36px] mb-4 text-[#797E86]">
                    {{ $news->getTranslation('title', $lang) }}
                </h2>
                <img src="{{ asset(getUploadedImage($news->image)) }}" alt="{{ $news->getTranslation('title', $lang) }}"
                    class="w-full h-[600px] object-cover rounded mb-6">

                {!! $news->getTranslation('description', $lang) !!}

                <hr class="!border-[#ebebea] my-10">
            </div>
        </div>
    </div>
</section>

<!-- News / Events -->
    @if($relatedNews->count() > 0)
        <section class="bg-white mb-8 py-[50px]">
            <div class="container mx-auto">
                <h2 class="text-[40px] text-[#744e2e] mb-6"> {{ __db('related') }} {{ __db('news_events') }}</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    @foreach ($relatedNews as $new)
                        <a href="{{ route('news-details', base64_encode($new->id)) }}">
                            <div class="p-4 rounded-lg  border border-gray-200">
                                <img src="{{ asset(getUploadedImage($new->image)) }}" alt="{{ $new->getTranslation('title', $lang) }}" class="w-full h-70 object-cover rounded mb-3">
                                <h3 class="text-sm text-[#b68a35]">{{ date('M d, Y', strtotime($new->news_date)) }}</h3>
                                <p class="text-[20px] leading-[25px] font-medium mt-1 text-[#797E86]">
                                    {{ $new->getTranslation('title', $lang) }} </p>
                            </div>
                        </a>
                    @endforeach
                    
                
                </div>

            </div>
        </section>
    @endif
   
@endsection