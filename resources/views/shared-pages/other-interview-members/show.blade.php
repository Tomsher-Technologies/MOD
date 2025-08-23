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
        {{-- <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-2xl font-bold text-primary-700 mb-6">
                    {{ __db('event_information') }}
                    @if ($interviewMember->event?->is_default)
                        <span
                            class="inline-block rounded bg-green-500 px-2 py-1 text-xs font-semibold text-white">Default</span>
                    @endif
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <div class="bg-white rounded-lg p-6 space-y-4 max-w-md">

                            <div class="space-y-3 text-gray-700">
                                <div class="flex justify-between">
                                    <span class="font-semibold">{{ __db('name') }} (EN) :</span>
                                    <span>{{ $interviewMember->event->name_en }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">{{ __db('name') }} (AR) :</span>
                                    <span>{{ $interviewMember->event->name_ar }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">{{ __db('code') }} :</span>
                                    <span>{{ $interviewMember->event->code }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">{{ __db('start_date') }} :</span>
                                    <span>{{ $interviewMember->event->start_date }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold">{{ __db('end_date') }} :</span>
                                    <span>{{ $interviewMember->event->end_date }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">{{ __db('status') }} :</span>
                                    @if ($interviewMember->event->status)
                                        <span
                                            class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">{{ __db('completed') }}</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">{{ __db('not_completed') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col items-center text-center">
                                <div class="text-sm font-semibold text-gray-700 mb-2">
                                    {{ __db('logo') }}
                                </div>
                                <div class="w-full h-32 flex items-center justify-center rounded overflow-hidden">
                                    <img src="{{ getUploadedImage($interviewMember->event->logo) }}" alt="Logo"
                                        class="max-w-full max-h-full object-contain">
                                </div>
                            </div>

                            @if ($interviewMember->event->image)
                                <div
                                    class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col items-center text-center">
                                    <div class="text-sm font-semibold text-gray-700 mb-2">
                                        {{ __db('image') }}
                                    </div>
                                    <div class="w-full h-32 flex items-center justify-center rounded overflow-hidden">
                                        <img src="{{ getUploadedImage($interviewMember->event->image) }}" alt="Image"
                                            class="max-w-full max-h-full object-contain">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}

        <div class="bg-white rounded-lg shadow p-6 mb-10">
            <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-4 mb-6">
                {{ __db('interview_member_details') }}</h2>

            <div class=" mx-auto">
                <div class="border rounded-lg p-6 shadow-sm">
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700 mb-1">{{ __db('name') }} (EN):</label>
                        <p class="text-gray-900 text-lg">{{ $interviewMember->name_en ?? '-' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700 mb-1">{{ __db('name') }} (AR):</label>
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
                @can('edit_interview_members')
                    <a href="{{ route('otherInterviewMembers.edit', ['id' => base64_encode($interviewMember->id)]) }}"
                        class="btn !bg-yellow-400 text-yellow-900 rounded-lg px-6 py-2 font-semibold hover:bg-yellow-500">
                        {{ __db('edit') }}
                    </a>
                @endcan
                <a href="{{ route('other-interview-members.index') }}"
                    class="btn !bg-gray-600 text-white rounded-lg px-6 py-2 font-semibold hover:bg-gray-700">
                    {{ __db('back_to_interview_members') }}
                </a>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script></script>
@endsection
