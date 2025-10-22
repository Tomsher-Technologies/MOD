@extends('layouts.admin_account', ['title' => __db('delegations_by_division')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6 w-full" id="print_area">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-sm xl:text-xl font-bold mb-0"> {{ __db('delegations_by_division') }}</h4>

                        <div class="flex items-center gap-2 no-print">
                            <button onclick="printSection('print_area','pieChart')"  class=" no-print btn text-sm !bg-[#5c451d] flex items-center text-white rounded-lg py-2.5 px-3">
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

                    <div class="overflow-x-auto w-[100%] mb-6">
                        <div id="pieChart" ></div>   
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[400px]" id="tableContainer">
                            <thead>
                                <tr class="text-[14px]">
                                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('department') }}</th>
                                    <th scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">{{ __db('count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                                @foreach($delegatesByDivision as $division)
                                    @php $grandTotal += $division->total; @endphp
                                    <tr class=" text-[13px] align-[middle]">
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
                '#06b6d482','#7fffd4','#deb887','#a9a9a9','#bdb76b', '#d2a3ff','#f18989','#90f790','#f7d28e','#f3acf3','#f7f779','#f96565','#a6a6ee','#daa9b2','#b8860b','#9acd32','#059ab3', '#902fec','#ff0000','#008000','#ffa500','#ff00ff','#caca03','#a52a2a', '#5f5ff3','#e36179',
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

       let pieChart =  Highcharts.chart('pieChart', {
            chart: { type: 'pie', height: 400 },
            credits: { enabled: false },
            title: { text: null },
            responsive: {
                rules: [{
                    condition: { maxWidth: 1000 },
                    chartOptions: {
                        chart: { width: null }
                    }
                }]
            },
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

        const insertChartAsPNG = (callback) => {
            if (chartId) {
                const chart = Highcharts.charts.find(c => c && c.renderTo.id === chartId);
                if (chart) {
                    const svgData = chart.getSVG({
                        exporting: {
                            sourceWidth: chart.chartWidth,
                            sourceHeight: chart.chartHeight,
                            chartOptions: {
                                chart: { backgroundColor: '#ffffff', style: { fontFamily: 'Arial, sans-serif' } },
                                legend: {
                                    useHTML: false,
                                    align: 'center',
                                    verticalAlign: 'bottom',
                                    layout: 'horizontal',
                                    itemStyle: { color: '#333', fontSize: '12px' }
                                },
                                plotOptions: {
                                    series: { colorByPoint: true }
                                }
                            }
                        }
                    });

                    // Convert SVG to PNG
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const img = new Image();

                    img.onload = () => {
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                        const pngDataUrl = canvas.toDataURL('image/png');

                        const chartContainer = clonedContent.querySelector('#pieChart');
                        if (chartContainer) {
                            chartContainer.innerHTML = `<img src="${pngDataUrl}" style="width:100%; height:auto;">`;
                        }

                        callback(); // proceed to print after PNG is ready
                    };

                    img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
                } else {
                    console.error('Chart not found or not fully rendered yet.');
                    callback();
                }
            } else {
                callback();
            }
        };

        insertChartAsPNG(() => {
            const printWindow = window.open('', 'PRINT', 'height=800,width=1500');
            printWindow.document.write('<html dir="rtl"><head><title> </title>');
            printWindow.document.write(`
            <style>
                @media print {
                    .no-print { display: none !important; }
                    table { border-collapse: collapse !important; width: 100%; page-break-inside: avoid; }
                    th, td { border: 1px solid #cbac71 !important; padding: 0.5rem !important; text-align: right !important; }
                    th:nth-child(2), td:nth-child(2), th:nth-child(3), td:nth-child(3) { text-align: center !important; }
                    th { color: #cbac71 !important; }
                    .page-break { page-break-before: always; }
                    svg, img { display: block !important; margin: 0 auto !important; width: 100% !important; height: auto !important; }
                    @page { size: auto; margin: 10mm; }
                }
                .chart-container {
                    display: flex;
                    justify-content: center; /* horizontal centering */
                    align-items: center;     /* vertical centering */
                    height: calc(100vh - 20mm); /* subtract @page margin top + bottom */
                    page-break-after: always; /* force table on next page */
                }

                .chart-container img {
                    max-width: 90%;
                    height: auto;
                    display: block;
                    margin: 0 auto;
                }

                .table-heading { font-size: 22px; font-weight: bold; text-align: right; margin-bottom: 10px; }
            </style>
            `);
            printWindow.document.write('</head><body>');

            const titleTable = "{{ __db('delegations_by_division') }}";

            // Chart section
            printWindow.document.write(`
            <div class="chart-section">
                <div class="table-heading">${titleTable}</div>
                <div class="chart-container">
                    ${clonedContent.querySelector('#pieChart')?.outerHTML || ''}
                </div>
            </div>
            `);

            // Table section
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
        });
    }
</script>
@endsection