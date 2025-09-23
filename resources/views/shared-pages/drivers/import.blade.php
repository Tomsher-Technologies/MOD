<div   >
    <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('import') . ' ' . __db('drivers') }}</h2>
        <a href="{{ route('drivers.index') }}" class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            <span>{{ __db('back') }}</span>
        </a>
    </div>
    
    <div class="bg-white h-full w-full rounded-lg border-0 p-30 mb-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="border rounded-lg text-center p-10 block">
                <a href="{{ asset('assets/excel/sample_drivers.xlsx') }}" download class="btn text-md mb-[-10px] !bg-[#B68A35] text-white rounded-lg w-full">
                    <svg class="w-6 h-6 text-white pe-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12-4-4m4 4 4-4" />
                    </svg>
                    <span>{{ __db('download_sample_drivers_excel') }}</span>
                </a>

                <p class="text-sm mt-4">
                    {!! __db('download_sample_drivers_excel_description') !!}
                </p>
            </div>
    
        </div>

        <div class="border rounded-lg text-center p-10 block mt-8">
            <form action="{{ route('drivers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-span-3 mb-2">
                    <input type="file" name="file" class="rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0">
                    @error('file')  
                        <div class="text-red-600">{{ $message }}</div>
                        @enderror
                </div>
                <button type="submit" class="btn text-md mb-[-10px] border !border-[#B68A35] text-[#B68A35] rounded-lg ">
                    <svg class="w-6 h-6 text-[#B68A35] me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
                    </svg>
                    <span class="text-[#B68A35]">{!! __db('upload_excel') !!}</span>
                </button>

                <p class="text-sm mt-4">
                    {!! __db('upload_excel_description') !!}
                </p>
            </form>
        </div>

    </div>
</div>