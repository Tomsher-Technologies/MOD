<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delegation;
use App\Models\Delegate;
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
use DB;
use Hash;
use Validator;

class ReportController extends Controller
{
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

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('delegates', function ($delegateQuery) use ($search) {
                        $delegateQuery->where(function ($dq) use ($search) {
                            $dq->where('name_en', 'like', "%{$search}%")
                                ->orWhere('title_en', 'like', "%{$search}%")
                                ->orWhere('title_ar', 'like', "%{$search}%");
                        });
                    })
                    ->orWhereHas('escorts', function ($escortQuery) use ($search) {
                        $escortQuery->where(function ($eq) use ($search) {
                            $eq->where('name_en', 'like', "%{$search}%")
                                ->orWhere('name_ar', 'like', "%{$search}%")
                                ->orWhere('title_en', 'like', "%{$search}%")
                                ->orWhere('title_ar', 'like', "%{$search}%");
                        });
                    })

                    ->orWhereHas('drivers', function ($driverQuery) use ($search) {
                        $driverQuery->where(function ($eq) use ($search) {
                            $eq->where('name_en', 'like', "%{$search}%")
                                ->orWhere('name_ar', 'like', "%{$search}%")
                                ->orWhere('title_en', 'like', "%{$search}%")
                                ->orWhere('title_ar', 'like', "%{$search}%");
                        });
                    });
            });
        }



        if ($invitationFrom = $request->input('invitation_from')) {
            if (is_array($invitationFrom)) {
                $query->whereIn('invitation_from_id', $invitationFrom);
            } else {
                $query->where('invitation_from_id', $invitationFrom);
            }
        }

        if ($continentId = $request->input('continent_id')) {
            if (is_array($continentId)) {
                $query->whereIn('continent_id', $continentId);
            } else {
                $query->where('continent_id', $continentId);
            }
        }

        if ($countryId = $request->input('country_id')) {
            if (is_array($countryId)) {
                $query->whereIn('country_id', $countryId);
            } else {
                $query->where('country_id', $countryId);
            }
        }

        if ($invitationStatusId = $request->input('invitation_status_id')) {
            if (is_array($invitationStatusId)) {
                $query->whereIn('invitation_status_id', $invitationStatusId);
            } else {
                $query->where('invitation_status_id', $invitationStatusId);
            }
        }

        if ($participationStatusId = $request->input('participation_status_id')) {
            if (is_array($participationStatusId)) {
                $query->whereIn('participation_status_id', $participationStatusId);
            } else {
                $query->where('participation_status_id', $participationStatusId);
            }
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

        $pdf = Pdf::loadView('admin.report.delegation-escorts', compact('delegation'))
            ->setPaper('A4', 'portrait');


        return $pdf->download("delegation-report-{$delegation->id}.pdf");
    }
}
