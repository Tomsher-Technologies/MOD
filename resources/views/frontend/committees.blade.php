@extends('layouts.frontend_account', ['title' => __db('committees')])

@section('content')
<section class="bg-white mb-8  py-[100px]">
    <div class="container mx-auto">
        <h2 class="text-[40px] leading-[40px] text-[#744e2e] mb-8">{{ $page->getTranslation('title1', $lang) }}</h2>
        <div class="mb-8">
            {!! $page->getTranslation('content1', $lang) !!}
        </div>
        <hr class="my-6 border-gray-300 mb-6">
        <!-- Search and Filter Controls -->
        <div class="w-[70%] ms-auto justify-end gap-4 mb-6">
            <form id="searchForm" action="{{ route('committees') }}" method="GET" class="flex gap-2">
                <input id="searchInput" type="text" placeholder="{{ __db('search') }}" name="search"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-1/2 focus:outline-none focus:ring-2 focus:ring-[#b68a35]" value="{{ request('search') }}"/>
                <select id="designationFilter" name="designation_id" class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-1/4 focus:outline-none focus:ring-2 focus:ring-[#b68a35]">
                    <option value="">{{ __db('select_designation') }}</option>
                    @foreach($availableDesignations as $designation)
                        <option value="{{ $designation->id }}" @selected(request('designation_id') == $designation->id)>
                            {{ $designation?->value }}
                        </option>
                    @endforeach
                </select>
                <select id="committeeFilter" name="committee_id"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-1/4 focus:outline-none focus:ring-2 focus:ring-[#b68a35]">
                    <option value="">{{ __db('select_committee') }}</option>
                    @foreach($availableCommittees as $committee)
                        <option value="{{ $committee->id }}" @selected(request('committee_id') == $committee->id)>
                            {{ $committee?->value }}
                        </option>
                    @endforeach
                </select>
              
                <button type="submit"   class="bg-[#B68A35] text-white py-2 px-6 rounded-lg hover:bg-[#a5752b]">
                    {{ __db('filter') }}
                </button>
                <a href="{{ route('committees') }}" class="bg-[#B68A35] text-white py-2 px-6 rounded-lg hover:bg-[#a5752b]">
                    {{ __db('reset') }}
                </a>
            </form>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200">
                <thead class="bg-[#f7f7f7]">
                    <tr>
                        <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('sl_no') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('name') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('email') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('phone') }}</th>
                        {{-- <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('military_no') }}</th> --}}
                        <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('designation') }}</th>
                        <th class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('committee') }}</th>
                    </tr>
                </thead>
                <tbody id="committeesTable">
                    @forelse ($committees as $i => $com)
                        <tr>
                            <td class="px-4 py-3 border">{{ $i+1 }}</td>
                            <td class="px-4 py-3 border">{{ $com->getTranslation('name', $lang) }}</td>
                            <td class="px-4 py-3 border">{{ $com->email }}</td>
                            <td class="px-4 py-3 border text-end" dir="ltr">{{ $com->phone }}</td>
                            {{-- <td class="px-4 py-3 border">{{ $com->military_no }}</td> --}}
                            <td class="px-4 py-3 border">{{ $com->designation?->value }}</td>
                            <td class="px-4 py-3 border">{{ $com->committee?->value }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-3 text-center" colspan="7" dir="ltr">{{ __db('no_data_found') }}</td>
                        </tr>                        
                    @endforelse
                    
                   
                </tbody>
            </table>
        </div>




    </div>
</section>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('searchForm');
        const inputs = [
            document.getElementById('searchInput'),
            document.getElementById('designationFilter'),
            document.getElementById('committeeFilter')
        ];

        inputs.forEach(input => {
            input.addEventListener('change', function() {
                form.submit();
            });
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                form.submit();
            }
        });
    });
</script>

@endsection