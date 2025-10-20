@extends('layouts.admin_account', ['title' => __db('delegates_by_participation_status')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6" id="print_area">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="!text-[20px] font-medium mb-0"> {{ __db('delegates_by_participation_status') }}</h4>
                        <div class="flex items-center gap-2">
                            <button onclick="printSection('print_area','ParticipationStatus')"  class=" no-print btn text-sm !bg-[#5c451d] flex items-center text-white rounded-lg py-2.5 px-3">
                                <svg class="ml-1 w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2m-12 0h12v3H6v-3Z" />
                                </svg>

                                {{ __db('print') }}
                            </button>

                            <a href="{{ route('admin.dashboard') }}"
                                class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3 no-print">
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                                </svg>
                                <span>{{ __db('back') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="w-[100%] mt-12">
                        <div id="ParticipationStatus"></div>
                    </div>

                    <div class="w-full mt-6">
                       <table class="table-auto mb-0  !border-[#F9F7ED] w-full h-[400px]" id="tableContainer">
                            <thead>
                                <tr class="text-[13px]">
                                    <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">{{ __db('department') }}</th>
                                    @foreach($data['delegatesByParticipationTable']['statuses'] as $status)
                                        <th scope="col" class=" text-center p-3 !bg-[#B68A35] text-white border !border-[#cbac71]">{{ __db($status) }}</th>
                                    @endforeach
                                    <th scope="col" class="p-3 !bg-[#B68A35]  text-center text-white border !border-[#cbac71]">{{ __db('total') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($data['delegatesByParticipationTable']['departments'] as $dept)
                                    <tr class="align-[middle] text-[12px]">
                                        <td class="px-4 py-2 border border-gray-200">{{ $dept->value }}</td>
                                        @foreach($data['delegatesByParticipationTable']['statuses'] as $status)
                                            <td class="px-4 py-2 text-center border border-gray-200">
                                                {{ $data['delegatesByParticipationTable']['tableData'][$dept->id][$status] ?? 0 }}
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-2 text-center border border-gray-200"><strong>{{ $data['delegatesByParticipationTable']['rowTotals'][$dept->id] }}</strong></td>
                                    </tr>
                                @endforeach
                                <tr class="  align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[16px]">
                                    <th class="text-start px-4 py-2 border border-gray-200">{{ __db('total') }}</th>
                                    @foreach($data['delegatesByParticipationTable']['statuses'] as $status)
                                        <th class="px-4 py-2 border border-gray-200 ">{{ $data['delegatesByParticipationTable']['colTotals'][$status] }}</th>
                                    @endforeach
                                    <th class="px-4 py-2 border border-gray-200">{{ $data['delegatesByParticipationTable']['grandTotal'] }}</th>
                                </tr>
                            </tbody>
                           
                        </table>
                        
                    </div>
                </div>
            </div> 
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        Highcharts.chart('ParticipationStatus', {
            chart: {
                type: 'column'
            },
            credits: { enabled: false },
            title: {
                text: '',
                align: 'left'
            },
            xAxis: {
                categories: @json($data['delegatesByParticipationStatus']['categories']),
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true
                }
            },
            legend: {
                align: 'left',
                x: 0,
                verticalAlign: 'bottom',
                y: 10,
                floating: false,
                backgroundColor: 'var(--highcharts-background-color, #ffffff)',
                borderColor: 'var(--highcharts-neutral-color-20, #cccccc)',
                borderWidth: 0,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{category}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                },
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function () {
                                window.location.href = '{{ route("admin.dashboard.tables",["table" => "participations"]) }}';
                            }
                        }
                    }
                }
            },
            series: @json($data['delegatesByParticipationStatus']['series'])


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

                        const chartContainer = clonedContent.querySelector('#ParticipationStatus');
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

            const titleTable = "{{ __db('delegates_by_participation_status') }}";

            // Chart section
            printWindow.document.write(`
            <div class="chart-section">
                <div class="table-heading">${titleTable}</div>
                <div class="chart-container">
                    ${clonedContent.querySelector('#ParticipationStatus')?.outerHTML || ''}
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