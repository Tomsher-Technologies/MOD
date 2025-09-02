@extends('layouts.admin_account', ['title' => __db('delegates_by_participation_status')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0"> {{ __db('delegates_by_participation_status') }}</h6>
                        <a href="{{ route('admin.dashboard') }}"
                            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                            </svg>
                            <span>{{ __db('back') }}</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto w-full">
                       <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[400px]">
                            <thead>
                                <tr class="text-[11px]">
                                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('department') }}</th>
                                    @foreach($data['delegatesByParticipationTable']['statuses'] as $status)
                                        <th scope="col" class=" text-center p-3 !bg-[#B68A35] text-white border !border-[#cbac71]">{{ __db($status) }}</th>
                                    @endforeach
                                    <th scope="col" class="p-3 !bg-[#B68A35]  text-center text-white border !border-[#cbac71]">{{ __db('total') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($data['delegatesByParticipationTable']['departments'] as $dept)
                                    <tr class="align-[middle] text-[11px]">
                                        <td class="px-4 py-2 border border-gray-200">{{ $dept->value }}</td>
                                        @foreach($data['delegatesByParticipationTable']['statuses'] as $status)
                                            <td class="px-4 py-2 text-center border border-gray-200">
                                                {{ $data['delegatesByParticipationTable']['tableData'][$dept->id][$status] ?? 0 }}
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-2 text-center border border-gray-200"><strong>{{ $data['delegatesByParticipationTable']['rowTotals'][$dept->id] }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="  align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[16px]">
                                    <th class="text-start px-4 py-2 border border-gray-200">{{ __db('total') }}</th>
                                    @foreach($data['delegatesByParticipationTable']['statuses'] as $status)
                                        <th class="px-4 py-2 border border-gray-200 ">{{ $data['delegatesByParticipationTable']['colTotals'][$status] }}</th>
                                    @endforeach
                                    <th class="px-4 py-2 border border-gray-200">{{ $data['delegatesByParticipationTable']['grandTotal'] }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </div>
                </div>
            </div> 
        </div>
    </div>
@endsection