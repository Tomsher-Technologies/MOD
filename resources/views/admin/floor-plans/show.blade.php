@extends('layouts.admin_account', ['title' => __db('view_floor_plan')])

@section('content')
    <x-back-btn title="" back-url="{{ route('floor-plans.index') }}" />

    <div class="bg-white h-full w-full rounded-lg border-0 p-6">
        <h2 class="font-semibold text-2xl mb-6">{{ __db('floor_plan_details') }}</h2>

        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12 md:col-span-6">
                <label class="form-label font-medium">{{ __db('event') }}:</label>
                <p class="text-gray-700">{{ $floorPlan->event->name ?? '-' }}</p>
            </div>

            <div class="col-span-12 md:col-span-6">
                <label class="form-label font-medium">{{ __db('created_at') }}:</label>
                <p class="text-gray-700">{{ $floorPlan->created_at ? $floorPlan->created_at->format('d-m-Y H:i') : '-' }}</p>
            </div>

            <div class="col-span-12 md:col-span-6">
                <label class="form-label font-medium">{{ __db('title_en') }}:</label>
                <p class="text-gray-700">{{ $floorPlan->title_en }}</p>
            </div>

            <div class="col-span-12 md:col-span-6">
                <label class="form-label font-medium">{{ __db('title_ar') }}:</label>
                <p class="text-gray-700" dir="rtl">{{ $floorPlan->title_ar }}</p>
            </div>

            <div class="col-span-12">
                <label class="form-label font-medium">{{ __db('floor_plan_files') }}:</label>
                <div class="border rounded-lg p-4">
                    @if(count($floorPlan->file_paths) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($floorPlan->file_paths as $index => $filePath)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-500 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M3 3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H3Zm1.741 4.47L7.5 10.724 11.259 7l1.2 1.2-5.018 4.542a.6.6 0 0 1-.844-.001L2.542 8.2l1.2-1.2Zm2.434 5.745L11.259 18l-1.2 1.2-5.018-4.542a.6.6 0 0 1-.001-.844L9.584 8.8l1.2 1.2Zm7.671-1.2-1.2-1.2 5.018-4.542a.6.6 0 0 1 .844-.001L21.458 15.8l-1.2 1.2-4.046-4.045Zm-1.2 6.47-1.2-1.2 4.046-4.045 1.2 1.2-4.045 4.045Z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ basename($filePath) }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank" 
                                       class="inline-flex items-center text-[#B68A35] hover:underline">
                                        <svg class="w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12 4-4m-4 4-4-4"/>
                                        </svg>
                                        {{ __db('download') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">{{ __db('no_files_uploaded') }}</p>
                    @endif
                </div>
            </div>

            <div class="col-span-12 mt-6">
                <div class="flex gap-3">
                    @directCanany(['edit_floor_plans'])
                        <a href="{{ route('floor-plans.edit', $floorPlan->id) }}"
                            class="btn !bg-[#B68A35] text-white rounded-lg py-2 px-4">
                            {{ __db('edit_floor_plan') }}
                        </a>
                    @enddirectCanany
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete button clicks
            document.querySelectorAll('.delete-floor-plan-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const floorPlanId = this.getAttribute('data-floor-plan-id');
                    const floorPlanTitle = this.getAttribute('data-floor-plan-title');
                    
                    Swal.fire({
                        title: '{{ __db('are_you_sure') }}',
                        text: "{{ __db('delete_floor_plan_confirm_msg') }} " + floorPlanTitle + "?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __db('yes_delete') }}',
                        cancelButtonText: '{{ __db('cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create a form dynamically and submit it
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ url('/mod-admin/floor-plans') }}/" + floorPlanId;
                            
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            
                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection