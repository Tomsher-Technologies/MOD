@extends('layouts.admin_account', ['title' => __db('delegates_invitation_status')])

@section('content')
    <div class="">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-3 mt-6">
            <div class="xl:col-span-12 2xl:col-span-12">
                <div class="bg-white h-full rounded-lg border-0 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="!text-[16px] font-medium mb-0"> {{ __db('delegates_invitation_status') }}</h6>
                        <a href="{{ route('admin.dashboard') }}"
                            class="btn text-sm !bg-[#B68A35] flex items-center text-white rounded-lg py-2 px-3">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                            </svg>
                            <span>{{ __db('back') }}</span>
                        </a>
                        <button onclick="printContent()" 
                                class="btn text-sm !bg-[#4CAF50] flex items-center text-white rounded-lg py-2 px-3">
                            <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 9V2h12v7M6 18h12a2 2 0 002-2V9H4v7a2 2 0 002 2z"/>
                            </svg>
                            {{ __db('print') }}
                        </button>
                    </div>

                    <div id="printArea">
                        <div class="w-full mt-12">
                            <div id="InvitationStatus"></div>
                        </div>

                        <div class="w-full mt-6">
                            <table class="table-auto mb-0  !border-[#F9F7ED] w-full max-h-full h-[400px]">
                                <thead>
                                    <tr class="text-[13px]">
                                        <th scope="col" class="p-3 !bg-[#B68A35] text-start text-white border !border-[#cbac71]">
                                            {{ __db('department') }}
                                        </th>
                                        @foreach($data['delegatesByInvitationStatusTable']['statuses'] as $status)
                                            <th  scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                                {{ $status->value }}
                                            </th>
                                        @endforeach
                                        <th  scope="col" class="p-3 !bg-[#B68A35] text-center text-white border !border-[#cbac71]">
                                            {{ __db('total') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['delegatesByInvitationStatusTable']['departments'] as $dept)
                                        <tr class=" align-[middle] text-[12px]">
                                            <td class="px-4 py-2 border border-gray-200">{{ $dept->value }}</td>
                                            @foreach($data['delegatesByInvitationStatusTable']['statuses'] as $status)
                                                <td class="px-4 text-center py-2 border border-gray-200">
                                                    {{ $data['delegatesByInvitationStatusTable']['tableData'][$dept->id][$status->id] }}
                                                </td>
                                            @endforeach
                                            <td class="px-4 py-2 text-center border border-gray-200 text-[13px]"><strong>{{ $data['delegatesByInvitationStatusTable']['rowTotals'][$dept->id] }}</strong></td>
                                        </tr>
                                    @endforeach
                                    <tr class=" align-[middle] bg-[#FFF9E4] font-medium text-[#B68A35] text-[16px]">
                                        <th class="text-start px-4 py-2 border border-gray-200">{{ __db('total') }}</th>
                                        @foreach($data['delegatesByInvitationStatusTable']['statuses'] as $status)
                                            <th class="px-4 py-2 border border-gray-200">{{ $data['delegatesByInvitationStatusTable']['colTotals'][$status->id] }}</th>
                                        @endforeach
                                        <th class="px-4 py-2 border border-gray-200">{{ $data['delegatesByInvitationStatusTable']['grandTotal'] }}</th>
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
        window.invitationChart = Highcharts.chart('InvitationStatus', {
            chart: {
                type: 'column'
            },
            credits: { enabled: false },
            title: {
                text: '',
                align: 'left'
            },
            xAxis: {
                categories: @json($data['delegatesByInvitationStatus']['categories']),
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
                                window.location.href = '{{ route("admin.dashboard.tables",["table" => "invitations"]) }}';
                            }
                        }
                    }
                }
            },
            series: @json($data['delegatesByInvitationStatus']['series'])

        });
    });
    function printContent() {
        const chart = window.invitationChart;
        if (!chart) { window.print(); return; }

        // Use Exporting module’s API; fallback to raw SVG if needed
        const svg = (typeof chart.getSVG === 'function')
            ? chart.getSVG()
            : chart.container.querySelector('svg').outerHTML;

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        const svgBlob = new Blob([svg], { type: 'image/svg+xml;charset=utf-8' });
        const url = URL.createObjectURL(svgBlob);

        img.onload = function () {
            canvas.width = chart.chartWidth;
            canvas.height = chart.chartHeight;
            ctx.drawImage(img, 0, 0);
            URL.revokeObjectURL(url);

            const dataUrl = canvas.toDataURL('image/png');

            const chartDiv = document.getElementById('InvitationStatus');
            const original = chartDiv.innerHTML;
            chartDiv.innerHTML = `<img src="${dataUrl}" style="width:100%">`;

            window.print();

            chartDiv.innerHTML = original;
            chart.reflow(); // restore
        };

        img.src = url;
    }
</script>
@endsection