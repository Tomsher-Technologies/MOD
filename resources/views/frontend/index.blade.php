@extends('layouts.frontend_account', ['title' => __db('home')])

@section('content')
    <section class="w-full h-[100vh] relative">
        <!-- Background Video -->
        <video autoplay muted loop playsinline class="w-full h-full object-cover -z-20">
            <source src="{{ asset(getUploadedImage($page?->getTranslation('link', $lang))) }}" type="video/mp4" />
            Your browser does not support the video tag.
        </video>
        <!-- Top Gradient -->
        <div class="absolute top-0 left-0 w-full h-[40%] 
                bg-gradient-to-b from-white/100 via-white/40 to-transparent z-10"></div>
        <!-- Bottom Gradient -->
        <div class="absolute bottom-0 left-0 w-full h-[80%] 
                bg-gradient-to-t from-black/80 via-black/30 to-transparent z-10"></div>
        <!-- Text Content -->
        <div class="absolute inset-0 z-20 flex justify-start items-end px-6 pb-[80px]">
            <div class="container mx-auto">
                <h2 class="text-[80px] md:text-6xl font-bold text-white mb-4">{{ $page?->getTranslation('title1', $lang) }}</h2>
                <p class="text-white text-[28px] mb-6">
                    {{ $page?->getTranslation('title2', $lang) }}
                </p>
                <a href="{{ route('about-us') }}"
                    class="inline-block bg-[#b68a35] text-white px-8 py-3 rounded-lg hover:bg-[#9e7526] transition">
                    {{ $page?->getTranslation('btn_link_1', $lang) }}
                </a>
            </div>
        </div>
        <!-- Overlay (Optional) -->
        <div class="fixed top-0 left-0 w-full h-20 -z-10"></div>
    </section>
    <!-- Important Contacts Section -->
    <section class="py-[100px] bg-[#f2f2f2] important_contacts">
        <div class="container mx-auto ">
            <div class="grid grid-cols-12">
                <div class="col-span-6 pe-[100px]">
                    <h2 class="text-[40px] leading-[40px] text-[#744e2e] mb-8">
                        {{ $page?->getTranslation('title3', $lang) }}
                    </h2>
                </div>
                <div class="col-span-6 text-[#797E86]">
                    {!! $page?->getTranslation('content1', $lang) !!}
                    <br>
                    <a href="{{ route('about-us') }}"
                        class="inline-block bg-[#b68a35] mt-4 text-white px-8 py-3 rounded-lg hover:bg-[#9e7526] transition">
                        {!! $page?->getTranslation('btn_link_2', $lang) !!}
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Important Contacts Section -->
    <section class="py-16 ">
        <div class="container mx-auto ">
            <div class="grid grid-cols-12">
                <div class="col-span-6 pe-[100px]">
                    <h2 class="text-[40px] leading-[40px] text-[#744e2e] mb-8"> {!! $page?->getTranslation('title4', $lang) !!}</h2>
                    <hr class="border-[#ebebea] mb-8" />
                    {!! $page?->getTranslation('content2', $lang) !!}
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2 col-span-6">
                    <!-- Contact Card -->
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page?->getTranslation('title5', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page?->getTranslation('content5', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page?->getTranslation('content5', $lang) !!}</a>
                        </div>
                    </div>
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page?->getTranslation('title6', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page?->getTranslation('content6', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page?->getTranslation('content6', $lang) !!}</a>
                        </div>
                    </div>
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page?->getTranslation('title7', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page?->getTranslation('content7', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page?->getTranslation('content7', $lang) !!}</a>
                        </div>
                    </div>
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page?->getTranslation('title8', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page?->getTranslation('content8', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page?->getTranslation('content8', $lang) !!}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-[160px]  bg-cover bg-center relative" style="background-image: url('{{ asset($page?->getTranslation('image', $lang)) }}');">
        <div class="container mx-auto relative z-10">
            <h2 class="text-[40px] text-[#744e2e] mb-2">{!! $page?->getTranslation('content3', $lang) !!}</h2>
            <p class="text-[#797E86]">{!! $page?->getTranslation('content4', $lang) !!}
            </p>
            <a href="{{ route('committees') }}"
                class="mt-3 inline-block bg-[#b68a35] text-white px-8 py-3 rounded-lg hover:bg-[#9e7526] text-end me-auto">
                {!! $page?->getTranslation('btn_link_3', $lang) !!}
            </a>
        </div>
    </section>
    <section class="bg-white mb-8 py-[100px]">
        <div class="container mx-auto">
            <h2 class="text-[40px] text-[#744e2e] mb-6">{!! $page?->getTranslation('title9', $lang) !!}</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-3 gap-6">
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
            <div class="mt-6 text-center">
                <a href="{{ route('news') }}"
                    class="mt-3 inline-block bg-[#b68a35] text-white px-8 py-3 rounded-lg hover:bg-[#9e7526] text-end me-auto">
                    {!! $page?->getTranslation('btn_link_4', $lang) !!}
                </a>
            </div>
        </div>
    </section>

@endsection
