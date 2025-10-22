@extends('layouts.admin_account', ['title' => __db('arrival_status')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6" id="print_area">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-sm xl:text-xl font-bold mb-0"> {{ __db('arrival_status') }}</h4>
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

                    <div class="flex items-center justify-between mb-6">
                        <div class="w-[50%]">
                            <div id="userOverviewDonutChart" class="apexcharts-tooltip-z-none"></div>
                        </div>

                        <div class="w-[45%] ml-2">
                            @php
                                $arrivalData = $data['arrival_status'];
                                $totalCount = array_sum($arrivalData);
                            @endphp
                            <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[200px]" id="tableContainer">
                                <thead>
                                    <tr class="text-[14px]">
                                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('status') }}</th>
                                        <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">{{ __db('count') }}</th>
                                        <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">{{ __db('percentage') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($arrivalData as $status => $count)
                                        <tr class="text-[13px]">
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
            colors: ['#FFE5BA', '#C8FFD4', '#FFBAF2'],
            series: [{
                name: '{{ __db('delegates') }}',
                data: [
                    { name: '{{ __db('to_be_arrived') }}', y: {{ $data['arrival_status']['to_be_arrived'] }} },
                    { name: '{{ __db('arrived') }}', y: {{ $data['arrival_status']['arrived'] }} },
                    { name: '{{ __db('departed') }}', y: {{ $data['arrival_status']['departed'] }} }
                ]
            }]
        });
    });

    function printSection(divId, chartId = null) {
        const contentDiv = document.getElementById(divId);
        const clonedContent = contentDiv.cloneNode(true);

        if (chartId) {
            const chart = Highcharts.charts.find(c => c && c.renderTo.id === chartId);
            if(chart && typeof chart.getSVG === 'function'){
                const svg = chart.getSVG({
                    exporting: {
                        sourceWidth: chart.chartWidth,
                        sourceHeight: chart.chartHeight
                    }
                });

                const chartContainer = clonedContent.querySelector(`#${chartId}`);
                if(chartContainer){
                    chartContainer.innerHTML = svg;
                }
            } else {
                console.error('Chart not found or not fully rendered yet.');
            }
        }

        const printWindow = window.open('', 'PRINT', 'height=800,width=1500');
        printWindow.document.write('<html dir="rtl"><head><title>&nbsp;</title>');
        printWindow.document.write(`
        <style>
        @media print {
            .no-print { display: none !important; }

            table { border-collapse: collapse !important; width: 100%; page-break-inside: avoid; }
            th, td { border: 1px solid #cbac71 !important; padding: 0.5rem !important; text-align: right !important; }
            th:nth-child(2), td:nth-child(2) { text-align: center !important; }
            th:nth-child(3), td:nth-child(3) { text-align: center !important; }
            th { color: #cbac71 !important; }

            .page-break { page-break-before: always; }

            svg { display: block !important; margin: 0 auto !important; width: 100% !important; height: auto !important; }

            /* Ensure chart page fills the printable page */
            @page { size: auto; margin: 10mm; }

            
        }

        .chart-container {
            display: flex;
            justify-content: center; /* horizontal centering */
            align-items: center;     /* vertical centering */
            height: calc(100vh - 50px); /* adjust for margins */
            width: 100%;
            box-sizing: border-box; /* include padding/margins in width */
            padding: 0 10mm; /* give some horizontal padding so SVG doesn't touch edges */
        }

        .chart-container #userOverviewDonutChart,
        .chart-container #userOverviewDonutChart svg {
            display: block !important;
            margin: 0 auto !important;
            max-width: 100% !important; /* make sure it does not exceed container */
            width: auto !important;      /* let Highcharts control width if needed */
            height: auto !important;
        }
        .table-heading { font-size: 22px; font-weight: bold; text-align: right; margin-bottom: 10px; }
        </style>
        `);

        printWindow.document.write('</head><body>');

        const titleTable = "{{ __db('arrival_status') }}";
        // 1️⃣ Chart page
        printWindow.document.write(`
        <div class="chart-section">
            <div class="table-heading ">${titleTable}</div>
            <div class="chart-container">
                ${clonedContent.querySelector('#userOverviewDonutChart')?.outerHTML || ''}
            </div>
        </div>
        `);

        // 2️⃣ Table page
        
        const tableHTML = clonedContent.querySelector('#tableContainer')?.outerHTML || '';
        printWindow.document.write(`
        <div class="page-break">
            <div class="table-heading text-left">${titleTable}</div>
            ${tableHTML}
        </div>
        `);

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