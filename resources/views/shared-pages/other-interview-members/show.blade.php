<div class="dashboard-main-body ">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6 mb-10">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('interview_member_details') }}</h2>
        <a href="{{ Session::has('interview_member_last_url') ? Session::get('interview_member_last_url') : route('other-interview-members.index') }}"
            id="add-attachment-btn"
            class="float-left btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>

    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <div class="bg-white rounded-lg shadow p-6 mb-10">
           

            <div class=" mx-auto">
                <div class="border rounded-lg p-6 shadow-sm">
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700 mb-1">{{ __db('name_en') }}:</label>
                        <p class="text-gray-900 text-lg">{{ $interviewMember->name_en ?? '-' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700 mb-1">{{ __db('name_ar') }}:</label>
                        <p class="text-gray-900 text-lg">{{ $interviewMember->name_ar ?? '-' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700 mb-1">{{ __db('status') }}:</label>
                        @if ($interviewMember->status)
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded">
                                {{ __db('active') }}
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-black bg-yellow-400 rounded">
                                {{ __db('inactive') }}
                            </span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700 mb-1">{{ __db('associated_event') }}:</label>
                        <p class="text-gray-900 text-lg">
                            {{ $interviewMember->event->code ?? '-' }} -
                            {{ $interviewMember->event->name_en ?? '-' }}
                            @if ($interviewMember->event?->is_default)
                                <span
                                    class="inline-block rounded bg-green-500 px-2 py-1 text-xs font-semibold text-white ml-2">{{ __db('default') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-start items-center gap-5 mt-6">
                @directCanany(['edit_other_interview_members'])
                    <a href="{{ route('otherInterviewMembers.edit', ['id' => base64_encode($interviewMember->id)]) }}"
                        class="btn !bg-yellow-400 text-yellow-900 rounded-lg px-6 py-2 font-semibold hover:bg-yellow-500">
                        {{ __db('edit') }}
                    </a>
                @endcan
                {{-- <a href="{{ route('other-interview-members.index') }}"
                    class="btn !bg-gray-600 text-white rounded-lg px-6 py-2 font-semibold hover:bg-gray-700">
                    {{ __db('back_to_interview_members') }}
                </a> --}}
            </div>
        </div>
    </div>
</div>


@section('script')
    <script></script>
@endsection
