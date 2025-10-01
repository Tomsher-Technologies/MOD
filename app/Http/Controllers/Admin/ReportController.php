<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delegation;
use App\Models\Delegate;
use App\Models\Escort;
use App\Models\Driver;
use App\Models\Interview;
use App\Models\DelegationEscort;
use App\Models\DelegationDriver;
use App\Models\DelegateTransport;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\AccommodationContact;
use App\Models\RoomAssignment;
use App\Models\ExternalMemberAssignment;
use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
use DB;
use Hash;
use Validator;

class ReportController extends Controller
{
    const UNASSIGNABLE_STATUS_CODES = [3, 9];
    const ASSIGNABLE_STATUS_CODES = [2, 10];
    public function index()
    {
        return view('admin.report.index');
    }

    public function reportsDelegations(Request $request)
    {
        $query = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates',
            'escorts',
            'drivers'
        ])->orderBy('id', 'desc');

        $currentEventId = session('current_event_id', getDefaultEventId());
        $query->where('event_id', $currentEventId);

        $query->leftJoin('countries as country_sort', 'delegations.country_id', '=', 'country_sort.id')
            ->leftJoin('dropdown_options as invitation_from_sort', 'delegations.invitation_from_id', '=', 'invitation_from_sort.id')
            ->leftJoin('dropdown_options as participation_status_sort', 'delegations.participation_status_id', '=', 'participation_status_sort.id')
            ->orderBy('country_sort.sort_order', 'asc')
            ->orderBy('invitation_from_sort.sort_order', 'asc')
            ->orderBy('participation_status_sort.sort_order', 'asc')
            ->select('delegations.*');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('delegations.code', 'like', "%{$search}%")

                    ->orWhereHas('country', function ($countryQuery) use ($search) {
                        $countryQuery->where('countries.name', 'like', "%{$search}%")
                            ->orWhere('countries.name_ar', 'like', "%{$search}%")
                            ->orWhere('countries.short_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('continent', function ($continentQuery) use ($search) {
                        $continentQuery->where('dropdown_options.value', 'like', "%{$search}%")
                            ->orWhere('dropdown_options.value_ar', 'like', "%{$search}%");
                    })
                    ->orWhereHas('invitationFrom', function ($invitationQuery) use ($search) {
                        $invitationQuery->where('dropdown_options.value', 'like', "%{$search}%")
                            ->orWhere('dropdown_options.value_ar', 'like', "%{$search}%");
                    })
                    ->orWhereHas('invitationStatus', function ($statusQuery) use ($search) {
                        $statusQuery->where('dropdown_options.value', 'like', "%{$search}%")
                            ->orWhere('dropdown_options.value_ar', 'like', "%{$search}%");
                    })
                    ->orWhereHas('participationStatus', function ($participationQuery) use ($search) {
                        $participationQuery->where('dropdown_options.value', 'like', "%{$search}%")
                            ->orWhere('dropdown_options.value_ar', 'like', "%{$search}%");
                    })

                    ->orWhereHas('delegates', function ($delegateQuery) use ($search) {
                        $delegateQuery->where(function ($dq) use ($search) {
                            $dq->where('delegates.name_en', 'like', "%{$search}%")
                                ->orWhere('delegates.name_ar', 'like', "%{$search}%")
                                ->orWhere('delegates.title_en', 'like', "%{$search}%")
                                ->orWhere('delegates.title_ar', 'like', "%{$search}%")
                                ->orWhere('delegates.code', 'like', "%{$search}%")
                                ->orWhere('delegates.designation_en', 'like', "%{$search}%")
                                ->orWhere('delegates.designation_ar', 'like', "%{$search}%");
                        });
                    })

                    ->orWhereHas('escorts', function ($escortQuery) use ($search) {
                        $escortQuery->where(function ($eq) use ($search) {
                            $eq->where('escorts.name_en', 'like', "%{$search}%")
                                ->orWhere('escorts.name_ar', 'like', "%{$search}%")
                                ->orWhere('escorts.title_en', 'like', "%{$search}%")
                                ->orWhere('escorts.title_ar', 'like', "%{$search}%")
                                ->orWhere('escorts.code', 'like', "%{$search}%")
                                ->orWhere('escorts.military_number', 'like', "%{$search}%")
                                ->orWhere('escorts.rank', 'like', "%{$search}%")
                                ->orWhere('escorts.phone_number', 'like', "%{$search}%")
                                ->orWhere('escorts.email', 'like', "%{$search}%");
                        });
                    })

                    ->orWhereHas('drivers', function ($driverQuery) use ($search) {
                        $driverQuery->where(function ($eq) use ($search) {
                            $eq->where('drivers.name_en', 'like', "%{$search}%")
                                ->orWhere('drivers.name_ar', 'like', "%{$search}%")
                                ->orWhere('drivers.title_en', 'like', "%{$search}%")
                                ->orWhere('drivers.title_ar', 'like', "%{$search}%")
                                ->orWhere('drivers.code', 'like', "%{$search}%")
                                ->orWhere('drivers.military_number', 'like', "%{$search}%")
                                ->orWhere('drivers.phone_number', 'like', "%{$search}%")
                                ->orWhere('drivers.car_type', 'like', "%{$search}%")
                                ->orWhere('drivers.car_number', 'like', "%{$search}%");
                        });
                    });
            });
        }

        if ($invitationFrom = $request->input('invitation_from')) {
            $query->whereIn('delegations.invitation_from_id', $invitationFrom);
        }

        if ($continentId = $request->input('continent_id')) {
            $query->whereIn('delegations.continent_id', $continentId);
        }

        if ($countryId = $request->input('country_id')) {
            $query->whereIn('delegations.country_id', $countryId);
        }

        if ($invitationStatusId = $request->input('invitation_status_id')) {
            $query->whereIn('delegations.invitation_status_id', $invitationStatusId);
        }

        if ($participationStatusId = $request->input('participation_status_id')) {
            $query->whereIn('delegations.participation_status_id', $participationStatusId);
        }

        $limit = $request->limit ? $request->limit : 20;

        $delegations = $query->paginate($limit);

        return view('admin.report.delegations', compact('delegations'));
    }

    public function showReportsDelegations($id)
    {
        $id = base64_decode($id);
        $delegation = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates' => function ($query) {
                $query->with([
                    'gender',
                    'parent',
                    'delegateTransports',
                ]);
            },
            'attachments',
            'escorts',
            'drivers'
        ])->findOrFail($id);

        $interviews = Interview::with(['interviewMembers', 'interviewMembers.fromDelegate', 'interviewMembers.toDelegate', 'interviewWithDelegation'])
            ->where('delegation_id', $id)
            ->get();

        return view('admin.report.delegations-show', compact('delegation', 'interviews'));
    }

    public function exportReportDelegationPdf($id)
    {
        $id = base64_decode($id);

        $delegation = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates.delegateTransports',
            'escorts.currentRoomAssignment.hotel',
            'drivers',
            'interviews.interviewMembers'
        ])->findOrFail($id);

        // $pdf = Pdf::loadView('admin.report.delegation-escorts', compact('delegation'))
        //             ->setPaper('A4', 'portrait')
        //             ->setOptions([
        //                 'isHtml5ParserEnabled' => true,
        //                 'isRemoteEnabled' => true,
        //             ]);


        // return $pdf->download("delegation-report-{$delegation->id}.pdf");

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_top' => 15,
            'margin_bottom' => 20,
            'default_font' => 'amiri'
        ]);

        $mpdf->SetHTMLFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $html = view('admin.report.delegation-escorts', compact('delegation'))->render();
        $mpdf->WriteHTML($html);

        $mpdf->SetFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $mpdf->Output("delegation-report-{$delegation->id}.pdf", 'D');
    }

    public function exportBulkReportDelegationPdf(Request $request)
    {
        $delegationIds = $request->input('export_pdf') ? json_decode($request->input('export_pdf')) : [];

        // echo '<pre>';
        // print_r($delegationIds);
        
        $currentEventId = session('current_event_id', getDefaultEventId());

        $delegations = Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates.delegateTransports',
            'escorts.currentRoomAssignment.hotel',
            'drivers',
            'interviews.interviewMembers'
        ])->whereIN('id', $delegationIds)->where('event_id', $currentEventId)->get();

        // print_r($delegation);
        // die;
      
        $today = date('Y-m-d-H-i');
        $reportName = 'delegations_escort_report';
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            // 'format' => 'A4',
            'format' => 'A4-L',
            'margin_top' => 40,
            'margin_bottom' => 20,
            'default_font' => 'amiri'
        ]);

        $headerHtml = view('admin.report.partials.pdf-header', compact('reportName'))->render();
        $mpdf->SetHTMLHeader($headerHtml);

        $mpdf->SetHTMLFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $html = view('admin.report.delegation-escorts-bulk', compact('delegations'))->render();

        $reportName = 'delegations_escort_report'.$today.'.pdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output($reportName, 'D');
    }

    public function hotelRooms(Request $request)
    {
        $lang = app()->getLocale() ?? 'en';
        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);

        $accommodations = Accommodation::with(['rooms', 'contacts'])->where('event_id', $currentEventId);

        if (request()->has('search')) {
            $accommodations->where(function ($query) {
                $query->where('hotel_name', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('hotel_name_ar', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('address', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('contact_number', 'like', '%' . request()->input('search') . '%');
            });
        }

        if($lang === 'ar') {
            $accommodations->orderBy('hotel_name_ar', 'asc');
        }else {
            $accommodations->orderBy('hotel_name', 'asc');
        }
        $accommodations = $accommodations->get();

        return view('admin.report.hotel-rooms', compact('accommodations'));
    }

    public function exportBulkHotelRoomPdf(Request $request)
    {
        $lang = getActiveLanguage();
        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);

        $accommodations = Accommodation::with(['rooms', 'contacts'])->where('event_id', $currentEventId);

        if (request()->has('search')) {
            $accommodations->where(function ($query) {
                $query->where('hotel_name', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('hotel_name_ar', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('address', 'like', '%' . request()->input('search') . '%')
                    ->orWhere('contact_number', 'like', '%' . request()->input('search') . '%');
            });
        }

        if($lang === 'ar') {
            $accommodations->orderBy('hotel_name_ar', 'asc');
        }else {
            $accommodations->orderBy('hotel_name', 'asc');
        }
        $accommodations = $accommodations->get();
      
        
        $today = date('Y-m-d-H-i');
        $reportName = 'hotel_rooms_vacancies_report';
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            // 'format' => 'A4',
            'format' => 'A4-L',
            'margin_top' => 40,
            'margin_bottom' => 20,
            'default_font' => 'amiri'
        ]);

        $headerHtml = view('admin.report.partials.pdf-header', compact('reportName'))->render();
        $mpdf->SetHTMLHeader($headerHtml);

        $mpdf->SetHTMLFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $html = view('admin.report.hotel-rooms-bulk', compact('accommodations'))->render();

        $mpdf->WriteHTML($html);
        $reportName = 'hotel_rooms_vacancies_report'.$today.'.pdf';
        $mpdf->Output($reportName, 'D');
    }

    public function escorts(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

        $baseQuery = Escort::with(['delegations', 'gender', 'nationality', 'delegation'])
            ->select('escorts.*')
            ->where('escorts.event_id', $currentEventId)
            ->where('escorts.status', 1)
            ->leftJoin('dropdown_options as rankings', 'escorts.internal_ranking_id', '=', 'rankings.id');

        $assignedEscorts = (clone $baseQuery)
            ->whereNotNull('escorts.delegation_id')
            ->orderBy('escorts.military_number')
            ->orderBy('rankings.sort_order')
            ->get();

        $unassignedEscorts = (clone $baseQuery)
            ->whereNull('escorts.delegation_id')
            ->orderBy('escorts.military_number')
            ->orderBy('rankings.sort_order')
            ->get();


        return view('admin.report.escorts', compact('assignedEscorts', 'unassignedEscorts'));
    }

    public function exportBulkEscortsPdf(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

        $baseQuery = Escort::with(['delegations', 'gender', 'nationality', 'delegation'])
            ->select('escorts.*')
            ->where('escorts.event_id', $currentEventId)
            ->where('escorts.status', 1)
            ->leftJoin('dropdown_options as rankings', 'escorts.internal_ranking_id', '=', 'rankings.id');

        $assignedEscorts = (clone $baseQuery)
            ->whereNotNull('escorts.delegation_id')
            ->orderBy('escorts.military_number')
            ->orderBy('rankings.sort_order')
            ->get();

        $unassignedEscorts = (clone $baseQuery)
            ->whereNull('escorts.delegation_id')
            ->orderBy('escorts.military_number')
            ->orderBy('rankings.sort_order')
            ->get();


        $today = date('Y-m-d-H-i');
        $reportName = 'escorts_report';
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            // 'format' => 'A4',
            'format' => 'A4-L',
            'margin_top' => 40,
            'margin_bottom' => 20,
            'default_font' => 'amiri'
        ]);

        $headerHtml = view('admin.report.partials.pdf-header', compact('reportName'))->render();
        $mpdf->SetHTMLHeader($headerHtml);

        $mpdf->SetHTMLFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $html = view('admin.report.escorts-bulk', compact('assignedEscorts', 'unassignedEscorts'))->render();

        $mpdf->WriteHTML($html);
        $reportName = 'escorts_report'.$today.'.pdf';
        $mpdf->Output($reportName, 'D');
    }

    public function drivers(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

        $assignedDrivers = Driver::where('event_id', $currentEventId)
                                ->where('status', 1)
                                ->whereHas('delegationDrivers', function ($q) {
                                    $q->where('status', 1);
                                })
                                ->orderBy('military_number', 'asc')
                                ->get();
              
       $unassignedDrivers = Driver::where('event_id', $currentEventId)
                                    ->where('status', 1)
                                    ->whereDoesntHave('delegationDrivers', function ($q) {
                                        $q->where('status', 1);
                                    })
                                    ->orderBy('military_number', 'asc')
                                    ->get();

        return view('admin.report.drivers', compact('assignedDrivers', 'unassignedDrivers'));
    }

    public function exportBulkDriversPdf(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

        $assignedDrivers = Driver::where('event_id', $currentEventId)
                                ->where('status', 1)
                                ->whereHas('delegationDrivers', function ($q) {
                                    $q->where('status', 1);
                                })
                                ->orderBy('military_number', 'asc')
                                ->get();
              
        $unassignedDrivers = Driver::where('event_id', $currentEventId)
                                    ->where('status', 1)
                                    ->whereDoesntHave('delegationDrivers', function ($q) {
                                        $q->where('status', 1);
                                    })
                                    ->orderBy('military_number', 'asc')
                                    ->get();

        $today = date('Y-m-d-H-i');
        $reportName = 'drivers_report';
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            // 'format' => 'A4',
            'format' => 'A4-L',
            'margin_top' => 40,
            'margin_bottom' => 20,
            'default_font' => 'amiri'
        ]);

        $headerHtml = view('admin.report.partials.pdf-header', compact('reportName'))->render();
        $mpdf->SetHTMLHeader($headerHtml);

        $mpdf->SetHTMLFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $html = view('admin.report.drivers-bulk', compact('assignedDrivers', 'unassignedDrivers'))->render();

        $mpdf->WriteHTML($html);
        $reportName = 'drivers_report'.$today.'.pdf';
        $mpdf->Output($reportName, 'D');
    }

    public function delegationHeadsArrivals(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

        $headsArrivals = DelegateTransport::where('type', 'arrival')
                        ->with([
                            'delegate.delegation.country',
                            'delegate.delegation.continent',
                            'delegate.delegation.escorts',
                            'delegate.delegation.drivers',
                            'airport',
                            'delegate.delegation.invitationFrom',
                        ])
                        ->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                            $delegationQuery->where('event_id', $currentEventId)
                                ->whereHas('invitationStatus', function ($q) {
                                    $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
                                });
                        })
                        ->whereHas('delegate', function ($delegateQuery) {
                            $delegateQuery->where('team_head', 1);
                        })
                        ->orderBy('date_time')
                        ->get();

        return view('admin.report.head_arrivals', compact('headsArrivals'));
    }

    public function exportBulkDelegationHeadsArrivalsPdf(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

         $currentEventId = session('current_event_id', getDefaultEventId());

        $headsArrivals = DelegateTransport::where('type', 'arrival')
                        ->with([
                            'delegate.delegation.country',
                            'delegate.delegation.continent',
                            'delegate.delegation.escorts',
                            'delegate.delegation.drivers',
                            'airport',
                            'delegate.delegation.invitationFrom',
                        ])
                        ->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                            $delegationQuery->where('event_id', $currentEventId)
                                ->whereHas('invitationStatus', function ($q) {
                                    $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
                                });
                        })
                        ->whereHas('delegate', function ($delegateQuery) {
                            $delegateQuery->where('team_head', 1);
                        })
                        ->orderBy('date_time')
                        ->get();

        $today = date('Y-m-d-H-i');
        $reportName = 'delegation_heads_arrivals_report';
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            // 'format' => 'A4',
            'format' => 'A4-L',
            'margin_top' => 40,
            'margin_bottom' => 20,
            'default_font' => 'amiri'
        ]);

        $headerHtml = view('admin.report.partials.pdf-header', compact('reportName'))->render();
        $mpdf->SetHTMLHeader($headerHtml);

        $mpdf->SetHTMLFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $html = view('admin.report.head_arrivals_bulk', compact('headsArrivals'))->render();

        $mpdf->WriteHTML($html);
        $reportName = 'delegation_heads_arrivals_report'.$today.'.pdf';
        $mpdf->Output($reportName, 'D');
    }

    public function delegationHeadsDeparture(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

        $headsDeparture = DelegateTransport::where('type', 'departure')
                        ->with([
                            'delegate.delegation.country',
                            'delegate.delegation.continent',
                            'delegate.delegation.escorts',
                            'delegate.delegation.drivers',
                            'airport',
                            'delegate.delegation.invitationFrom',
                        ])
                        ->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                            $delegationQuery->where('event_id', $currentEventId)
                                ->whereHas('invitationStatus', function ($q) {
                                    $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
                                });
                        })
                        ->whereHas('delegate', function ($delegateQuery) {
                            $delegateQuery->where('team_head', 1);
                        })
                        ->orderBy('date_time')
                        ->get();

        return view('admin.report.head_departure', compact('headsDeparture'));
    }

    public function exportBulkDelegationHeadsDeparturePdf(Request $request){
        $currentEventId = session('current_event_id', getDefaultEventId());

        $headsDeparture = DelegateTransport::where('type', 'departure')
                        ->with([
                            'delegate.delegation.country',
                            'delegate.delegation.continent',
                            'delegate.delegation.escorts',
                            'delegate.delegation.drivers',
                            'airport',
                            'delegate.delegation.invitationFrom',
                        ])
                        ->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                            $delegationQuery->where('event_id', $currentEventId)
                                ->whereHas('invitationStatus', function ($q) {
                                    $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
                                });
                        })
                        ->whereHas('delegate', function ($delegateQuery) {
                            $delegateQuery->where('team_head', 1);
                        })
                        ->orderBy('date_time')
                        ->get();

        $today = date('Y-m-d-H-i');
        $reportName = 'delegations_heads_departure';
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            // 'format' => 'A4',
            'format' => 'A4-L',
            'margin_top' => 40,
            'margin_bottom' => 20,
            'default_font' => 'amiri'
        ]);

        $headerHtml = view('admin.report.partials.pdf-header', compact('reportName'))->render();
        $mpdf->SetHTMLHeader($headerHtml);

        $mpdf->SetHTMLFooter('<div style="padding-top:5px;text-align:center;font-size:10px">'.__db('page').' {PAGENO} '.__db('of').' {nb}</div>');

        $html = view('admin.report.head_departure_bulk', compact('headsDeparture'))->render();

        $mpdf->WriteHTML($html);
        $reportName = 'delegations_heads_departure'.$today.'.pdf';
        $mpdf->Output($reportName, 'D');
    }
}
