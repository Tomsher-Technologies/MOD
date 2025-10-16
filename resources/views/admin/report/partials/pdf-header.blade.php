<div style=" border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px;">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <!-- Left Logo -->
            <td style="width: 25%; text-align: left; vertical-align: middle;">
                <img src="{{ public_path('assets/img/md-logo.svg') }}" 
                    alt="{{ env('APP_NAME') }}" 
                    style="width: 150px; height: auto;">
            </td>

            <!-- Center Text -->
            <td style="width: 50%; text-align: center; vertical-align: middle; line-height: 1.8;">
                <div style="font-size: 14px; font-weight: bold;">
                    {{ __db('united_arab_emirates') }}
                </div>
                <div style="font-size: 14px; font-weight: bold;">
                    {{ __db('ministry_of_defense') }}
                </div>
                <div style="font-size: 14px; font-weight: bold; color: #cc0000;">
                    {!! __db($reportName) ?? $reportName !!}
                </div>

                @if(isset($internalRankName))
                    <div style="font-size: 14px; font-weight: bold;">
                        {!! $internalRankName !!}
                    </div>
                @endif
                
            </td>

            <!-- Right Logo -->
            <td style="width: 25%; text-align: right; vertical-align: middle;">
                <img src="{{ asset(getAdminEventPDFLogo()) }}" 
                    alt="{{ getCurrentEventName() }}" 
                    style="width: 150px; height: auto;">
            </td>
        </tr>
    </table>

    <div style="text-align: right; font-size: 0.9em; margin-top:5px; margin-bottom:5px;">{{ date('d-m-Y H:i A') }}</div>

</div>