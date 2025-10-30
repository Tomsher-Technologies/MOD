<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Delegation;
use App\Models\DropdownOption;
use App\Models\AccommodationRoom;
use App\Models\AccommodationContact;
use App\Models\DelegationAttachment;
use App\Models\Delegate;
use App\Models\DelegationEscort;
use App\Models\DelegationDriver;
use App\Models\DelegateTransport;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Hash;
use Auth;

class AdminDashboardController extends Controller
{

    const ASSIGNABLE_STATUS_CODES = [2, 10];

    public function dashboard(Request $request )
    {

        $lang = app()->getLocale() ?? 'en';
        $currentEventId = session('current_event_id', getDefaultEventId());

        $invitationStatusIds = DropdownOption::whereHas('dropdown', function ($query) {
            $query->where('code', 'invitation_status');
        })->whereIn('code', self::ASSIGNABLE_STATUS_CODES)->pluck('id')->toArray();

        $totalDelegates = Delegate::whereHas('delegation', function ($q) use ($currentEventId, $invitationStatusIds) {
            $q->where('event_id', $currentEventId)
                ->whereIn('invitation_status_id', $invitationStatusIds);
        })->count();

        $totalEscortsAssigned = DelegationEscort::where('status', 1)
            ->whereHas('delegation', function ($q) use ($currentEventId) {
                $q->where('event_id', $currentEventId);
            })
            ->distinct('escort_id')
            ->count('escort_id');

        $totalDriversAssigned = DelegationDriver::where('status', 1)
            ->whereHas('delegation', function ($q) use ($currentEventId) {
                $q->where('event_id', $currentEventId);
            })
            ->distinct('driver_id')
            ->count('driver_id');

        $totalHotels = Accommodation::where('status', 1)
            ->where('event_id', $currentEventId)
            ->count();

        // Delegates by division
        $delegatesByDivision =  DropdownOption::leftJoin('delegations', function ($join) use ($currentEventId) {
            $join->on('delegations.invitation_from_id', '=', 'dropdown_options.id')
                ->where('delegations.event_id', $currentEventId);
        })
            // ->leftJoin('delegates', 'delegates.delegation_id', '=', 'delegations.id')
            ->join('dropdowns', 'dropdowns.id', '=', 'dropdown_options.dropdown_id')
            ->where('dropdowns.code', 'departments')
            ->where('dropdown_options.status', 1)
            ->groupBy('dropdown_options.id', 'dropdown_options.value', 'dropdown_options.sort_order')
            ->orderBy('dropdown_options.sort_order')
            ->select(DB::raw($lang === 'ar' ? 'COALESCE(dropdown_options.value_ar, dropdown_options.value) as department_name' : 'dropdown_options.value as department_name'), DB::raw('COUNT(delegations.id) as total'))
            ->get();

        $seriesDelegatesByDivision = $delegatesByDivision->pluck('total')->toArray();
        $labelsDelegatesByDivision = $delegatesByDivision->pluck('department_name')->toArray();

        // Delegates Assignments
        $delegationIds = Delegation::where('event_id', $currentEventId)->whereIN('invitation_status_id', [41, 42])->pluck('id');
        $totalDelegations = count($delegationIds);

        $assignedEscorts = DelegationEscort::whereIn('delegation_id', $delegationIds)
            ->where('status', 1)
            ->distinct('delegation_id')
            ->count('delegation_id');
        $notAssignedEscorts = $totalDelegations - $assignedEscorts;

        $assignedDrivers = DelegationDriver::whereIn('delegation_id', $delegationIds)
            ->where('status', 1)
            ->distinct('delegation_id')
            ->count('delegation_id');
        $notAssignedDrivers = $totalDelegations - $assignedDrivers;

        
        $hotelDelegation = Delegation::where('event_id', $currentEventId)
                                        ->whereIN('id',$delegationIds)
                                        ->get();


        $data['delegation_assignments'] = [
            'assignedEscorts' => $assignedEscorts,
            'notAssignedEscorts' => $notAssignedEscorts,
            'assignedDrivers' => $assignedDrivers,
            'notAssignedDrivers' => $notAssignedDrivers,
            'assignedHotels' => $hotelDelegation->whereIN('accomodation_status', [1,3])->count(),
            'notAssignedHotels' => $hotelDelegation->whereIN('accomodation_status', [0, 2])->count()
        ];


        // Arrival Status
        $delegIds = $delegationIds;

        $statuses = Delegate::whereIn('delegation_id', $delegIds)
            ->select('participation_status')
            ->get()
            ->groupBy('participation_status')
            ->map->count();

        $allStatuses = [
            'to_be_arrived' => 0,
            'arrived' => 0,
            // 'to_be_departed' => 0,
            'departed' => 0
        ];

        $statuses = array_merge($allStatuses, $statuses->toArray());

        $data['arrival_status'] = [
            'to_be_arrived' => $statuses['to_be_arrived'],
            'arrived' => $statuses['arrived'],
            // 'to_be_departed' => $statuses['to_be_departed'],
            'departed' => $statuses['departed']
        ];

        // Member arrivals and departures

        $airports = DropdownOption::whereHas('dropdown', function ($query) {
            $query->where('code', 'airports');
        })->where('status', 1)->orderBy('sort_order', 'asc')
            ->select(
                'id',
                'sort_order',
                DB::raw("'flight' as mode"),
                DB::raw(
                    $lang === 'ar'
                        ? "CASE WHEN value_ar IS NOT NULL AND value_ar != '' THEN value_ar ELSE value END as transport_point"
                        : "value as transport_point"
                )
            );


        $static = DB::table(DB::raw("(SELECT NULL as id, 100 as sort_order, 'Sea' as transport_point, 'sea' as mode 
            UNION ALL 
            SELECT NULL, 101, 'Land', 'land') as s"));

        $base = $airports->unionAll($static);

        $summary = DB::query()
            ->fromSub($base, 'tp')
            ->leftJoin('delegate_transports as dt', function ($join) {
                $join->whereRaw("(tp.mode = 'flight' AND dt.airport_id = tp.id AND dt.mode = 'flight')")
                    ->orWhereRaw("(tp.mode = 'sea' AND dt.mode = 'sea')")
                    ->orWhereRaw("(tp.mode = 'land' AND dt.mode = 'land')");
            })
            ->select(
                'tp.transport_point',
                DB::raw("SUM(CASE WHEN dt.type = 'arrival' AND DATE(dt.date_time) = CURDATE() THEN 1 ELSE 0 END) as arrival_count"),
                DB::raw("SUM(CASE WHEN dt.type = 'departure' AND DATE(dt.date_time) = CURDATE() THEN 1 ELSE 0 END) as departure_count")
            )
            ->groupBy('tp.transport_point', 'tp.mode')
            ->orderByRaw("tp.sort_order,
                        CASE 
                            WHEN tp.mode = 'flight' THEN 1
                            WHEN tp.mode = 'land' THEN 2
                            WHEN tp.mode = 'sea' THEN 3
                        END, tp.transport_point
                    ")
            ->get();

        // Delegates By Invitation Status
        $departments = DB::table('dropdown_options as d')
            ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
            ->where('dropdowns.code', 'departments')
            ->where('d.status', 1)
            ->orderBy('d.sort_order', 'asc')
            ->select('d.id', DB::raw($lang === 'ar' ? 'COALESCE(d.value_ar, d.value) as value' : 'd.value as value'))
            ->get();

        $statusesList = DB::table('dropdown_options as d')
            ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
            ->where('dropdowns.code', 'invitation_status')
            ->where('d.status', 1)
            ->orderBy('d.sort_order', 'asc')
            ->select('d.id', 'd.code',  DB::raw($lang === 'ar' ? 'COALESCE(d.value_ar, d.value) as value' : 'd.value as value'))
            ->get();

        $rawData = DB::table('delegations')
            ->join('dropdown_options as departments', function ($join) {
                $join->on('delegations.invitation_from_id', '=', 'departments.id')
                    ->where('departments.status', 1);
            })
            ->join('dropdown_options as statuses', function ($join) {
                $join->on('delegations.invitation_status_id', '=', 'statuses.id')
                    ->where('statuses.status', 1);
            })
            ->join('dropdowns as d1', 'departments.dropdown_id', '=', 'd1.id')
            ->join('dropdowns as d2', 'statuses.dropdown_id', '=', 'd2.id')
            ->where('delegations.event_id', $currentEventId)
            ->where('d1.code', 'departments')
            ->where('d2.code', 'invitation_status')
            ->select(
                'departments.id as department_id',
                'statuses.id as status_id',
                DB::raw('COUNT(delegations.id) as total')
            )
            ->groupBy('departments.id', 'statuses.id')
            ->get();

        $categories = $departments->pluck('value')->values();

        $delegatesByInvitationStatus = [];

        $baseColor = '#B68A35';
        $spread = 50;
        $labelsCount = $statusesList->count();
        foreach ($statusesList as $i => $status) {
            $dataNew = [];
            foreach ($departments as $dept) {
                $match = $rawData->first(
                    fn($row) =>
                    $row->department_id === $dept->id && $row->status_id === $status->id
                );
                $dataNew[] = $match ? (int) $match->total : 0;
            }

            $color = '#B68A35';
            if ($status->code == '1') {  // Waiting
                $color = '#FFF9C4';
            } elseif ($status->code == '2') {  // Accepted
                $color = '#C8E6C9';
            } elseif ($status->code == '3') {   // Rejected
                $color = '#F8BBD0';
            } elseif ($status->code == '9') {  // Accepted with representative
                $color = '#E1BEE7';
            } elseif ($status->code == '10') {  // Accepted with secretary
                $color = '#BBDEFB';
            }

            $delegatesByInvitationStatus[] = [
                'name' => $status->value,
                'data' => $dataNew,
                'color' => $color,
            ];
        }
        $data['delegatesByInvitationStatus'] = [
            'categories' => $categories,
            'series' => $delegatesByInvitationStatus
        ];

        // Delegates By participation status

        $participation_statuses = ['to_be_arrived', 'arrived', 'to_be_departed', 'departed'];

        $rawDataParticipation = DB::table('delegates')
            ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
            ->where('delegations.event_id', $currentEventId)
            ->select(
                'delegations.invitation_from_id',
                'delegates.participation_status',
                DB::raw('COUNT(delegates.id) as total')
            )

            ->whereIN('delegations.invitation_status_id', [41, 42])
            ->groupBy('delegates.participation_status', 'delegations.invitation_from_id')
            ->get();

        $departmentsIds = $departments->pluck('id');
        $categoriesParticipation = $departments->pluck('value');
        $seriesParticipation = [];

        $statusColors = [
            'arrived'        => '#A8E6CF',
            'to_be_arrived'  => '#D1C4E9',
            'departed'       => '#FFAAA5',
        ];

        foreach ($participation_statuses as $part_status) {
            $dataPart = [];
            foreach ($departmentsIds as $deptId) {
                $match = $rawDataParticipation->first(
                    fn($row) =>
                    $row->invitation_from_id == $deptId &&
                        $row->participation_status == $part_status
                );
                $dataPart[] = $match ? (int)$match->total : 0;
            }
            $seriesParticipation[] = [
                'name'  => __db($part_status),
                'data'  => $dataPart,
                'color' => $statusColors[$part_status] ?? null,
            ];
        }

        $data['delegatesByParticipationStatus'] = [
            'categories' => $categoriesParticipation,
            'series' => $seriesParticipation
        ];

        // Total number of accepted invitations by Continents.
        $continents = DB::table('dropdown_options')
            ->where('dropdown_id', function ($q) {
                $q->select('id')->from('dropdowns')->where('code', 'continents')->limit(1);
            })
            ->where('status', 1)
            ->pluck(DB::raw($lang === 'ar' ? 'COALESCE(value_ar, value) as value' : 'value as value'), 'id');

        $rawDataContinents = DB::table('delegations')
            ->where('event_id', $currentEventId)
            ->whereIN('invitation_status_id', [41, 42])
            ->select('continent_id', 'invitation_from_id as department_id', DB::raw('COUNT(delegations.id) as total'))
            ->groupBy('continent_id', 'invitation_from_id')
            ->get();

        $lookup = [];
        foreach ($rawDataContinents as $row) {
            $lookup[$row->continent_id][$row->department_id] = (int) $row->total;
        }

        $seriesContinents = [];
        // $baseColorCont = ['#d9a644', '#A0782F', '#f0da8b', '#806028', '#5C451D', '#e8bc64', '#D2AA59', '#E0BA6B', '#ECCC85', '#F7DEA0'];
        $baseColorCont = [
            '#FFB3BA',
            '#BAE0FF',
            '#FFFFBA',
            '#BAFFC9',
            '#BAE1FF',
            '#D5BAFF',
            '#FFC1E3',
            '#FFE0BA',
            '#FFF5BA',
            '#C8FFD4',
            '#C4E7FF',
            '#E1C8FF',
            '#FFBAF2',
            '#FFE5BA',
            '#FCFFBA',
            '#BAFFD0',
            '#BAF0FF',
            '#D0BAFF',
            '#FFF0BA',
            '#BAFFE4',
            '#FFBAC8',
            '#FFDABA',
            '#FFFFC4',
            '#BAFFD1',
            '#E3BAFF',
            '#FFBAE0',
            '#FFE8BA',
            '#FFDFBA',
            '#BFFAFF',
            '#D9FFBA'
        ];


        $ij = 0;
        foreach ($continents as $continentId => $continentName) {
            $dataCont = [];
            foreach ($departments as $dept) {
                $dataCont[] = $lookup[$continentId][$dept->id] ?? 0;
            }

            $seriesContinents[] = [
                'name' => $continentName,
                'data' => $dataCont,
                'color' => $baseColorCont[$ij],
            ];
            $ij++;
        }

        $data['invitationByContinents'] = [
            'categories' => $departments->pluck('value')->values(), // x-axis = departments
            'series'     => $seriesContinents,                      // each continent as series
        ];

        // Upcoming Arrivals & Departures

        $now = Carbon::now();
        $oneHourBefore = $now->copy()->subHour();
        $oneHourAfter  = $now->copy()->addHour();

        $arrivals_query = DelegateTransport::where('type', 'arrival')
            ->with([
                'delegate.delegation.country',
                'delegate.delegation.continent',
                'delegate.delegation.escorts',
                'delegate.delegation.drivers',
                'airport'
            ])
            ->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                $delegationQuery->where('event_id', $currentEventId);
            })
            ->whereDate('date_time', $now->toDateString()) // Only today
            ->where('status', '!=', 'arrived')
            ->orderByRaw("
                                CASE 
                                    WHEN date_time BETWEEN ? AND ? THEN 0
                                    ELSE 1
                                END ASC
                            ", [$oneHourBefore, $oneHourAfter])
            ->orderBy('date_time', 'asc');
        $data['upcomming_arrivals'] = $arrivals_query->paginate(5, ['*'], 'arrival_page');

        $departure_query = DelegateTransport::where('type', 'departure')
            ->with([
                'delegate.delegation.country',
                'delegate.delegation.continent',
                'delegate.delegation.escorts',
                'delegate.delegation.drivers',
                'airport'
            ])
            ->whereHas('delegate.delegation', function ($delegationQuery) use ($currentEventId) {
                $delegationQuery->where('event_id', $currentEventId);
            })
            ->whereDate('date_time', $now->toDateString()) // Only today
            ->where('status', '!=', 'departed')
            ->orderByRaw("
                                CASE 
                                    WHEN date_time BETWEEN ? AND ? THEN 0
                                    ELSE 1
                                END ASC
                            ", [$oneHourBefore, $oneHourAfter])
            ->orderBy('date_time', 'asc');
        $data['upcomming_departures'] = $departure_query->paginate(5, ['*'], 'departure_page');

        $data['arr_dep_summary'] = $summary;


        $data['totalDelegates'] = $totalDelegates;
        $data['totalEscortsAssigned'] = $totalEscortsAssigned;
        $data['totalDriversAssigned'] = $totalDriversAssigned;
        $data['totalHotels'] = $totalHotels;
        $data['delegatesByDivision'] = ['labels' => $labelsDelegatesByDivision, 'series' => $seriesDelegatesByDivision];
        // echo '<pre>';
        // print_r($summary);
        // echo '</pre>';
        // exit;

        $request->session()->put('show_delegations_last_url', url()->full());

        return view('admin.dashboard', compact('data'));
    }

    public function dashboardTables($table)
    {

        $lang = app()->getLocale() ?? 'en';
        $currentEventId = session('current_event_id', getDefaultEventId());

        $departments = DB::table('dropdown_options as d')
            ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
            ->where('dropdowns.code', 'departments')
            ->where('d.status', 1)
            ->orderBy('d.sort_order', 'asc')
            ->select('d.id',  DB::raw($lang === 'ar' ? 'COALESCE(d.value_ar, d.value) as value' : 'd.value as value'))
            ->get();


        if ($table == 'invitations') {

            $statusesList = DB::table('dropdown_options as d')
                ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
                ->where('dropdowns.code', 'invitation_status')
                ->where('d.status', 1)
                ->orderBy('d.sort_order', 'asc')
                ->select('d.id', 'd.code',  DB::raw($lang === 'ar' ? 'COALESCE(d.value_ar, d.value) as value' : 'd.value as value'))
                ->get();

            $rawData = DB::table('delegations')
                ->join('dropdown_options as departments', function ($join) {
                    $join->on('delegations.invitation_from_id', '=', 'departments.id')
                        ->where('departments.status', 1);
                })
                ->join('dropdown_options as statuses', function ($join) {
                    $join->on('delegations.invitation_status_id', '=', 'statuses.id')
                        ->where('statuses.status', 1);
                })
                ->join('dropdowns as d1', 'departments.dropdown_id', '=', 'd1.id')
                ->join('dropdowns as d2', 'statuses.dropdown_id', '=', 'd2.id')
                ->where('delegations.event_id', $currentEventId)
                ->where('d1.code', 'departments')
                ->where('d2.code', 'invitation_status')
                ->select(
                    'departments.id as department_id',
                    'statuses.id as status_id',
                    DB::raw('COUNT(delegations.id) as total')
                )
                ->groupBy('departments.id', 'statuses.id')
                ->get();

            $categories = $departments->pluck('value')->values();

            $baseColor = '#B68A35';
            $spread = 50;
            $labelsCount = $statusesList->count();

            $delegatesByInvitationStatus = [];
            foreach ($statusesList as $i => $status) {
                $dataNew = [];
                foreach ($departments as $dept) {
                    $match = $rawData->first(
                        fn($row) =>
                        $row->department_id === $dept->id && $row->status_id === $status->id
                    );
                    $dataNew[] = $match ? (int) $match->total : 0;
                }

                $color = '#B68A35';
                if ($status->code == '1') {  // Waiting
                    $color = '#FFF9C4';
                } elseif ($status->code == '2') {  // Accepted
                    $color = '#C8E6C9';
                } elseif ($status->code == '3') {   // Rejected
                    $color = '#F8BBD0';
                } elseif ($status->code == '9') {  // Accepted with representative
                    $color = '#E1BEE7';
                } elseif ($status->code == '10') {  // Accepted with secretary
                    $color = '#BBDEFB';
                }

                $delegatesByInvitationStatus[] = [
                    'name' => $status->value,
                    'data' => $dataNew,
                    'color' => $color,
                ];
            }
            $tableData = [];
            $rowTotals = [];
            $colTotals = [];

            foreach ($departments as $dept) {
                $tableData[$dept->id] = [];
                $rowTotals[$dept->id] = 0;

                foreach ($statusesList as $status) {
                    $tableData[$dept->id][$status->id] = 0;
                }
            }
            foreach ($statusesList as $status) {
                $colTotals[$status->id] = 0;
            }

            foreach ($rawData as $row) {
                $tableData[$row->department_id][$row->status_id] = (int) $row->total;
                $rowTotals[$row->department_id] += $row->total;
                $colTotals[$row->status_id] += $row->total;
            }

            $grandTotal = array_sum($rowTotals);

            $data['delegatesByInvitationStatusTable'] = [
                'tableData' => $tableData,
                'rowTotals' => $rowTotals,
                'colTotals' => $colTotals,
                'grandTotal' => $grandTotal,
                'departments' => $departments,
                'statuses' => $statusesList,
            ];

            $data['delegatesByInvitationStatus'] = [
                'categories' => $categories,
                'series' => $delegatesByInvitationStatus
            ];

            return view('admin.dashboard-tables.invitations', compact('data'));
        } elseif ($table == 'participations') {

            $participation_statuses = ['to_be_arrived', 'arrived', 'departed'];

            $rawDataParticipation = DB::table('delegates')
                ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
                ->where('delegations.event_id', $currentEventId)
                ->select(
                    'delegations.invitation_from_id',
                    'delegates.participation_status',
                    DB::raw('COUNT(delegates.id) as total')
                )
                ->whereIN('delegations.invitation_status_id', [41, 42])
                ->groupBy('delegates.participation_status', 'delegations.invitation_from_id')
                ->get();

            $departmentsIds   = $departments->pluck('id');
            $categories       = $departments->pluck('value');
            $seriesParticipation = [];

            $statusColors = [
                'arrived'        => '#A8E6CF',
                'to_be_arrived'  => '#D1C4E9',
                'departed'       => '#FFAAA5',
            ];

            foreach ($participation_statuses as $part_status) {
                $dataPart = [];
                foreach ($departmentsIds as $deptId) {
                    $match = $rawDataParticipation->first(
                        fn($row) =>
                        $row->invitation_from_id == $deptId &&
                            $row->participation_status == $part_status
                    );
                    $dataPart[] = $match ? (int)$match->total : 0;
                }
                $seriesParticipation[] = [
                    'name'  => __db($part_status),
                    'data'  => $dataPart,
                    'color' => $statusColors[$part_status] ?? null,
                ];
            }

            $data['delegatesByParticipationStatus'] = [
                'categories' => $categories,
                'series' => $seriesParticipation
            ];

            $tableData = [];
            $rowTotals = [];
            $colTotals = array_fill_keys($participation_statuses, 0);
            $grandTotal = 0;

            foreach ($departments as $dept) {
                $rowTotal = 0;
                foreach ($participation_statuses as $status) {
                    $count = $rawDataParticipation
                        ->first(fn($r) => $r->invitation_from_id == $dept->id && $r->participation_status == $status)
                        ->total ?? 0;

                    $tableData[$dept->id][$status] = $count;
                    $rowTotal += $count;
                    $colTotals[$status] += $count;
                    $grandTotal += $count;
                }
                $rowTotals[$dept->id] = $rowTotal;
            }

            $data['delegatesByParticipationTable'] = [
                'departments'    => $departments,
                'statuses'       => $participation_statuses,
                'tableData'      => $tableData,
                'rowTotals'      => $rowTotals,
                'colTotals'      => $colTotals,
                'grandTotal'     => $grandTotal,
            ];
            return view('admin.dashboard-tables.participations', compact('data'));
        } elseif ($table == 'continents') {
            $continents = DB::table('dropdown_options')
                ->where('dropdown_id', function ($q) {
                    $q->select('id')->from('dropdowns')->where('code', 'continents')->limit(1);
                })
                ->where('status', 1)
                ->pluck(DB::raw($lang === 'ar' ? 'COALESCE(value_ar, value) as value' : 'value as value'), 'id');

            $rawDataContinents = DB::table('delegations')
                ->where('event_id', $currentEventId)
                ->whereIN('invitation_status_id', [41, 42])
                ->select('continent_id', 'invitation_from_id as department_id', DB::raw('COUNT(delegations.id) as total'))
                ->groupBy('continent_id', 'invitation_from_id')
                ->get();
            $lookup = [];
            foreach ($rawDataContinents as $row) {
                $lookup[$row->continent_id][$row->department_id] = (int) $row->total;
            }

            $seriesContinents = [];
            $baseColorCont = [
                '#FFB3BA',
                '#BAE0FF',
                '#FFFFBA',
                '#BAFFC9',
                '#BAE1FF',
                '#D5BAFF',
                '#FFC1E3',
                '#FFE0BA',
                '#FFF5BA',
                '#C8FFD4',
                '#C4E7FF',
                '#E1C8FF',
                '#FFBAF2',
                '#FFE5BA',
                '#FCFFBA',
                '#BAFFD0',
                '#BAF0FF',
                '#D0BAFF',
                '#FFF0BA',
                '#BAFFE4',
                '#FFBAC8',
                '#FFDABA',
                '#FFFFC4',
                '#BAFFD1',
                '#E3BAFF',
                '#FFBAE0',
                '#FFE8BA',
                '#FFDFBA',
                '#BFFAFF',
                '#D9FFBA'
            ];

            $ij = 0;

            foreach ($continents as $continentId => $continentName) {
                $dataCont = [];
                foreach ($departments as $dept) {
                    $dataCont[] = $lookup[$continentId][$dept->id] ?? 0;
                }

                $seriesContinents[] = [
                    'name' => $continentName,
                    'data' => $dataCont,
                    'color' => $baseColorCont[$ij],
                ];
                $ij++;
            }

            $data['invitationByContinents'] = [
                'categories' => $departments->pluck('value')->values(),
                'series'     => $seriesContinents,
            ];

            $tableData = [];
            $grandTotal = 0;

            foreach ($departments as $dept) {
                $rowTotal = 0;
                $row = [
                    'department' => $dept->value,
                    'continents' => [],
                    'total'      => 0,
                ];

                foreach ($continents as $continentId => $continentName) {
                    $count = $lookup[$continentId][$dept->id] ?? 0;
                    $row['continents'][$continentId] = $count;
                    $rowTotal += $count;

                    $colTotals[$continentId] = ($colTotals[$continentId] ?? 0) + $count;
                }

                $row['total'] = $rowTotal;
                $grandTotal += $rowTotal;
                $tableData[] = $row;
            }

            $colTotals['row_total'] = $grandTotal;

            $data['invitationByContinentsTable'] = [
                'departments' => $departments,
                'continents'  => $continents,
                'rows'        => $tableData,
                'colTotals'   => $colTotals,
            ];
            return view('admin.dashboard-tables.continents', compact('data'));
        } elseif ($table == 'divisions') {
            $delegatesByDivision =  DropdownOption::leftJoin('delegations', function ($join) use ($currentEventId) {
                $join->on('delegations.invitation_from_id', '=', 'dropdown_options.id')
                    ->where('delegations.event_id', $currentEventId);
            })
                // ->leftJoin('delegates', 'delegates.delegation_id', '=', 'delegations.id')
                ->join('dropdowns', 'dropdowns.id', '=', 'dropdown_options.dropdown_id')
                ->where('dropdowns.code', 'departments')
                ->where('dropdown_options.status', 1)
                ->groupBy('dropdown_options.id', 'dropdown_options.value', 'dropdown_options.sort_order')
                ->orderBy('dropdown_options.sort_order')
                ->select(DB::raw($lang === 'ar' ? 'COALESCE(dropdown_options.value_ar, dropdown_options.value) as department_name' : 'dropdown_options.value as department_name'), DB::raw('COUNT(delegations.id) as total'))
                ->get();

            $seriesDelegatesByDivision = $delegatesByDivision->pluck('total')->toArray();
            $labelsDelegatesByDivision = $delegatesByDivision->pluck('department_name')->toArray();

            $data['delegatesByDivision'] = [
                'series' => $seriesDelegatesByDivision,
                'labels' => $labelsDelegatesByDivision,
            ];
            return view('admin.dashboard-tables.divisions', compact('data', 'delegatesByDivision'));
        } elseif ($table == 'assignments') {
            $delegationIds = Delegation::where('event_id', $currentEventId)->whereIN('invitation_status_id', [41, 42])->pluck('id');
            $totalDelegations = count($delegationIds);

            $assignedEscorts = DelegationEscort::whereIn('delegation_id', $delegationIds)
                ->where('status', 1)
                ->distinct('delegation_id')
                ->count('delegation_id');
            $notAssignedEscorts = $totalDelegations - $assignedEscorts;

            $assignedDrivers = DelegationDriver::whereIn('delegation_id', $delegationIds)
                ->where('status', 1)
                ->distinct('delegation_id')
                ->count('delegation_id');
            $notAssignedDrivers = $totalDelegations - $assignedDrivers;

            $hotelDelegation = Delegation::where('event_id', $currentEventId)
                                        ->whereIN('invitation_status_id', [41, 42])
                                        ->get();


            $data['delegation_assignments'] = [
                'assignedEscorts' => $assignedEscorts,
                'notAssignedEscorts' => $notAssignedEscorts,
                'assignedDrivers' => $assignedDrivers,
                'notAssignedDrivers' => $notAssignedDrivers,
                'assignedHotels' => $hotelDelegation->whereIN('accomodation_status', [1,3])->count(),
                'notAssignedHotels' => $hotelDelegation->whereIN('accomodation_status', [0, 2])->count()
            ];


            return view('admin.dashboard-tables.assignments', compact('data'));
        } elseif ($table == 'arrival') {
            $delegIds = Delegation::where('event_id', $currentEventId)->whereIN('invitation_status_id', [41, 42])->pluck('id');

            $statuses = Delegate::whereIn('delegation_id', $delegIds)
                ->select('participation_status')
                ->get()
                ->groupBy('participation_status')
                ->map->count();

            $allStatuses = [
                'to_be_arrived' => 0,
                'arrived' => 0,
                // 'to_be_departed' => 0,
                'departed' => 0
            ];

            $statuses = array_merge($allStatuses, $statuses->toArray());

            $data['arrival_status'] = [
                'to_be_arrived' => $statuses['to_be_arrived'],
                'arrived' => $statuses['arrived'],
                // 'to_be_departed' => $statuses['to_be_departed'],
                'departed' => $statuses['departed']
            ];

            return view('admin.dashboard-tables.arrival', compact('data'));
        }
        return redirect()->route('admin.dashboard');
    }

    public function account()
    {
        $user = Auth::user();

        $showSystemTools = false;

        if ($user->hasRole('Super Admin')) {
            $showSystemTools = true;
        }

        return view('admin.account', compact('user', 'showSystemTools'));
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => __db('current_password_required'),
            'password.required' => __db('new_password_required'),
            'password.confirmed' => __db('confirm_password_mismatch'),
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __db('current_password_incorrect')])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->force_password = 0;
        $user->save();

        return back()->with('success', __db('password_changed_successfully'));
    }
}
