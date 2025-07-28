@extends('layouts.admin_account',['title' => __db('bulk_import_options')])

@section('content')

<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <h2 class="font-semibold mb-0 !text-[22px]">{{ __db('bulk_import_options') }}</h2>

    <div>
        <a href="{{ route('dropdowns.index') }}">
            <button type="button" class="float-left btn text-md mb-[-10px] border !border-[#B68A35] !text-[#B68A35] rounded-lg h-12">
            <svg class="w-6 h-6 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 12H5m14 0-4 4m4-4-4-4" />
            </svg>
            
             {{ __db('back') }}</button>
        </a>

    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-3 h-full w-full" dir="ltr">
    <div class="xl:col-span-12 h-full">
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg  mx-auto">
            <!-- Title -->
            <h2 class=" font-semibold text-gray-800 mb-4">Import Dropdown Options</h2>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-xl p-4 mb-6">
                <p class="text-xl mb-2"><strong> Instructions: </strong></p>
                <ul class="list-disc pl-5 space-y-1 text-lg">
                    <li>Use the <strong>sample Excel file</strong> to prepare your dropdown options.</li>
                    
                    <li class="mt-2"><strong>Required columns</strong> in the file:
                        <ul class="list-disc pl-6 mt-4 text-gray-700">
                            <li><strong>Code</strong> - Dropdown code (Check dropdown list)</li>
                            <li><strong>Value</strong> - Option name</li>
                            <li><strong>Sort Order</strong> - Numeric sort order</li>
                            <li><strong>Status</strong> - Use 1 for active, 0 for inactive</li>
                        </ul>
                    </li>
                    <li  class="mt-2">If a value already exists, its <strong>status</strong> and <strong>sort order</strong> will be updated.</li>
                </ul>
            </div>

            <!-- Sample Download -->
            <div class="mb-6">
                <a href="{{ asset('assets/excel/dropdown-options-sample.xlsx') }}" download
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 12l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Sample File
                </a>
            </div>

            <!-- File Upload Form -->
            <form action="{{ route('admin.dropdowns.import') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- File Input -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Select Excel File</label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv"
                       class="w-[50%] block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">

                       
                </div>
                <!-- Submit -->
                <div class="mt-6">
                    <button type="submit" class="btn text-md  !bg-[#B68A35] text-white rounded-lg h-12 mr-4">
                        {{ __db('import_options') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
  
</script>
@endsection