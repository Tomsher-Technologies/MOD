@extends('layouts.admin_account', ['title' => __db('delegations_by_division')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6" id="print_area">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="!text-[20px] font-medium mb-0"> {{ __db('delegations_by_division') }}</h4>

                        <div class="flex items-center gap-2 no-print">
                            <button onclick="printSection('print_area')"  class=" no-print btn text-sm !bg-[#5c451d] flex items-center text-white rounded-lg py-2.5 px-3">
                                <svg class="ml-1 w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2m-12 0h12v3H6v-3Z" />
                                </svg>

                                {{ __db('print') }}
                            </button>
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
                    </div>

                    <div class="overflow-x-auto w-[90%] mb-6">
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
                                @php $grandTotal = 0; @endphp
                                @foreach($delegatesByDivision as $division)
                                    @php $grandTotal += $division->total; @endphp
                                    <tr class=" text-[12px] align-[middle]">
                                        <td class="px-4 py-2 border border-gray-200">{{ $division->department_name }}</td>
                                        <td class="px-4 text-center py-2 border border-gray-200">{{ $division->total }}</td>
                                    </tr>
                                @endforeach
                            
                                {{-- Total row --}}
                                <tr class=" align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[16px]">
                                    <td class="text-start px-4 py-2 border border-gray-200">{{ __db('total') }}</td>
                                    <td class="text-center px-4 py-2 border border-gray-200">{{ $grandTotal }}</td>
                                </tr>
                            </tbody>
                        
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    @php
      $colors = [
    '#FFB3BA', '#FFDFBA',  '#BAFFC9', '#BAE1FF',
    '#FFD6A5', '#FDFFB6', '#CAFFBF', '#9BF6FF', '#A0C4FF',
    '#BDB2FF', '#FFC6FF', '#D0F4DE', '#FFADAD', '#FFDAC1',
    '#E2F0CB', '#C7CEEA', '#F1C0E8', '#C2F0FC', '#FFF5BA',
    '#F8C8DC', '#C1E1C1', '#E0BBE4', '#D5F4E6', '#FFB7B2',
    '#B5EAD7', '#E2F0D9', '#F1E3DD', '#D4A5A5', '#C9BBCF'
];
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

    function printSection(divId, chartId = null) {
        const contentDiv = document.getElementById(divId);
        const clonedContent = contentDiv.cloneNode(true);

        if (chartId) {
            const chart = Highcharts.charts.find(c => c && c.renderTo.id === chartId);
            if (chart) {
                const svg = chart.getSVG({
                    exporting: {
                        sourceWidth: chart.chartWidth,
                        sourceHeight: chart.chartHeight
                    }
                });

                const chartContainer = clonedContent.querySelector(`#${chartId}`);
                if (chartContainer) {
                    chartContainer.innerHTML = `
                        <div style="width:100%; max-width:${chart.chartWidth}px;">
                            ${svg}
                        </div>
                    `;
                    const svgEl = chartContainer.querySelector('svg');
                    svgEl.setAttribute('width', '100%');
                    svgEl.setAttribute('height', 'auto');
                }
            }
        }

        const printWindow = window.open('', 'PRINT', 'height=800,width=1200');
        printWindow.document.write('<html dir="rtl"><head><title>' + document.title + '</title>');

        printWindow.document.write('<style>' +
            '@media print {' +
            '.no-print { display: none !important; }' +
            'table { border-collapse: collapse !important; width: 100%; }' +
            'th, td { border: 1px solid #cbac71 !important; padding: 0.5rem !important; }' +
            'th, td { text-align: right !important; }' +
            'th:nth-child(2), td:nth-child(2) { text-align: center !important; }' +
            'th {color: #cbac71 !important; }' +
            'svg { display: block !important; margin: 0 auto !important; max-width: 85% !important; height: auto !important; page-break-after: always; }' +
            '}' +
            '</style>'
        );

        printWindow.document.write('</head><body>');
        printWindow.document.write(clonedContent.outerHTML);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>
@endsection