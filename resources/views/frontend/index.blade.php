@extends('layouts.frontend_account', ['title' => __db('home')])

@section('content')
    <section class="w-full h-[100vh] relative">
        <!-- Background Video -->
        <video autoplay muted loop playsinline class="w-full h-full object-cover -z-20">
            <source src="{{ asset(getUploadedImage($page->getTranslation('link', $lang))) }}" type="video/mp4" />
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
                <h2 class="text-[80px] md:text-6xl font-bold text-white mb-4">{{ $page->getTranslation('title1', $lang) }}</h2>
                <p class="text-white text-[28px] mb-6">
                    {{ $page->getTranslation('title2', $lang) }}
                </p>
                <a href="{{ route('about-us') }}"
                    class="inline-block bg-[#b68a35] text-white px-8 py-3 rounded-lg hover:bg-[#9e7526] transition">
                    {{ $page->getTranslation('btn_link_1', $lang) }}
                </a>
            </div>
        </div>
        <!-- Overlay (Optional) -->
        <div class="fixed top-0 left-0 w-full h-20 -z-10"></div>
    </section>





@php
    // DEMO DATA: Pass this from your controller.
    $floorPlans = [
        (object) [
            'name' => 'Ground Floor & Lobby',
            'files' => [
                ['type' => 'PDF', 'url' => '#'],
                ['type' => 'Image', 'url' => '#'],
                ['type' => 'DWG', 'url' => '#'],
            ],
        ],
        (object) [
            'name' => 'First Floor Conference Halls',
            'files' => [
                ['type' => 'PDF', 'url' => '#'],
                ['type' => 'Image', 'url' => '#'],
            ],
        ],
        (object) [
            'name' => 'Grand Exhibition Hall',
            'files' => [['type' => 'PDF', 'url' => '#']],
        ],
        (object) [
            'name' => 'Executive Suites',
            'files' => [
                ['type' => 'PDF', 'url' => '#'],
                ['type' => 'Image', 'url' => '#'],
            ],
        ],
        (object) [
            'name' => 'Basement & Parking Area',
            'files' => [['type' => 'PDF', 'url' => '#']],
        ],
        (object) [
            'name' => 'Rooftop Terrace',
            'files' => [
                ['type' => 'PDF', 'url' => '#'],
                ['type' => 'Image', 'url' => '#'],
            ],
        ],
    ];
@endphp

<section class="py-[100px] bg-[#f2f2f2]">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-[40px] text-[#744e2e] font-bold">Venue Layouts</h2>
            <p class="text-lg text-gray-500 mt-2 max-w-2xl mx-auto">Explore our versatile spaces. Select a plan to view details and download files.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach ($floorPlans as $plan)
                <div class="relative flex flex-col justify-between p-6 h-40 rounded-lg overflow-hidden bg-gradient-to-br from-white to-gray-50
                             shadow-lg group transform hover:-translate-y-2 transition-all duration-300 ease-in-out border border-transparent hover:border-[#b68a35]">
                    
                    <div class="absolute top-4 right-4 text-gray-200/50 transition-colors duration-300 group-hover:text-[#b68a35]/20">
                        <svg class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold text-[#744e2e] mb-3">{{ $plan->name }}</h3>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-2 flex-wrap">
                            @foreach ($plan->files as $file)
                                <a href="{{ $file['url'] }}" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-2 bg-gray-100 text-[#797E86] text-sm font-semibold px-3 py-1.5 rounded-full border border-gray-200 hover:bg-[#b68a35] hover:text-white hover:border-[#b68a35] transition-colors duration-200">
                                    
                                    @php $fileType = strtoupper($file['type']); @endphp

                                    @if ($fileType == 'PDF') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                    @elseif ($fileType == 'IMAGE') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    @elseif ($fileType == 'DWG') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                    @else <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h4.5m-4.5 0H5.625c-.621 0-1.125.504-1.125 1.125v-17.25c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125-1.125v17.25c0 .621-.504 1.125-1.125 1.125h-1.5m-4.5-12.75h.008v.008h-.008v-.008Z" /></svg>
                                    @endif
                                    
                                    <span>{{ $file['type'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>





    









    <!-- Important Contacts Section -->
    <section class="py-[100px] bg-[#f2f2f2] important_contacts">
        <div class="container mx-auto ">
            <div class="grid grid-cols-12">
                <div class="col-span-6 pe-[100px]">
                    <h2 class="text-[40px] leading-[40px] text-[#744e2e] mb-8">
                        {{ $page->getTranslation('title3', $lang) }}
                    </h2>
                </div>
                <div class="col-span-6 text-[#797E86]">
                    {!! $page->getTranslation('content1', $lang) !!}
                    <br>
                    <a href="{{ route('about-us') }}"
                        class="inline-block bg-[#b68a35] mt-4 text-white px-8 py-3 rounded-lg hover:bg-[#9e7526] transition">
                        {!! $page->getTranslation('btn_link_2', $lang) !!}
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
                    <h2 class="text-[40px] leading-[40px] text-[#744e2e] mb-8"> {!! $page->getTranslation('title4', $lang) !!}</h2>
                    <hr class="border-[#ebebea] mb-8" />
                    {!! $page->getTranslation('content2', $lang) !!}
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2 col-span-6">
                    <!-- Contact Card -->
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page->getTranslation('title5', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page->getTranslation('content5', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page->getTranslation('content5', $lang) !!}</a>
                        </div>
                    </div>
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page->getTranslation('title6', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page->getTranslation('content6', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page->getTranslation('content6', $lang) !!}</a>
                        </div>
                    </div>
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page->getTranslation('title7', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page->getTranslation('content7', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page->getTranslation('content7', $lang) !!}</a>
                        </div>
                    </div>
                    <div class="p-6 rounded-lg border border-[#b68a35]">
                        <h3 class="text-2xl text-[#b68a35] font-medium mb-4">{!! $page->getTranslation('title8', $lang) !!}</h3>
                        <hr class="border-[#ebebea] mb-6" />
                        <div class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-[#797E86]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                            <a href="tel:{!! $page->getTranslation('content8', $lang) !!}"
                                class="text-lg text-[#797E86] font-medium hover:text-[#b68a35] transition">{!! $page->getTranslation('content8', $lang) !!}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-[160px]  bg-cover bg-center relative" style="background-image: url('{{ asset($page->getTranslation('image', $lang)) }}');">
        <div class="container mx-auto relative z-10">
            <h2 class="text-[40px] text-[#744e2e] mb-2">{!! $page->getTranslation('content3', $lang) !!}</h2>
            <p class="text-[#797E86]">{!! $page->getTranslation('content4', $lang) !!}
            </p>
            <a href="{{ route('committees') }}"
                class="mt-3 inline-block bg-[#b68a35] text-white px-8 py-3 rounded-lg hover:bg-[#9e7526] text-end me-auto">
                {!! $page->getTranslation('btn_link_3', $lang) !!}
            </a>
        </div>
    </section>
    <section class="bg-white mb-8 py-[100px]">
        <div class="container mx-auto">
            <h2 class="text-[40px] text-[#744e2e] mb-6">{!! $page->getTranslation('title9', $lang) !!}</h2>
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
                    {!! $page->getTranslation('btn_link_4', $lang) !!}
                </a>
            </div>
        </div>
    </section>

@endsection
