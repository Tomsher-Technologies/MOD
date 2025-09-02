<div {{ $attributes->merge(['class' => $attributes->get('class') ?? 'flex flex-wrap items-center justify-between gap-2 mb-6']) }}>
    @if (isset($title))
        <h2 class="font-semibold mb-0 !text-[22px]">{{ $title }}</h2>
    @endif
    <a href="{{ $backUrl }}" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 12H5m14 0-4 4m4-4-4-4" />
        </svg>
        <span>Back</span>
    </a>
</div>

