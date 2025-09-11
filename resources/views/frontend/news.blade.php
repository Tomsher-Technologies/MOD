@extends('layouts.frontend_account', ['title' => __db('news_events')])

@section('content')
<section class="bg-white mb-8 py-[100px]">
    <div class="container mx-auto">

        <h2 class="text-[40px] leading-[40px] text-[#744e2e] mb-8">{{ __db('news_events') }}</h2>
        @if($news->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4   gap-6">
                @foreach ($news as $new)
                    <div class="p-4 rounded-lg  border border-gray-200">
                        <a href="{{ route('news-details', base64_encode($new->id)) }}">
                            <img src="{{ asset(getUploadedImage($new->image)) }}" alt="{{ $new->getTranslation('title', $lang) }}" class="w-full h-70 object-cover rounded mb-3">
                            <h3 class="text-sm text-[#b68a35]">{{ date('M d, Y', strtotime($new->news_date)) }}</h3>
                            <p class="text-[20px] leading-[25px] font-medium mt-1 text-[#797E86]">
                                {{ $new->getTranslation('title', $lang) }}
                            </p>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                {{ $news->links() }}
            </div>
        @else
            <div class="text-center">
                <p class="text-[20px] leading-[25px] font-medium mt-1 text-[#797E86]">
                    {{ __db('no_record_found') }}
                </p>
            </div>
        @endif
        
    </div>
</section>
@endsection