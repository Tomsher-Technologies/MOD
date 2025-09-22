

@props([
    'title',
    'value',
    'color' => 'cyan', 
])

@php
    $colors = [
        'cyan' => 'bg-cyan-500',
        'purple' => 'bg-purple-500',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
    ];
    $colorClass = $colors[$color] ?? $colors['cyan'];
@endphp

{{-- Main card with hover transition and shadow --}}
<div class="group relative overflow-hidden rounded-xl border border-gray-300 bg-white p-5 shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg">
    {{-- Subtle background decoration --}}
    <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full {{ $colorClass }} opacity-10 transition-transform duration-500 group-hover:scale-[15]"></div>

    <div class="relative z-10 flex items-center justify-between">
        <div>
            <p class="mb-1 text-sm font-medium text-gray-500">{{ $title }}</p>
            {{-- The h3 tag is targeted by JS for the number animation --}}
            <h3 class="animate-number mb-0 text-3xl font-bold text-gray-800" data-value="{{ $value }}">0</h3>
        </div>
        {{-- Icon wrapper with color and shadow --}}
        <div class="flex h-14 w-14 items-center justify-center rounded-full {{ $colorClass }} shadow-lg shadow-{{$color}}-500/30">
            {{-- The SVG icon is passed into this slot --}}
            {{ $slot }}
        </div>
    </div>
</div>