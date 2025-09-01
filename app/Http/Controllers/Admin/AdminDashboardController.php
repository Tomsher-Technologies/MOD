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
use DB;

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
}
