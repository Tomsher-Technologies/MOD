@extends('layouts.frontend_account', ['title' => __db('about_us')])

@section('content')
<section class="py-[100px] bg-[#f2f2f2] important_contacts">
    <div class="container mx-auto ">
        <div class="grid grid-cols-12">
            <div class="col-span-6 pe-[100px]">
                <h2 class="text-[40px] leading-[40px] text-[#744e2e] mb-8">{{ $page->getTranslation('title1', $lang) }}</h2>
            </div>

            <div class="col-span-6">
                {!! $page->getTranslation('content1', $lang) !!}
            </div>
        </div>
        <img src="{{ $page->getTranslation('image', $lang) }}" class="w-full rounded-lg mt-12" alt="">
    </div>
</section>
@endsection