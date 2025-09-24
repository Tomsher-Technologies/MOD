<div>

    <x-back-btn title="{{ __db('interview_member_details') }}"
        back-url="{{ Session::has('other_interview_member_show_last_url') ? Session::get('other_interview_member_show_last_url') : route('other-interview-members.index') }}" />


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
        </div>
    </div>
</div>
</div>


@section('script')
<script></script>
@endsection
