@extends('layouts.admin_account', ['title' => __db('arrival_status')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0"> {{ __db('arrival_status') }}</h6>
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

                    <div class="flex items-center justify-between mb-6">
                        <div class="w-[50%]">
                            <div id="userOverviewDonutChart" class="apexcharts-tooltip-z-none"></div>
                        </div>

                        <div class="w-[45%] ml-2">
                            @php
                                $arrivalData = $data['arrival_status'];
                                $totalCount = array_sum($arrivalData);
                            @endphp
                            <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[200px]">
                                <thead>
                                    <tr class="text-[13px]">
                                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('status') }}</th>
                                        <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">{{ __db('count') }}</th>
                                        <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">{{ __db('percentage') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($arrivalData as $status => $count)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2 capitalize">
                                                {{ __db($status) }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $count }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                {{ $totalCount > 0 ? number_format(($count / $totalCount) * 100, 2) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class=" align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[16px]">
                                        <td class="px-4 py-2 border border-gray-200">{{ __db('total') }}</td>
                                        <td class="px-4 py-2 text-center border border-gray-200">{{ $totalCount }}</td>
                                        <td class="px-4 py-2 text-center border border-gray-200">
                                            {{ $totalCount > 0 ? number_format($totalCount / $totalCount * 100, 2) : 0 }}% </td>
                                    </tr>
                                </tbody>
                              
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        Highcharts.chart('userOverviewDonutChart', {
            chart: {
                type: 'pie',
                height: 400
            },
            credits: { enabled: false },
            title: {
                text: ''
            },
            plotOptions: {
                pie: {
                    innerSize: '50%', 
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '11px',  
                            fontWeight: 'bold', 
                            color: '#000'       
                        },
                        format: '{point.name}: {point.percentage:.1f}%'
                    }
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                itemMarginBottom: 0,
                itemStyle: {
                    fontSize: '11px', 
                    fontWeight: 'normal',
                    color: '#333'
                },
                navigation: { enabled: true } 
            },
            colors: ['#B68A35', '#F2ECCF', '#D7BC6D', '#E6D7A2'],
            series: [{
                name: '{{ __db('delegates') }}',
                data: [
                    { name: '{{ __db('to_be_arrived') }}', y: {{ $data['arrival_status']['to_be_arrived'] }} },
                    { name: '{{ __db('arrived') }}', y: {{ $data['arrival_status']['arrived'] }} },
                    { name: '{{ __db('to_be_departed') }}', y: {{ $data['arrival_status']['to_be_departed'] }} },
                    { name: '{{ __db('departed') }}', y: {{ $data['arrival_status']['departed'] }} }
                ]
            }]
        });
    });
</script>
@endsection