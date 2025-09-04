@extends('layouts.admin_account', ['title' => __db('delegations_by_division')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0"> {{ __db('delegations_by_division') }}</h6>
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

                    <div class="overflow-x-auto w-full mb-6">
                        <div id="pieChart"></div>   
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[400px]">
                            <thead>
                                <tr class="text-[13px]">
                                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('department') }}</th>
                                    <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">{{ __db('count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($delegatesByDivision as $division)
                                    <tr class=" text-[12px] align-[middle]">
                                        <td class="px-4 py-2 border border-gray-200">{{ $division->department_name }}</td>
                                        <td class="px-4 text-center py-2 border border-gray-200">{{ $division->total }}</td>
                                    </tr>
                                @endforeach
                            
                            </tbody>
                        
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    @php
        $baseColor = '#B68A35';
        $labelsCount = count($data['delegatesByDivision']['labels']);
        $colors = [];

        $spread = 30; // +/- percentage from base color

        for ($i = 0; $i < $labelsCount; $i++) {
        // Alternate dark/light slices
        $position = ($i % 2 == 0) ? -1 : 1; // even = darker, odd = lighter
        $step = ceil($i / 2); // step away from base
        $percent = $position * ($spread * $step / max(1, ceil($labelsCount / 2)));
        
        $colors[] = shadeColor($baseColor, $percent);
        }
    @endphp

@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        var labels = @json($data['delegatesByDivision']['labels'] ?? []);
        var series = @json($data['delegatesByDivision']['series'] ?? []);
        var colors = @json($colors ?? []);
            
        var chartData = labels.map(function(label, i) {
            return {
                    name: label,
                    y: series[i],
                    color: colors[i] || '#B68A35' // fallback color
            };
        });

        Highcharts.chart('pieChart', {
            chart: { type: 'pie', height: 400 },
            credits: { enabled: false },
            title: { text: null },
            tooltip: {
                    pointFormat: '{point.name}: <b>{point.actual}</b>',
                    formatter: function() {
                    var original = @json($data['delegatesByDivision']['series'] ?? [])[this.point.index];
                    return '<b>' + this.point.name + '</b>: ' + original;
                    }
            },
            plotOptions: {
                    pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '11px',  
                            fontWeight: 'bold', 
                            color: '#000' 
                        },
                        formatter: function() {
                                var original = @json($data['delegatesByDivision']['series'] ?? [])[this.point.index];
                                return this.point.name + '('+ original +') ' ;
                        }
                    },
                    borderWidth: 3,
                    borderColor: '#ffffff'
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
            series: [{
                    name: "{{ __db('delegates') }}",
                    colorByPoint: true,
                    data: chartData
            }]
        });
    });
</script>
@endsection