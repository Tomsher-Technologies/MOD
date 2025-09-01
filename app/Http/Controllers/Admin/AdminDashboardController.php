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


class AdminDashboardController extends Controller
{
    
    public function dashboard(){
   
        $currentEventId = session('current_event_id', getDefaultEventId());

        $totalDelegates = Delegate::whereHas('delegation', function ($q) use ($currentEventId) {
                                $q->where('event_id', $currentEventId);
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
        $delegatesByDivision =  DropdownOption::leftJoin('delegations', function($join) use ($currentEventId) {
                                            $join->on('delegations.invitation_from_id', '=', 'dropdown_options.id')
                                                ->where('delegations.event_id', $currentEventId);
                                        })
                                        ->leftJoin('delegates', 'delegates.delegation_id', '=', 'delegations.id')
                                        ->join('dropdowns', 'dropdowns.id', '=', 'dropdown_options.dropdown_id')
                                        ->where('dropdowns.code', 'departments')
                                        ->where('dropdown_options.status', 1)
                                        ->groupBy('dropdown_options.id', 'dropdown_options.value', 'dropdown_options.sort_order')
                                        ->orderBy('dropdown_options.sort_order')
                                        ->select('dropdown_options.value as department_name', DB::raw('COUNT(delegates.id) as total'))
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

        $hotels_status = DB::table('delegations as d')->select(
                        'd.id as delegation_id',
                        'd.code as delegation_name',
                        DB::raw("
                            CASE
                                WHEN (COALESCE(delegates_summary.total_count, 0) +
                                    COALESCE(escorts_summary.total_count, 0) +
                                    COALESCE(drivers_summary.total_count, 0)) = 0
                                    THEN 0
                                WHEN (COALESCE(delegates_summary.assigned_count, 0) +
                                    COALESCE(escorts_summary.assigned_count, 0) +
                                    COALESCE(drivers_summary.assigned_count, 0)) = 0
                                    THEN 0
                                WHEN (COALESCE(delegates_summary.assigned_count, 0) +
                                    COALESCE(escorts_summary.assigned_count, 0) +
                                    COALESCE(drivers_summary.assigned_count, 0)) =
                                    (COALESCE(delegates_summary.total_count, 0) +
                                    COALESCE(escorts_summary.total_count, 0) +
                                    COALESCE(drivers_summary.total_count, 0))
                                    THEN 1
                                ELSE 2
                            END as status
                        "),
                        DB::raw("(COALESCE(delegates_summary.total_count, 0) + 
                                COALESCE(escorts_summary.total_count, 0) + 
                                COALESCE(drivers_summary.total_count, 0)) as total_count"),
                        DB::raw("(COALESCE(delegates_summary.assigned_count, 0) + 
                                COALESCE(escorts_summary.assigned_count, 0) + 
                                COALESCE(drivers_summary.assigned_count, 0)) as assigned_count")
                    )
                    ->leftJoin(
                        DB::raw("(select delegation_id,
                                        COUNT(*) as total_count,
                                        SUM(CASE WHEN current_room_assignment_id IS NOT NULL THEN 1 ELSE 0 END) as assigned_count
                                from delegates
                                where accommodation = 1
                                group by delegation_id) as delegates_summary"),
                        'delegates_summary.delegation_id', '=', 'd.id'
                    )
                    ->leftJoin(
                        DB::raw("(select de.delegation_id,
                                        COUNT(*) as total_count,
                                        SUM(CASE WHEN e.current_room_assignment_id IS NOT NULL THEN 1 ELSE 0 END) as assigned_count
                                from delegation_escorts de
                                inner join escorts e on e.id = de.escort_id
                                where de.status = 1
                                group by de.delegation_id) as escorts_summary"),
                        'escorts_summary.delegation_id', '=', 'd.id'
                    )
                    ->leftJoin(
                        DB::raw("(select dd.delegation_id,
                                        COUNT(*) as total_count,
                                        SUM(CASE WHEN dr.current_room_assignment_id IS NOT NULL THEN 1 ELSE 0 END) as assigned_count
                                from delegation_drivers dd
                                inner join drivers dr on dr.id = dd.driver_id
                                where dd.status = 1
                                group by dd.delegation_id) as drivers_summary"),
                        'drivers_summary.delegation_id', '=', 'd.id'
                    )
                    ->where('d.event_id', $currentEventId)
                    ->whereIN('d.invitation_status_id', [41, 42])
                    ->get();
        

        $data['delegation_assignments'] = [
                                            'assignedEscorts' => $assignedEscorts,
                                            'notAssignedEscorts' => $notAssignedEscorts,
                                            'assignedDrivers' => $assignedDrivers,
                                            'notAssignedDrivers' => $notAssignedDrivers,
                                            'assignedHotels' => $hotels_status->where('status', 1)->count(),
                                            'notAssignedHotels' => $hotels_status->whereIN('status', [0, 2])->count()
                                        ];

        
        // Arrival Status
        $delegIds = Delegation::where('event_id', $currentEventId)->pluck('id');

        $statuses = Delegate::whereIn('delegation_id', $delegIds)
            ->select('participation_status')
            ->get()
            ->groupBy('participation_status')
            ->map->count();

        $allStatuses = [
            'not_yet_arrived' => 0,
            'arrived' => 0,
            'departed' => 0
        ];

        $statuses = array_merge($allStatuses, $statuses->toArray());

        // Member arrivals and departures
        $summary =  DelegateTransport::query()
                        ->selectRaw("
                            CASE 
                                WHEN delegate_transports.mode = 'flight' THEN dropdown_options.value 
                                WHEN delegate_transports.mode = 'sea' THEN 'Sea' 
                                WHEN delegate_transports.mode = 'land' THEN 'Land' 
                            END AS transport_point,
                            SUM(CASE WHEN delegate_transports.type = 'arrival' THEN 1 ELSE 0 END) AS arrival_count,
                            SUM(CASE WHEN delegate_transports.type = 'departure' THEN 1 ELSE 0 END) AS departure_count
                        ")
                        ->leftJoin('dropdown_options', function ($join) {
                            $join->on('delegate_transports.airport_id', '=', 'dropdown_options.id')
                                ->where('delegate_transports.mode', '=', 'flight');
                        })
                        ->whereDate('delegate_transports.date_time', now()->toDateString())
                        ->groupBy('transport_point')
                        ->get();

        // Delegates By Invitation Status
        $departments = DB::table('dropdown_options as d')
                            ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
                            ->where('dropdowns.code', 'departments')
                            ->where('d.status', 1)
                            ->select('d.id', 'd.value')
                            ->get();

        $statusesList = DB::table('dropdown_options as d')
                            ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
                            ->where('dropdowns.code', 'invitation_status')
                            ->where('d.status', 1)
                            ->select('d.id', 'd.value')
                            ->get();

        $rawData = DB::table('delegates')
                    ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
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
                        DB::raw('COUNT(delegates.id) as total')
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
                $match = $rawData->first(fn ($row) =>
                    $row->department_id === $dept->id && $row->status_id === $status->id
                );
                $dataNew[] = $match ? (int) $match->total : 0;
            }

            $position = ($i % 2 == 0) ? -1 : 1; // even = darker, odd = lighter
            $step = ceil($i / 2);
            $percent = $position * ($spread * $step / max(1, ceil($labelsCount / 2)));

            $delegatesByInvitationStatus[] = [
                'name' => $status->value,
                'data' => $dataNew,
                'color' => shadeColor($baseColor, $percent) ?? $baseColor,
            ];
        }
        $data['delegatesByInvitationStatus'] = [
                                                    'categories' => $categories,
                                                    'series' => $delegatesByInvitationStatus
                                                ];
        
        // Delegates By participation status

        $participation_statuses = ['to_be_arrived','arrived','departed'];

        $rawDataParticipation = DB::table('delegates')
                                    ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
                                    ->where('delegations.event_id', $currentEventId)
                                    ->select(
                                        'delegations.invitation_from_id',
                                        'delegates.participation_status',
                                        DB::raw('COUNT(delegates.id) as total')
                                    )
                                    ->groupBy('delegations.invitation_from_id', 'delegates.participation_status')
                                    ->get();

        $departmentsIds = $departments->pluck('id');   
        $categoriesParticipation = $departments->pluck('value');
        $seriesParticipation = [];

        $statusColors = [
            'arrived'         => '#7c5e24',
            'to_be_arrived'   => '#b68a35',
            'departed'      => '#f0da8b',
        ];

        foreach ($participation_statuses as $part_status) {
            $dataPart = [];
            foreach ($departmentsIds as $deptId) {
                $match = $rawDataParticipation->first(fn($row) =>
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
                            ->pluck('value', 'id');

        $rawDataContinents = DB::table('delegations')
                            ->where('event_id', $currentEventId)
                            ->whereIN('invitation_status_id', [41, 42])
                            ->select('continent_id','invitation_from_id as department_id', DB::raw('COUNT(delegations.id) as total'))
                            ->groupBy('continent_id', 'invitation_from_id')
                            ->get();
                            
        $lookup = [];
        foreach ($rawDataContinents as $row) {
            $lookup[$row->continent_id][$row->department_id] = (int) $row->total;
        }

        $seriesContinents = [];
        $baseColorCont = ['#d9a644','#A0782F','#f0da8b', '#806028','#5C451D','#e8bc64','#D2AA59','#E0BA6B','#ECCC85','#F7DEA0'];
        
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
        $data['upcomming_arrivals'] = $arrivals_query->get();

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
        $data['upcomming_departures'] = $departure_query->get();

        
        $data['arr_dep_summary'] = $summary;
        $data['arrival_status'] = [
            'not_yet_arrived' => $statuses['not_yet_arrived'],
            'arrived' => $statuses['arrived'],
            'departed' => $statuses['departed']
        ];

        $data['totalDelegates'] = $totalDelegates;
        $data['totalEscortsAssigned'] = $totalEscortsAssigned;
        $data['totalDriversAssigned'] = $totalDriversAssigned;
        $data['totalHotels'] = $totalHotels;
        $data['delegatesByDivision'] = ['labels' => $labelsDelegatesByDivision, 'series' => $seriesDelegatesByDivision];
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // exit;
        return view('admin.dashboard', compact('data'));
    }

    public function dashboardTables($table) {
       
        $currentEventId = session('current_event_id', getDefaultEventId());

        $departments = DB::table('dropdown_options as d')
                            ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
                            ->where('dropdowns.code', 'departments')
                            ->where('d.status', 1)
                            ->select('d.id', 'd.value')
                            ->get();

        if($table == 'invitations') {

            $statusesList = DB::table('dropdown_options as d')
                                ->join('dropdowns', 'd.dropdown_id', '=', 'dropdowns.id')
                                ->where('dropdowns.code', 'invitation_status')
                                ->where('d.status', 1)
                                ->select('d.id', 'd.value')
                                ->get();

            $rawData = DB::table('delegates')
                        ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
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
                            DB::raw('COUNT(delegates.id) as total')
                        )
                        ->groupBy('departments.id', 'statuses.id')
                        ->get();

            $categories = $departments->pluck('value')->values();

            $delegatesByInvitationStatus = [];
            foreach ($statusesList as $i => $status) {
                $dataNew = [];
                foreach ($departments as $dept) {
                    $match = $rawData->first(fn ($row) =>
                        $row->department_id === $dept->id && $row->status_id === $status->id
                    );
                    $dataNew[] = $match ? (int) $match->total : 0;
                }
                $delegatesByInvitationStatus[] = [
                    'name' => $status->value,
                    'data' => $dataNew
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

            return view('admin.dashboard-tables.invitations', compact('data'));
        }elseif($table == 'participations') {

            $participation_statuses = ['to_be_arrived','arrived','departed'];

            $rawDataParticipation = DB::table('delegates')
                                        ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
                                        ->where('delegations.event_id', $currentEventId)
                                        ->select(
                                            'delegations.invitation_from_id',
                                            'delegates.participation_status',
                                            DB::raw('COUNT(delegates.id) as total')
                                        )
                                        ->groupBy('delegations.invitation_from_id', 'delegates.participation_status')
                                        ->get();

            $departmentsIds   = $departments->pluck('id');
            $categories       = $departments->pluck('value');

            $statusColors = [
                'arrived'        => '#7c5e24',
                'to_be_arrived'  => '#b68a35',
                'departed'       => '#f0da8b',
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

            $data['delegatesByParticipationStatus'] = [
                'categories' => $categories,
                'series'     => collect($participation_statuses)->map(function($status) use ($departmentsIds, $rawDataParticipation, $statusColors) {
                    $dataPart = [];
                    foreach ($departmentsIds as $deptId) {
                        $match = $rawDataParticipation->first(fn($r) =>
                            $r->invitation_from_id == $deptId &&
                            $r->participation_status == $status
                        );
                        $dataPart[] = $match ? (int)$match->total : 0;
                    }
                    return [
                        'name'  => __db($status),
                        'data'  => $dataPart,
                        'color' => $statusColors[$status] ?? null,
                    ];
                }),
            ];

            $data['delegatesByParticipationTable'] = [
                'departments'    => $departments,
                'statuses'       => $participation_statuses,
                'tableData'      => $tableData,
                'rowTotals'      => $rowTotals,
                'colTotals'      => $colTotals,
                'grandTotal'     => $grandTotal,
            ];
            return view('admin.dashboard-tables.participations', compact('data'));
        }elseif($table == 'continents') {
            $continents = DB::table('dropdown_options')
                            ->where('dropdown_id', function ($q) {
                                $q->select('id')->from('dropdowns')->where('code', 'continents')->limit(1);
                            })
                            ->where('status', 1)
                            ->pluck('value', 'id');

            $rawDataContinents = DB::table('delegations')
                                ->where('event_id', $currentEventId)
                                ->whereIN('invitation_status_id', [41, 42])
                                ->select('continent_id','invitation_from_id as department_id', DB::raw('COUNT(delegations.id) as total'))
                                ->groupBy('continent_id', 'invitation_from_id')
                                ->get();
            $lookup = [];
            foreach ($rawDataContinents as $row) {
                $lookup[$row->continent_id][$row->department_id] = (int) $row->total;
            }

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
        }
        return redirect()->route('admin.dashboard');
    }
}
