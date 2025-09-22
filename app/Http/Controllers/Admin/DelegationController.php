<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesUpdateConfirmation;
use App\Models\Delegate;
use App\Models\DelegateTransport;
use App\Models\Delegation;
use App\Models\DelegationAttachment;
use App\Models\Interview;
use App\Models\Accommodation;
use App\Models\OtherInterviewMember;
use App\Services\DelegationStatusService;
use App\Exports\DelegationExport;
use App\Exports\DelegateExport;
use App\Imports\DelegateImport;
use App\Imports\DelegationAttachmentImport;
use App\Imports\DelegationOnlyImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DelegationController extends Controller
{
    use HandlesUpdateConfirmation;

    const UNASSIGNABLE_STATUS_CODES = [3, 9];
    const ASSIGNABLE_STATUS_CODES = [2, 10];

    protected $delegationStatusService;

    public function __construct()
    {
        $this->middleware('auth');

        // === Delegations ===
        $this->middleware('permission:view_delegations|delegate_view_delegations|escort_view_delegations|driver_view_delegations|hotel_view_delegations', [
            'only' => ['index', 'search', 'searchByCode', 'members', 'interviewsIndex', 'show', 'arrivalsIndex', 'departuresIndex', 'getTravelDetails']
        ]);

        $this->middleware('permission:add_delegations|delegate_add_delegations', [
            'only' => ['create', 'store', 'syncTransportInfo']
        ]);

        $this->middleware('permission:import_delegations', [
            'only' => ['showImportForm', 'import']
        ]);

        $this->middleware('permission:edit_delegations|delegate_edit_delegations', [
            'only' => ['edit', 'update', 'setDefault', 'editAttachment', 'updateAttachments', 'destroyAttachment', 'syncTransportInfo']
        ]);

        $this->middleware('permission:delete_delegations|delegate_delete_delegations', [
            'only' => ['destroy']
        ]);

        // === Delegates ===
        $this->middleware('permission:add_delegates|delegate_add_delegates', [
            'only' => ['addDelegate', 'syncTransportInfo', 'storeOrUpdateDelegate']
        ]);

        $this->middleware('permission:delete_delegates|delegate_delete_delegates', [
            'only' => ['destroyDelegate', 'syncTransportInfo']
        ]);

        $this->middleware('permission:edit_delegates|delegate_edit_delegates', [
            'only' => ['editDelegate', 'syncTransportInfo', 'storeOrUpdateDelegate']
        ]);

        // === Interviews ===
        $this->middleware('permission:add_interviews|delegate_edit_delegates', [
            'only' => ['storeOrUpdateInterview', 'addInterview']
        ]);

        $this->middleware('permission:edit_interviews|delegate_edit_delegates', [
            'only' => ['editInterview', 'storeOrUpdateInterview']
        ]);

        $this->middleware('permission:delete_interviews|delegate_edit_delegates', [
            'only' => ['destroyInterview']
        ]);

        // === Travels ===
        $this->middleware('permission:add_travels|delegate_edit_delegates', [
            'only' => ['addTravel', 'storeTravel', 'updateTravel']
        ]);

        $this->middleware('permission:edit_delegations|delegate_edit_delegations', [
            'only' => ['badgePrintedIndex']
        ]);

        // Badge Print Export
        $this->middleware('permission:badge_print_export', [
            'only' => ['badgePrintedIndex', 'exportNonBadgePrintedDelegates', 'exportNonBadgePrinted']
        ]);

        $this->delegationStatusService = new DelegationStatusService();
    }

    public function index(Request $request)
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
        ]);

        $currentEventId = session('current_event_id', getDefaultEventId());
        $query->where('delegations.event_id', $currentEventId);

        if ($request->filter_services_assignable) {
            $query->whereDoesntHave('invitationStatus', function ($q) {
                $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
            });
        }

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
                    ->orWhereHas('delegates', function ($delegateQuery) use ($search) {
                        $delegateQuery->where(function ($dq) use ($search) {
                            $dq->where('name_en', 'like', "%{$search}%")
                                ->orWhere('name_ar', 'like', "%{$search}%")
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

        $request->session()->put('delegations_last_url', url()->full());

        return view('admin.delegations.index', compact('delegations'));
    }


    public function interviewsIndex(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = Interview::with([
            'delegation.continent',
            'delegation.country',
            'status',
            'interviewWithDelegation',
            'fromMembers.fromDelegate',
            'toMembers.toDelegate',
            'toMembers.otherMember',
        ])->whereHas('delegation', function ($delegationQuery) use ($currentEventId) {
            $delegationQuery->where('event_id', $currentEventId);
        });

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('delegation', function ($delegationQuery) use ($search) {
                    $delegationQuery->where('code', 'like', "%{$search}%");
                })
                    ->orWhereHas('fromMembers.fromDelegate', function ($delegateQuery) use ($search) {
                        $delegateQuery->where('name_en', 'like', "%{$search}%")
                            ->orWhere('name_ar', 'like', "%{$search}%");
                    })
                    ->orWhereHas('toMembers.toDelegate', function ($delegateQuery) use ($search) {
                        $delegateQuery->where('name_en', 'like', "%{$search}%")
                            ->orWhere('name_ar', 'like', "%{$search}%");
                    })
                    ->orWhereHas('toMembers.otherMember', function ($otherMemberQuery) use ($search) {
                        $otherMemberQuery->where('name_en', 'like', "%{$search}%");
                    });
            });
        }

        if ($continentId = $request->input('continent_id')) {
            $query->whereHas('delegation', function ($q) use ($continentId) {
                if (is_array($continentId)) {
                    $q->whereIn('continent_id', $continentId);
                } else {
                    $q->where('continent_id', $continentId);
                }
            });
        }

        if ($countryId = $request->input('country_id')) {
            $query->whereHas('delegation', function ($q) use ($countryId) {
                if (is_array($countryId)) {
                    $q->whereIn('country_id', $countryId);
                } else {
                    $q->where('country_id', $countryId);
                }
            });
        }


        if ($statusId = $request->input('status_id')) {
            if (is_array($statusId)) {
                $query->whereIn('status_id', $statusId);
            } else {
                $query->where('status_id', $statusId);
            }
        }

        $limit = $request->limit ? $request->limit : 20;
        $interviews = $query->orderBy('id', 'desc')->paginate($limit);

        return view('admin.delegations.interviews', compact('interviews'));
    }

    public function create()
    {
        return view('admin.delegations.create', array_merge([
            'uniqueDelegateId' => '',
        ]));
    }

    public function edit($id)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates' => function ($query) {
                $query->with(['gender', 'parent', 'delegateTransports']);
            },
            'attachments',
            'interviews' => function ($query) {
                $query->with([
                    'status',
                    'interviewWithDelegation',
                    'interviewMembers'
                ]);
            },
            'escorts',
            'drivers'
        ])->findOrFail($id);

        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.edit', [
            'delegation' => $delegation,
            'unassignableStatus' => self::UNASSIGNABLE_STATUS_CODES
        ]);
    }

    public function show($id, Request $request)
    {
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

        // Return JSON for AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'delegation' => $delegation
            ]);
        }

        $interviews = Interview::with(['interviewMembers', 'interviewMembers.fromDelegate', 'interviewMembers.toDelegate', 'interviewWithDelegation'])
            ->where('delegation_id', $id)
            ->get();

        $hotels = Accommodation::where('status', 1)
            ->whereHas('rooms', function ($q) {
                $q->where('available_rooms', '>', 0);
            })
            ->where('event_id', session('current_event_id', getDefaultEventId() ?? null))
            ->orderBy('hotel_name', 'asc')
            ->get();

        $request->session()->put('interview_member_last_url', url()->full());


        return view('admin.delegations.show', compact('delegation', 'hotels'));
    }

    public function arrivalsIndex(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        $now = now();

        $oneHourAgo = $now->copy()->subHour();
        $oneHourFromNow = $now->copy()->addHours(1);

        if (!$request->input('date_range') && !$request->input('from_date') && !$request->input('to_date')) {
            $today = now()->format('Y-m-d');
            $request->merge(['date_range' => $today . ' - ' . $today]);
        }

        $arrivalsQuery = DelegateTransport::where('type', 'arrival')
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
            });

        $this->applyTransportFilters($arrivalsQuery, $request, $currentEventId);

        $limit = $request->limit ? $request->limit : 20;

        $arrivals = $arrivalsQuery->orderBy('date_time', 'asc')
            ->get()
            ->sortBy(function ($transport) use ($now, $oneHourAgo, $oneHourFromNow) {
                $scheduledTime = \Carbon\Carbon::parse($transport->date_time);
                $isInCriticalWindow = $scheduledTime->between($oneHourAgo, $oneHourFromNow);
                $isOverdue = $scheduledTime->lt($now) && $transport->status !== 'arrived';
                $isUpcoming = $scheduledTime->gt($now) && $scheduledTime->lte($now->copy()->addHour());
                $isArrived = $transport->status === 'arrived';

                if ($isArrived) {
                    return [5, $scheduledTime]; // Lowest priority: All arrived flights at the end
                } elseif ($isOverdue) {
                    return [0, $scheduledTime]; // Critical: Overdue flights
                } elseif ($isUpcoming) {
                    return [1, $scheduledTime]; // High priority: Arriving within 1 hour
                } elseif ($isInCriticalWindow) {
                    return [2, $scheduledTime]; // Medium priority: In time window
                } else {
                    return [3, $scheduledTime]; // Low priority: All other today's flights
                }
            })
            ->values();


        $arrivals = $arrivals->filter(function ($transport) use ($now) {
            $scheduledTime = \Carbon\Carbon::parse($transport->date_time);
            $oneHourAfterScheduled = $scheduledTime->copy()->addHour();

            return $transport->status !== 'arrived' ||
                $now->lte($oneHourAfterScheduled) ||
                $scheduledTime->lt($now->copy()->subHour());
        });


        $groupedArrivals = $this->groupTransports($arrivals);

        $currentPage = $request->input('page', 1);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($groupedArrivals)->forPage($currentPage, $limit),
            count($groupedArrivals),
            $limit,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => view('shared-pages.arrivals.table', compact('paginator', 'groupedArrivals'))->render()
            ]);
        }

        return view('admin.arrivals.index', compact('paginator', 'groupedArrivals'));
    }


    public function departuresIndex(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        $now = now();

        $oneHourAgo = $now->copy()->subHour();
        $oneHourFromNow = $now->copy()->addHours(1);

        // Set default date range to today if not provided
        if (!$request->input('date_range') && !$request->input('from_date') && !$request->input('to_date')) {
            $today = now()->format('Y-m-d');
            $request->merge(['date_range' => $today . ' - ' . $today]);
        }

        $departuresQuery = DelegateTransport::where('type', 'departure')
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
            });

        $this->applyTransportFilters($departuresQuery, $request, $currentEventId);

        $limit = $request->limit ? $request->limit : 20;

        $departures = $departuresQuery->orderBy('date_time', 'asc')
            ->get()
            ->sortBy(function ($transport) use ($now, $oneHourAgo, $oneHourFromNow) {
                $scheduledTime = \Carbon\Carbon::parse($transport->date_time);
                $isInCriticalWindow = $scheduledTime->between($oneHourAgo, $oneHourFromNow);
                $isOverdue = $scheduledTime->lt($now) && $transport->status !== 'departed';
                $isDepartingSoon = $scheduledTime->gt($now) && $scheduledTime->lte($now->copy()->addHour());
                $hasDeparted = $transport->status === 'departed';

                if ($hasDeparted) {
                    return [5, $scheduledTime]; // Lowest priority: All departed flights at the end
                } elseif ($isOverdue) {
                    return [0, $scheduledTime]; // Critical: Overdue departures (delayed flights)
                } elseif ($isDepartingSoon) {
                    return [1, $scheduledTime]; // High priority: Departing within 1 hour
                } elseif ($isInCriticalWindow) {
                    return [2, $scheduledTime]; // Medium priority: In time window
                } else {
                    return [3, $scheduledTime]; // Low priority: All other today's departures
                }
            })
            ->values();

        $departures = $departures->filter(function ($transport) use ($now) {
            $scheduledTime = \Carbon\Carbon::parse($transport->date_time);
            $oneHourAfterScheduled = $scheduledTime->copy()->addHour();

            // 1. Not yet departed
            // 2. Already departed but still within 1-hour display margin
            // 3. Overdue and not yet departed (critical)
            return $transport->status !== 'departed' ||
                $now->lte($oneHourAfterScheduled) ||
                $scheduledTime->lt($now->copy()->subHour());
        });

        $groupedDepartures = $this->groupTransports($departures);

        $currentPage = $request->input('page', 1);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($groupedDepartures)->forPage($currentPage, $limit),
            count($groupedDepartures),
            $limit,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => view('shared-pages.departures.table', compact('paginator', 'groupedDepartures'))->render()
            ]);
        }

        return view('admin.departures.index', compact('paginator', 'groupedDepartures'));
    }


    protected function applyTransportFilters($query, $request, $currentEventId)
    {
        if ($searchKey = $request->input('search_key')) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('flight_no', 'like', "%{$searchKey}%")
                    ->orWhere('flight_name', 'like', "%{$searchKey}%")
                    ->orWhereHas('delegate', function ($delegateQuery) use ($searchKey) {
                        $delegateQuery->where('name_en', 'like', "%{$searchKey}%")
                            ->orWhere('name_ar', 'like', "%{$searchKey}%")
                            ->orWhereHas('delegation', function ($delegationQuery) use ($searchKey) {
                                $delegationQuery->where('code', 'like', "%{$searchKey}%");
                            });
                    });
            });
        }

        if ($eventId = $request->input('event_id')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($eventId) {
                $delegationQuery->where('event_id', $eventId);
            });
        }

        if ($dateRange = $request->input('date_range')) {
            $dates = explode(' - ', $dateRange);

            if (count($dates) === 2) {
                $fromDate = trim($dates[0]);
                $toDate   = trim($dates[1]);

                if (preg_match('/^\d{2}-\d{2}-\d{4}/', $fromDate)) {
                    $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', substr($fromDate, 0, 10))->format('Y-m-d');
                }

                if (preg_match('/^\d{2}-\d{2}-\d{4}/', $toDate)) {
                    $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', substr($toDate, 0, 10))->format('Y-m-d');
                }

                $query->whereDate('date_time', '>=', $fromDate)
                    ->whereDate('date_time', '<=', $toDate);
            }
        } else {
            if ($fromDate = $request->input('from_date')) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}/', $fromDate)) {
                    $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $fromDate)->format('Y-m-d');
                }
                $query->whereDate('date_time', '>=', $fromDate);
            }

            if ($toDate = $request->input('to_date')) {
                if (preg_match('/^\d{2}-\d{2}-\d{4}/', $toDate)) {
                    $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $toDate)->format('Y-m-d');
                }
                $query->whereDate('date_time', '<=', $toDate);
            }
        }

        if ($invitation_from = $request->input('invitation_from')) {
            $query->whereHas('delegate.delegation', function ($delegationQuery) use ($invitation_from) {
                $delegationQuery->where('invitation_from_id', $invitation_from);
            });
        }

        if ($continentIds = $request->input('continent_id')) {
            if (is_array($continentIds)) {
                $query->whereHas('delegate.delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->whereIn('continent_id', $continentIds);
                });
            } else {
                $query->whereHas('delegate.delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->where('continent_id', $continentIds);
                });
            }
        }

        if ($countryIds = $request->input('country_id')) {
            if (is_array($countryIds)) {
                $query->whereHas('delegate.delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->whereIn('country_id', $countryIds);
                });
            } else {
                $query->whereHas('delegate.delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->where('country_id', $countryIds);
                });
            }
        }

        if ($airportIds = $request->input('airport_id')) {
            if (is_array($airportIds)) {
                $query->whereIn('airport_id', $airportIds);
            } else {
                $query->where('airport_id', $airportIds);
            }
        }

        if ($statuses = $request->input('status')) {
            if (is_array($statuses)) {
                $query->whereIn('status', $statuses);
            } else {
                $query->where('status', $statuses);
            }
        }
    }

    public function updateTravel(Request $request, DelegateTransport $transport)
    {
        $transportIds = $request->input('ids');

        if ($transportIds && is_array($transportIds)) {
            $transports = DelegateTransport::whereIn('id', $transportIds)->get();
        } else {
            $transports = collect([$transport]);
        }

        $validated = $request->validate([
            'airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'flight_no' => 'nullable|string|max:255',
            'flight_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'status' => 'nullable|string|max:255',
        ], [
            'airport_id.exists' => __db('airport_id_exists'),
            'flight_no.max' => __db('flight_no_max', ['max' => 255]),
            'flight_name.max' => __db('flight_name_max', ['max' => 255]),
            'date_time.date' => __db('date_time_date'),
        ]);

        $validated['date_time'] = Carbon::parse($validated['date_time'])->format('Y-m-d H:i:s');

        $relationsToCompare = [
            'airport_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
        ];

        $firstTransport = $transports->first();
        $confirmationResult = $this->processUpdate($request, $firstTransport, $validated, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        try {
            foreach ($transports as $t) {
                $t->update($dataToSave);

                $this->delegationStatusService->updateAllStatus($t->delegate);
            }

            if ($transports->isNotEmpty()) {
                $this->delegationStatusService->updateDelegationParticipationStatus($transports->first()->delegate->delegation);
            }

            if ($request->has('_is_confirmed') && $request->has('changed_fields_json')) {
                $changes = json_decode($request->input('changed_fields_json'), true);
                if (!empty($changes)) {
                    $this->logActivity(
                        module: 'Travel',
                        action: 'update',
                        model: $firstTransport,
                        fieldsToNotify: $fieldsToNotify,
                    );
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Record updated successfully.', 'redirect_url' => route('delegations.edit', $transports->first()->delegate->delegation_id ?? $transport->delegate->delegation_id)]);
        } catch (\Throwable $e) {
            Log::error('Travel update failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'transport_id' => $transport->id,
                'validated_data' => $validated,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to update record.'], 500);
        }
    }


    public function getTravelDetails(DelegateTransport $transport)
    {
        return response()->json([
            'success' => true,
            'data' => $transport,
        ]);
    }

    public function addTravel($id, Request $request)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'attachments',
            'delegates.delegateTransports.airport'
        ])->findOrFail($id);

        $showArrival = $request->query('showArrival');
        $showDeparture = $request->query('showDeparture');

        if (!$showArrival && !$showDeparture) {
            $showArrival = true;
            $showDeparture = true;
        } else {
            $showArrival = (bool) $showArrival;
            $showDeparture = (bool) $showDeparture;
        }

        $delegates = $delegation->delegates;

        return view('admin.delegations.add-travel', compact('delegation', 'delegates', 'showArrival', 'showDeparture'));
    }

    public function addDelegate($id)
    {
        $delegation = \App\Models\Delegation::with([
            'invitationFrom',
            'continent',
            'country',
            'invitationStatus',
            'participationStatus',
            'delegates',
        ])->findOrFail($id);

        $otherMembers = OtherInterviewMember::all();
        $delegate = new Delegate();
        // return response()->json([
        //     'delegation' => $delegation,
        // ]);

        return view('admin.delegations.add-delegate', compact('delegation', 'otherMembers', 'delegate'));
    }

    public function editDelegate(Delegation $delegation, Delegate $delegate)
    {

        // return response()->json([
        //     'delegate' => $delegate,
        //     'delegation' => $delegation
        // ]);

        return view('admin.delegations.edit-delegate', [
            'delegation' => $delegation,
            'delegate' => $delegate,
        ]);
    }

    public function addInterview(Delegation $delegation)
    {

        $currentEventId = session('current_event_id', getDefaultEventId());

        $otherMembers = OtherInterviewMember::where('event_id', $currentEventId)->where('status', 1)->get();

        return view('admin.delegations.add-interview', [
            'delegation' => $delegation,
            'interview' => new Interview(),
            'otherMembers' => $otherMembers,
        ]);
    }

    public function editInterview(Delegation $delegation, Interview $interview)
    {
        if ($interview->delegation_id !== $delegation->id) {
            abort(404, 'Interview not found for this delegation.');
        }

        $currentEventId = session('current_event_id', getDefaultEventId());

        $otherMembers = OtherInterviewMember::where('event_id', $currentEventId)->where('status', 1)->get();

        $toDelegationMembers = [];

        if ($interview->type === 'del_del' && $interview->interviewWithDelegation) {
            $toDelegationMembers = $interview->interviewWithDelegation->delegates()->get();
        }
        return view('admin.delegations.edit-interview', [
            'delegation' => $delegation,
            'interview' => $interview,
            'otherMembers' => $otherMembers,
            'toDelegationMembers' => $toDelegationMembers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invitation_from_id' => 'required|exists:dropdown_options,id',
            'continent_id' => 'required|exists:dropdown_options,id',
            'country_id' => 'required',
            'invitation_status_id' => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1' => 'nullable|string',
            'note2' => 'nullable|string',
            'delegates' => 'nullable|array',
            'delegates.*.tmp_id' => 'required_with:delegates',
            'delegates.*.title_en' => 'nullable|string',
            'delegates.*.title_ar' => 'nullable|string',
            'delegates.*.name_en' => 'nullable|string|required_without:delegates.*.name_ar',
            'delegates.*.name_ar' => 'nullable|string|required_without:delegates.*.name_en',
            'delegates.*.designation_en' => 'nullable|string',
            'delegates.*.designation_ar' => 'nullable|string',
            'delegates.*.gender_id' => 'required_with:delegates|exists:dropdown_options,id',
            'delegates.*.parent_id' => 'nullable|exists:delegates,id',
            'delegates.*.relationship' => 'nullable|string',
            'delegates.*.internal_ranking_id' => 'nullable|string',
            'delegates.*.note' => 'nullable|string',
            'delegates.*.team_head' => 'nullable|boolean',
            'delegates.*.accommodation' => 'nullable|boolean',
            'delegates.*.badge_printed' => 'nullable|boolean',
            'attachments' => 'nullable|array',
            'attachments.*.title_id' => 'nullable|string',
            'attachments.*.file' => 'nullable|file|max:5120',
            'attachments.*.document_date' => 'nullable|date',
        ], [
            'invitation_from_id.required' => __db('invitation_from_id_required'),
            'invitation_from_id.exists' => __db('invitation_from_id_exists'),
            'continent_id.required' => __db('continent_id_required'),
            'continent_id.exists' => __db('continent_id_exists'),
            'country_id.required' => __db('country_id_required'),
            'country_id.exists' => __db('country_id_exists'),
            'invitation_status_id.required' => __db('invitation_status_id_required'),
            'invitation_status_id.exists' => __db('invitation_status_id_exists'),
            'participation_status_id.required' => __db('participation_status_id_required'),
            'participation_status_id.exists' => __db('participation_status_id_exists'),
            'delegates.*.tmp_id.required_with' => __db('delegates_tmp_id_required_with'),
            'delegates.*.name_en.required_without' => __db('either_english_name_or_arabic_name'),
            'delegates.*.name_ar.required_without' => __db('either_english_name_or_arabic_name'),
            'delegates.*.gender_id.required_with' => __db('delegates_gender_id_required_with'),
            'delegates.*.gender_id.exists' => __db('delegates_gender_id_exists'),
            'delegates.*.parent_id.exists' => __db('delegates_parent_id_exists'),
            'attachments.*.title_id.exists' => __db('attachments_title_id_exists'),
            'attachments.*.file.file' => __db('attachments_file_file'),
            'attachments.*.file.max' => __db('attachments_file_max'),
            'attachments.*.document_date.date' => __db('attachments_document_date_date'),
        ]);

        DB::beginTransaction();

        try {
            $delegation = Delegation::create([
                'invitation_from_id' => $validated['invitation_from_id'],
                'continent_id' => $validated['continent_id'],
                'country_id' => $validated['country_id'],
                'invitation_status_id' => $validated['invitation_status_id'],
                'participation_status_id' => $validated['participation_status_id'],
                'note1' => $validated['note1'] ?? null,
                'note2' => $validated['note2'] ?? null,
            ]);

            if (!empty($validated['delegates'])) {

                foreach ($validated['delegates'] as $delegateData) {
                    $tmpId = $delegateData['tmp_id'];

                    unset($delegateData['parent_id']);

                    $delegateData['delegation_id'] = $delegation->id;
                    $delegateData['team_head'] = !empty($delegateData['team_head']);
                    $delegateData['badge_printed'] = !empty($delegateData['badge_printed']);
                    $delegateData['accommodation'] = !empty($delegateData['accommodation']);

                    $createdDelegate = $delegation->delegates()->create($delegateData);
                    $this->delegationStatusService->updateAllStatus($createdDelegate);

                    $tmpIdToDbId[$tmpId] = $createdDelegate->id;
                }

                foreach ($validated['delegates'] as $delegateData) {
                    if (!empty($delegateData['parent_id']) && isset($tmpIdToDbId[$delegateData['parent_id']])) {
                        $parentDbId = $tmpIdToDbId[$delegateData['parent_id']];
                        $delegateTmpId = $delegateData['tmp_id'];

                        $delegate = $delegation->delegates()->where('id', $tmpIdToDbId[$delegateTmpId])->first();
                        if ($delegate) {
                            $delegate->parent_id = $parentDbId;
                            $delegate->save();
                        }
                    }
                }
            }

            if ($request->has('attachments')) {
                foreach ($request->attachments as $idx => $attachment) {
                    if ($request->file("attachments.$idx.file")) {
                        $file = $request->file("attachments.$idx.file");
                        $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->code, 'files') ?? '';
                        $delegation->attachments()->create([
                            'title_id' => $attachment['title_id'],
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'document_date' => $attachment['document_date'] ?? now()->format('Y-m-d'),
                        ]);
                    }
                }
            }

            DB::commit();

            $this->logActivity(
                module: 'Delegation',
                action: 'create',
                model: $delegation,
                delegationId: $delegation->id
            );

            if ($request->has('submit_exit')) {
                return redirect()->route('delegations.index')->with('success', __db('delegation_created'));
            } elseif ($request->has('submit_add_interview')) {
                return redirect()->route('delegations.addInterview', ['delegation' => $delegation]);
            } elseif ($request->has('submit_add_travel')) {
                return redirect()->route('delegations.addTravel', ['id' => $delegation->id]);
            }

            return redirect()->route('delegations.index')->with('success', __db('delegation_created'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delegation creation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'validated_data' => $validated,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->withErrors(['error' => 'Failed to create delegation. Please check all required fields and try again.'])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $delegation = Delegation::findOrFail($id);

        $validated = $request->validate([
            'invitation_from_id' => 'required|exists:dropdown_options,id',
            'continent_id' => 'required|exists:dropdown_options,id',
            'country_id' => 'required',
            'invitation_status_id' => 'required|exists:dropdown_options,id',
            'participation_status_id' => 'required|exists:dropdown_options,id',
            'note1' => 'nullable|string',
            'note2' => 'nullable|string',
        ], [
            'invitation_from_id.required' => __db('invitation_from_id_required'),
            'invitation_from_id.exists' => __db('invitation_from_id_exists'),
            'continent_id.required' => __db('continent_id_required'),
            'continent_id.exists' => __db('continent_id_exists'),
            'country_id.required' => __db('country_id_required'),
            'country_id.exists' => __db('country_id_exists'),
            'invitation_status_id.required' => __db('invitation_status_id_required'),
            'invitation_status_id.exists' => __db('invitation_status_id_exists'),
            'participation_status_id.required' => __db('participation_status_id_required'),
            'participation_status_id.exists' => __db('participation_status_id_exists'),
        ]);

        $relationsToCompare = [
            'invitation_from_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'continent_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'country_id' => [
                'display_with' => [
                    'model' => \App\Models\Country::class,
                    'key' => 'id',
                    'label' => 'name',
                ],
            ],
            'invitation_status_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'participation_status_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
        ];

        $confirmationResult = $this->processUpdate($request, $delegation, $validated, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        try {
            DB::beginTransaction();

            $delegation->update([
                'invitation_from_id' => $dataToSave['invitation_from_id'],
                'continent_id' => $dataToSave['continent_id'],
                'country_id' => $dataToSave['country_id'],
                'invitation_status_id' => $dataToSave['invitation_status_id'],
                'participation_status_id' => $dataToSave['participation_status_id'],
                'note1' => $dataToSave['note1'] ?? null,
                'note2' => $dataToSave['note2'] ?? null,
            ]);

            if ($delegation->shouldUnassignServices()) {

                $delegation->escorts()->updateExistingPivot($delegation->escorts->pluck('id')->toArray(), ['status' => 0]);

                $delegation->drivers()->updateExistingPivot($delegation->drivers->pluck('id')->toArray(), ['status' => 0]);

                $delegation->interviews()->delete();

                \App\Models\RoomAssignment::where('delegation_id', $delegation->id)->update(['active_status' => 0]);
            }

            DB::commit();

            if ($request->has('changed_fields_json')) {
                $changes = json_decode($request->input('changed_fields_json'), true);
                if (!empty($changes)) {
                    $this->logActivity(
                        module: 'Delegation',
                        action: 'update',
                        model: $delegation,
                        changedFields: $changes,
                        delegationId: $delegation->id,
                        fieldsToNotify: $fieldsToNotify
                    );
                }
            }

            if (!empty($fieldsToNotify)) {
                Log::info('Admin chose to notify about these delegation changes: ' . implode(', ', $fieldsToNotify));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Delegation updated successfully.',
                'redirect_url' => route('delegations.edit', $delegation->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delegation update failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'delegation_id' => $id,
                'validated_data' => $validated,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->withErrors(['error' => 'Failed to update delegation. Please check all required fields and try again.'])->withInput();
        }
    }

    public function updateAttachments(Request $request, $delegationId)
    {
        $delegation = Delegation::with('attachments')->findOrFail($delegationId);

        $validatedData = $request->validate([
            'attachments' => ['required', 'array'],
            'attachments.*.id' => ['nullable', 'exists:delegation_attachments,id'],
            'attachments.*.title_id' => ['required', 'exists:dropdown_options,id'],
            'attachments.*.document_date' => ['nullable', 'date'],
            'attachments.*.file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
        ], [
            'attachments.required' => __db('attachments_required'),
            'attachments.array' => __db('attachments_array'),
            'attachments.*.id.exists' => __db('delegation_attachments_id_exists'),
            'attachments.*.title_id.required' => __db('attachments_title_id_exists'),
            'attachments.*.title_id.exists' => __db('attachments_title_id_exists'),
            'attachments.*.document_date.date' => __db('attachments_document_date_date'),
            'attachments.*.file.file' => __db('attachments_file_file'),
        ]);

        $inputAttachments = $validatedData['attachments'];

        foreach ($inputAttachments as $idx => $attachmentData) {
            if (!empty($attachmentData['id']) && (!empty($attachmentData['deleted']) && $attachmentData['deleted'])) {
                $att = $delegation->attachments()->find($attachmentData['id']);
                if ($att) {
                    if ($att->file_path && Storage::disk('public')->exists($att->file_path)) {
                        Storage::disk('public')->delete($att->file_path);
                    }
                    $att->delete();
                }
                continue;
            }

            $data = [
                'title_id' => $attachmentData['title_id'],
                'document_date' => $attachmentData['document_date'] ?? now()->format('Y-m-d'),
            ];

            if ($request->hasFile("attachments.$idx.file")) {
                $file = $request->file("attachments.$idx.file");

                $path = storeUploadedFileToModuleFolder($file, 'delegations', $delegation->code, 'files') ?? '';

                $data['file_path'] = $path;
                $data['file_name'] = $file->getClientOriginalName();

                if (!empty($attachmentData['id'])) {
                    $oldAtt = $delegation->attachments()->find($attachmentData['id']);
                    if ($oldAtt && $oldAtt->file_path && Storage::disk('public')->exists($oldAtt->file_path)) {
                        Storage::disk('public')->delete($oldAtt->file_path);
                    }
                }
            }

            if (!empty($attachmentData['id'])) {
                $att = $delegation->attachments()->find($attachmentData['id']);
                if ($att) {
                    $att->update($data);
                }
            } else {
                $delegation->attachments()->create($data);
            }
        }

        return redirect()
            ->route('delegations.edit', $delegationId)
            ->with('success', __db('attachments_updated_successfully'));
    }

    public function destroyAttachment($id)
    {
        $attachment = DelegationAttachment::findOrFail($id);

        // Log attachment deletion activity before deletion
        $this->logActivity(
            module: 'Delegation',
            submodule: 'attachment',
            action: 'delete',
            model: $attachment,
            submoduleId: $attachment->id,
            delegationId: $attachment->delegation_id
        );

        if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()->back()->with('success', __db('attachment_deleted_successfully'));
    }

    public function storeTravel(Request $request, $delegationId)
    {
        $delegation = Delegation::findOrFail($delegationId);

        $validated = $request->validate([
            'delegate_ids' => 'required|array|min:1',
            'delegate_ids.*' => 'integer|exists:delegates,id',
            'arrival.mode' => 'nullable|string|in:flight,land,sea',
            'arrival.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'arrival.flight_no' => 'nullable|string|max:255',
            'arrival.flight_name' => 'nullable|string|max:255',
            'arrival.date_time' => 'nullable|date',
            'arrival.status' => 'nullable|string|max:255',
            'arrival.comment' => 'nullable|string',
            'departure.mode' => 'nullable|string|in:flight,land,sea',
            'departure.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'departure.flight_no' => 'nullable|string|max:255',
            'departure.flight_name' => 'nullable|string|max:255',
            'departure.date_time' => 'nullable|date|after:arrival.date_time',
            'departure.status' => 'nullable|string|max:255',
            'departure.comment' => 'nullable|string',
        ],  [
            'delegate_ids.required' => __db('delegate_ids_required'),
            'delegate_ids.array' => __db('delegate_ids_array'),
            'delegate_ids.min' => __db('delegate_ids_min'),
            'delegate_ids.*.integer' => __db('delegate_ids_integer'),
            'delegate_ids.*.exists' => __db('delegate_ids_exists'),

            'arrival.mode.in' => __db('arrival_mode_in'),
            'arrival.airport_id.integer' => __db('arrival_airport_id_integer'),
            'arrival.airport_id.exists' => __db('arrival_airport_id_exists'),
            'arrival.flight_no.max' => __db('arrival_flight_no_max'),
            'arrival.flight_name.max' => __db('arrival_flight_name_max'),
            'arrival.date_time.date' => __db('arrival_date_time_date'),
            'arrival.status.max' => __db('arrival_status_max'),
            'arrival.comment.string' => __db('arrival_comment_string'),

            'departure.mode.in' => __db('departure_mode_in'),
            'departure.airport_id.integer' => __db('departure_airport_id_integer'),
            'departure.airport_id.exists' => __db('departure_airport_id_exists'),
            'departure.flight_no.max' => __db('departure_flight_no_max'),
            'departure.flight_name.max' => __db('departure_flight_name_max'),
            'departure.date_time.date' => __db('departure_date_time_date'),
            'departure.date_time.after' => __db('departure_date_time_after'),
            'departure.status.max' => __db('departure_status_max'),
            'departure.comment.string' => __db('departure_comment_string'),
        ]);

        DB::beginTransaction();

        try {

            foreach ($validated['delegate_ids'] as $delegateId) {
                $delegate = $delegation->delegates()->findOrFail($delegateId);

                if (isset($validated['arrival']['date_time']) && $validated['arrival']['date_time']) {
                    $this->delegationStatusService->syncTransportInfo($delegate, $validated['arrival'], 'arrival');
                }

                if (isset($validated['departure']['date_time']) && $validated['departure']['date_time']) {
                    $this->delegationStatusService->syncTransportInfo($delegate, $validated['departure'], 'departure');
                }

                $this->delegationStatusService->updateAllStatus($delegate);
            }

            DB::commit();

            $this->logActivity(
                module: 'Delegation',
                submodule: 'travel',
                action: 'create',
                model: $delegation,
                submoduleId: $delegation->id,
                delegationId: $delegation->id
            );

            if ($request->has('submit_exit')) {
                return redirect()->route('delegations.edit', ['id' => $delegation->id])->with('success', __db('travel') . " " . __db("created_successfully"));
            } elseif ($request->has('submit_add_departure')) {
                return redirect()->route('delegations.addTravel', ['id' => $delegation->id, 'showDeparture' => '1']);
            } elseif ($request->has('submit_add_arrival')) {
                return redirect()->route('delegations.addTravel', ['id' => $delegation->id, 'showArrival' => '1']);
            } elseif ($request->has('submit_add_interview')) {
                return redirect()->route('delegations.addInterview', ['delegation' => $delegation,]);
            } elseif ($request->has('submit_add_transport')) {
                return redirect()->back()->with('success', __db('travel') . " " . __db("created_successfully"));
            }

            return redirect()
                ->route('delegations.show', $delegationId)
                ->with('success', __db('travel_details_assigned_successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Failed to save travel details: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function storeOrUpdateInterview(Request $request, Delegation $delegation, Interview $interview = null)
    {


        $isEditMode = $interview && $interview->exists;

        $validator = Validator::make($request->all(), [
            'from_delegate_ids' => 'required|array|min:1',
            'from_delegate_ids.*' => 'integer|exists:delegates,id',
            'date_time' => 'required|date',
            'interview_type' => ['required', Rule::in(['delegation', 'other'])],
            'interview_with_delegation_code' => 'required_if:interview_type,delegation|nullable|string|exists:delegations,code',
            'to_delegate_id' => 'required_if:interview_type,delegation|nullable|integer|exists:delegates,id',
            'other_member_id' => 'required_if:interview_type,other|nullable|integer|exists:other_interview_members,id',
            'status_id' => 'required|integer|exists:dropdown_options,id',
            // 'comment' => 'nullable|string|max:5000',
        ], [
            'from_delegate_ids.required' => __db('from_delegate_ids_required'),
            'from_delegate_ids.array' => __db('from_delegate_ids_array'),
            'from_delegate_ids.min' => __db('from_delegate_ids_min'),
            'from_delegate_ids.*.exists' => __db('from_delegate_ids_exists'),
            'date_time.required' => __db('date_time_required'),
            'date_time.date' => __db('date_time_date'),
            'interview_type.required' => __db('interview_type_required'),
            'interview_type.in' => __db('interview_type_in'),
            'interview_with_delegation_code.required_if' => __db('interview_with_delegation_code_required_if'),
            'interview_with_delegation_code.exists' => __db('interview_with_delegation_code_exists'),
            'to_delegate_id.required_if' => __db('to_delegate_id_required_if'),
            'to_delegate_id.exists' => __db('to_delegate_id_exists'),
            'other_member_id.required_if' => __db('other_member_id_required_if'),
            'other_member_id.exists' => __db('other_member_id_exists'),
            'status_id.required' => __db('status_id_required'),
            'status_id.integer' => __db('status_id_integer'),
            'status_id.exists' => __db('status_id_exists'),
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        if ($validated['interview_type'] === 'delegation') {
            $validated['other_member_id'] = null;
        } else {
            $validated['interview_with_delegation_code'] = null;
            $validated['to_delegate_id'] = null;
        }

        $interviewWithDelegation = $validated['interview_with_delegation_code']
            ? Delegation::where('code', $validated['interview_with_delegation_code'])->first()
            : null;

        $dataToProcess = [
            'delegation_id' => $delegation->id,
            'date_time' => Carbon::parse($validated['date_time'])->format('Y-m-d H:i:s'),
            'from_delegate_ids' => $validated['from_delegate_ids'],
            'type' => $validated['interview_type'] === 'delegation' ? 'del_del' : 'del_others',
            'interview_with' => $validated['interview_type'] === 'delegation' ? ($interviewWithDelegation->id ?? null) : null,
            'to_delegate_id' => $validated['to_delegate_id'],
            'other_member_id' => $validated['other_member_id'],
            'status_id' => $validated['status_id'],
            'comment' => $validated['comment'] ?? null,
        ];

        if (!$isEditMode) {
            try {
                DB::beginTransaction();
                $newInterview = Interview::create($dataToProcess);
                $newInterview->interviewMembers()->delete();

                foreach ($validated['from_delegate_ids'] as $fromId) {
                    $newInterview->interviewMembers()->create([
                        'member_id' => $fromId,
                        'type' => 'from'
                    ]);
                }

                if ($validated['interview_type'] === 'delegation' && !empty($validated['to_delegate_id'])) {
                    $newInterview->interviewMembers()->create([
                        'member_id' => $validated['to_delegate_id'],
                        'type' => 'to'
                    ]);
                }

                DB::commit();

                $this->logActivity(
                    module: 'Delegation',
                    submodule: 'interview',
                    action: 'create',
                    model: $newInterview,
                    submoduleId: $newInterview->id,
                    delegationId: $delegation->id
                );

                return response()->json([
                    'status' => 'success',
                    'message' => __db('interview_created_successfully'),
                    'redirect_url' => route('delegations.edit', $delegation->id)
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Interview Create Failed: ' . $e->getMessage());
                return response()->json(['message' => 'Error creating interview.', 'errors' => [$e->getMessage()]], 500);
            }
        }

        $mainModelData = Arr::except($dataToProcess, ['delegation_id']);

        $relationsToCompare = [
            'from_delegate_ids' => [
                'relation' => 'fromMembers',
                'type' => 'list',
                'column' => 'member_id',
                'display_with' => [
                    'model' => \App\Models\Delegate::class,
                    'key' => 'id',
                    'label' => 'name_en',
                ],
            ],
            'to_delegate_id' => [
                'relation' => 'toMembers',
                'type' => 'single',
                'column' => 'member_id',
                'display_with' => [
                    'model' => \App\Models\Delegate::class,
                    'key' => 'id',
                    'label' => 'name_en',
                ],
            ],

            'other_member_id' => [
                'relation' => 'otherMember',
                'type' => 'single',
                'column' => 'id',
                'display_with' => [
                    'model' => \App\Models\OtherInterviewMember::class,
                    'key' => 'id',
                    'label' => 'name_en',
                ],
            ],

            'status_id' => [
                'relation' => 'status',
                'type' => 'single',
                'column' => 'id',
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
        ];

        $confirmationResult = $this->processUpdate($request, $interview, $mainModelData, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'] ?? [];

        try {
            DB::beginTransaction();

            $interview->update($dataToSave);

            $interview->interviewMembers()->where('type', 'from')->delete();

            foreach ($validated['from_delegate_ids'] as $fromId) {
                $interview->interviewMembers()->create([
                    'member_id' => $fromId,
                    'type' => 'from',
                ]);
            }

            $interview->interviewMembers()->where('type', 'to')->delete();

            if ($validated['interview_type'] === 'delegation' && !empty($validated['to_delegate_id'])) {
                $interview->interviewMembers()->create([
                    'member_id' => $validated['to_delegate_id'],
                    'type' => 'to',
                ]);
            }

            DB::commit();

            if ($request->has('changed_fields_json')) {
                $this->logActivity(
                    module: 'Delegation',
                    submodule: 'interview',
                    action: 'update',
                    model: $interview,
                    changedFields: json_decode($request->input('changed_fields_json'), true),
                    submoduleId: $interview->id,
                    delegationId: $delegation->id,
                    fieldsToNotify: $fieldsToNotify
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Interview updated successfully.',
                'redirect_url' => route('delegations.show', $delegation->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Interview Update Failed: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating interview.'], 500);
        }
    }

    public function storeOrUpdateDelegate(Request $request, Delegation $delegation, Delegate $delegate = null)
    {
        $isEditMode = $delegate && $delegate->exists;

        $validator = Validator::make($request->all(), [
            'title_en' => 'nullable|string|required_without:title_ar',
            'title_ar' => 'nullable|string|required_without:title_en',
            'name_en' => 'nullable|string|required_without:name_ar',
            'name_ar' => 'nullable|string|required_without:name_en',
            'designation_en' => 'nullable|string',
            'designation_ar' => 'nullable|string',
            'gender_id' => 'required|exists:dropdown_options,id',
            'parent_id' => 'nullable|exists:delegates,id',
            'relationship_id' => 'nullable|string|exists:dropdown_options,id',
            'internal_ranking_id' => 'nullable|string|exists:dropdown_options,id',
            'note' => 'nullable|string',
            'team_head' => 'nullable|boolean',
            'badge_printed' => 'nullable|boolean',
            'accommodation' => 'nullable|boolean',
            'arrival.mode' => 'nullable|string|in:flight,land,sea',
            'arrival.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'arrival.flight_no' => 'nullable|string|max:255',
            'arrival.flight_name' => 'nullable|string|max:255',
            'arrival.date_time' => 'nullable|string',
            'departure.date_time' => 'nullable|string',
            'arrival.status' => 'nullable|string|max:255',
            'arrival.comment' => 'nullable|string',
            'departure.mode' => 'nullable|string|in:flight,land,sea',
            'departure.airport_id' => 'nullable|integer|exists:dropdown_options,id',
            'departure.flight_no' => 'nullable|string|max:255',
            'departure.flight_name' => 'nullable|string|max:255',
            'departure.status' => 'nullable|string|max:255',
            'departure.comment' => 'nullable|string',
        ], [
            'title_en.required_without' => __db('either_english_title_or_arabic_title'),
            'title_ar.required_without' => __db('either_english_title_or_arabic_title'),
            'name_en.required_without' => __db('either_english_name_or_arabic_name'),
            'name_ar.required_without' => __db('either_english_name_or_arabic_name'),
            'gender_id.required' => __db('gender_id_required'),
            'gender_id.exists' => __db('delegates_gender_id_exists'),
            'parent_id.exists' => __db('delegates_parent_id_exists'),
            'relationship_id.exists' => __db('relationship_id_exists'),
            'internal_ranking_id.exists' => __db('internal_ranking_id_exists'),
            'arrival.airport_id.exists' => __db('airport_id_exists'),
            'arrival.flight_no.max' => __db('flight_no_max', ['max' => 255]),
            'arrival.flight_name.max' => __db('flight_name_max', ['max' => 255]),
            'departure.airport_id.exists' => __db('airport_id_exists'),
            'departure.flight_no.max' => __db('flight_no_max', ['max' => 255]),
            'departure.flight_name.max' => __db('flight_name_max', ['max' => 255]),
        ]);

        $validator->after(function ($validator) use ($request) {
            $arrivalDate = $request->input('arrival.date_time');
            $departureDate = $request->input('departure.date_time');

            if (!empty($arrivalDate) && !empty($departureDate)) {
                $arrivalTimestamp = strtotime($arrivalDate);
                $departureTimestamp = strtotime($departureDate);

                if ($arrivalTimestamp === false || $departureTimestamp === false) {
                    $validator->errors()->add('arrival.date_time', __db('invalid_date_format'));
                    $validator->errors()->add('departure.date_time', __db('invalid_date_format'));
                } elseif ($departureTimestamp <= $arrivalTimestamp) {
                    $validator->errors()->add('departure.date_time', __db('departure_date_after_arrival_date'));
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $dataToProcess = $validated;
        $dataToProcess['team_head'] = $request->has('team_head');
        $dataToProcess['badge_printed'] = $request->has('badge_printed');
        $dataToProcess['accommodation'] = $request->has('accommodation');

        if (!$isEditMode) {
            try {
                DB::beginTransaction();

                $delegateDataForCreate = Arr::except($dataToProcess, ['arrival', 'departure']);
                $newDelegate = $delegation->delegates()->create($delegateDataForCreate);

                $this->delegationStatusService->syncTransportInfo($newDelegate, $dataToProcess['arrival'] ?? null, 'arrival');
                $this->delegationStatusService->syncTransportInfo($newDelegate, $dataToProcess['departure'] ?? null, 'departure');

                $this->delegationStatusService->updateAllStatus($newDelegate);

                DB::commit();

                $this->logActivity(
                    module: 'Delegation',
                    submodule: 'delegate',
                    action: 'create',
                    model: $newDelegate,
                    submoduleId: $newDelegate->id,
                    delegationId: $delegation->id
                );

                return response()->json([
                    'status' => 'success',
                    'message' => __db('delegate_created_successfully'),
                    'redirect_url' => route('delegations.edit', $delegation->id)
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Delegate Create Failed: ' . $e->getMessage() . ' on line ' . $e->getLine());
                return response()->json(['message' => 'A critical error occurred while creating the delegate.'], 500);
            }
        }

        $relationsToCompare = [
            'title_en' => [],
            'title_ar' => [],
            'gender_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'parent_id' => [
                'display_with' => [
                    'model' => \App\Models\Delegate::class,
                    'key' => 'id',
                    'label' => 'name_en',
                ],
            ],
            'relationship_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'internal_ranking_id' => [
                'display_with' => [
                    'model' => \App\Models\DropdownOption::class,
                    'key' => 'id',
                    'label' => 'value',
                ],
            ],
            'arrival' => [
                'relation' => 'delegateTransports',
                'find_by' => ['type' => 'arrival'],
                'display_with' => [
                    'airport_id' => [
                        'model' => \App\Models\DropdownOption::class,
                        'key' => 'id',
                        'label' => 'value',
                    ]
                ]
            ],
            'departure' => [
                'relation' => 'delegateTransports',
                'find_by' => ['type' => 'departure'],
                'display_with' => [
                    'airport_id' => [
                        'model' => \App\Models\DropdownOption::class,
                        'key' => 'id',
                        'label' => 'value',
                    ]
                ]
            ],
            "status" => []
        ];

        if (!isset($dataToProcess['arrival']['date_time']) || empty($dataToProcess['arrival']['date_time'])) {
            unset($dataToProcess['arrival']);
        }

        if (!isset($dataToProcess['departure']['date_time']) || empty($dataToProcess['departure']['date_time'])) {
            unset($dataToProcess['departure']);
        }

        $confirmationResult = $this->processUpdate($request, $delegate, $dataToProcess, $relationsToCompare);

        if ($confirmationResult instanceof \Illuminate\Http\JsonResponse) {
            return $confirmationResult;
        }

        $dataToSave = $confirmationResult['data'];
        $fieldsToNotify = $confirmationResult['notify'];

        try {
            DB::beginTransaction();

            if ($isEditMode) {
                $newAccommodation = $request->has('accommodation') ? 1 : 0;
                if ($newAccommodation == 0) {
                    if ($delegate->current_room_assignment_id) {
                        $oldAssignment = \App\Models\RoomAssignment::find($delegate->current_room_assignment_id);

                        if ($oldAssignment) {
                            $oldRoom = \App\Models\AccommodationRoom::find($oldAssignment->room_type_id);
                            if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                                $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                                $oldRoom->save();
                            }

                            $oldAssignment->active_status = 0;
                            $oldAssignment->save();
                        }

                        $delegate->current_room_assignment_id = null;
                        $delegate->save();
                    }
                }
            }

            $finalDelegateData = Arr::except($dataToSave, ['arrival', 'departure']);
            $delegate->update($finalDelegateData);

            $this->delegationStatusService->syncTransportInfo($delegate, $dataToSave['arrival'] ?? null, 'arrival');
            $this->delegationStatusService->syncTransportInfo($delegate, $dataToSave['departure'] ?? null, 'departure');

            $this->delegationStatusService->updateAllStatus($delegate);

            DB::commit();

            if ($request->has('changed_fields_json')) {
                $changes = json_decode($request->input('changed_fields_json'), true);
                if (!empty($changes)) {
                    $this->logActivity(
                        module: 'Delegation',
                        submodule: 'delegate',
                        action: 'update',
                        model: $delegate,
                        changedFields: $changes,
                        submoduleId: $delegate->id,
                        delegationId: $delegation->id,
                        fieldsToNotify: $fieldsToNotify
                    );
                }
            }

            if (!empty($fieldsToNotify)) {
                Log::info('Admin chose to notify about these changes: ' . implode(', ', $fieldsToNotify));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Delegate updated successfully.',
                'redirect_url' => route('delegations.edit', $delegation->id),
                'fields_to_notify' => $fieldsToNotify
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delegate Save/Update Failed: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json(['message' => 'A critical error occurred while saving.'], 500);
        }
    }


    public function destroyDelegate(Delegation $delegation, Delegate $delegate)
    {
        try {
            $this->logActivity(
                module: 'Delegation',
                submodule: 'delegate',
                action: 'delete',
                model: $delegate,
                submoduleId: $delegate->id,
                delegationId: $delegation->id
            );

            if ($delegate->current_room_assignment_id) {
                $oldAssignment = \App\Models\RoomAssignment::find($delegate->current_room_assignment_id);

                if ($oldAssignment) {
                    $oldRoom = \App\Models\AccommodationRoom::find($oldAssignment->room_type_id);
                    if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                        $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                        $oldRoom->save();
                    }

                    $oldAssignment->active_status = 0;
                    $oldAssignment->save();
                }
            }

            $delegate->delete();

            $this->delegationStatusService->updateDelegationParticipationStatus($delegation);

            return redirect()
                ->route('delegations.edit', $delegation->id)
                ->with('success', 'Delegate has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delegate Deletion Failed: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while deleting the delegate.');
        }
    }

    public function destroyInterview(Interview $interview)
    {
        try {
            $delegationId = $interview->delegation_id;

            $this->logActivity(
                module: 'Delegation',
                submodule: 'interview',
                action: 'delete',
                model: $interview,
                submoduleId: $interview->id,
                delegationId: $delegationId
            );

            $interview->interviewMembers()->delete();
            $interview->delete();

            return redirect()
                ->route('delegations.show', $delegationId)
                ->with('success', 'Interview deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Interview Delete Failed: ' . $e->getMessage());

            return redirect()
                ->route('delegations.show', $interview->delegation_id)
                ->with('error', 'Failed to delete interview.');
        }
    }

    public function searchByCode(Request $request)
    {
        $code = $request->query('code');

        $currentEventId = session('current_event_id', getDefaultEventId());
        $query = Delegation::query();

        // if (!$code) {
        //     return response()->json(['success' => false, 'message' => __db('code_required')]);
        // }

        if ($code) {
            $query = $query->where('code', $code);
        }

        $delegation = $query->with('delegates', 'country', 'continent')->where('event_id', $currentEventId)->first();

        if (!$delegation) {
            return response()->json(['success' => false, 'message' => __db('delegation_not_found')]);
        }

        $members = $delegation->delegates->map(fn($d) => [
            'id' => $d->id,
            'name_en' => $d->getTranslation('name'),
        ]);

        return response()->json([
            'success' => true,
            'members' => $members,
            'delegation' => $delegation
        ]);
    }

    public function search(Request $request)
    {
        $query = Delegation::query();

        $filterNonAssignableDeligations = true;

        $currentEventId = session('current_event_id', getDefaultEventId());

        $driverId = $request->input('driver_id');
        $escortId = $request->input('escort_id');


        $query->where('event_id', $currentEventId);

        if ($continentId = $request->query('continent_id')) {
            $query->where('continent_id', $continentId);
        }

        if ($countryId = $request->query('country_id')) {
            $query->where('country_id', $countryId);
        }

        if ($request->query('delegates') == '1') {
            $query->with(['invitationFrom', 'delegates']);
        } else {
            $query->with('invitationFrom');
        }

        if ($driverId) {
            $query->whereDoesntHave('drivers', function ($q) use ($driverId) {
                $q->where('delegation_drivers.driver_id', $driverId)
                    ->where('delegation_drivers.status', 1);
            });
        }

        if ($escortId) {
            $query->whereDoesntHave('escorts', function ($q) use ($escortId) {
                $q->where('delegation_escorts.escort_id', $escortId)
                    ->where('delegation_escorts.status', 1);
            });
        }

        if ($filterNonAssignableDeligations) {
            $query->whereHas('invitationStatus', function ($q) {
                $q->whereIn('code', self::ASSIGNABLE_STATUS_CODES);
            });
        }


        $delegations = $query->with('invitationFrom', 'country', 'continent')->get();

        return response()->json([
            'success' => true,
            'delegations' => $delegations->map(fn($d) => array_merge(
                $d->toArray(),
                [
                    'id' => $d->id,
                    'code' => $d->code,
                    'invitationFrom_value' => $d->invitationFrom->value ?? '',
                ]
            )),
        ]);
    }

    public function members($delegationId)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $delegation = Delegation::with('delegates')->where('event_id', $currentEventId)->findOrFail($delegationId);

        $members = $delegation->delegates->map(fn($d) => [
            'id' => $d->id,
            'name_en' => $d->getTranslation('name'),
        ]);

        return response()->json(['success' => true, 'members' => $members]);
    }

    private function groupTransports($transports)
    {
        $groups = [];

        foreach ($transports as $transport) {
            $key = $this->createGroupKey($transport);

            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'delegation' => $transport->delegate->delegation,
                    'date_time' => $transport->date_time,
                    'flight_no' => $transport->flight_no,
                    'flight_name' => $transport->flight_name,
                    'airport' => $transport->airport,
                    'status' => $transport->status,
                    'transports' => [],
                    'delegates' => [],
                ];
            }

            $groups[$key]['transports'][] = $transport;

            $delegateExists = false;
            foreach ($groups[$key]['delegates'] as $existingDelegate) {
                if ($existingDelegate->id === $transport->delegate->id) {
                    $delegateExists = true;
                    break;
                }
            }

            if (!$delegateExists) {
                $groups[$key]['delegates'][] = $transport->delegate;
            }
        }

        return array_values($groups);
    }

    private function createGroupKey($transport)
    {
        return md5(
            $transport->delegate->delegation_id .
                ($transport->date_time ?? '') .
                ($transport->flight_no ?? '') .
                ($transport->flight_name ?? '') .
                ($transport->airport_id ?? '') .
                ($transport->status ?? '')
        );
    }


    public function badgePrintedIndex(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = Delegate::with([
            'delegation.country',
            'delegation.continent',
            'delegation.invitationFrom',
            'delegation.invitationStatus'
        ])
            ->leftJoin('delegations', 'delegates.delegation_id', '=', 'delegations.id')
            ->leftJoin('dropdown_options as invitation_status', 'delegations.invitation_status_id', '=', 'invitation_status.id')
            ->leftJoin('countries as country_sort', 'delegations.country_id', '=', 'country_sort.id')
            ->leftJoin('dropdown_options as invitation_from_sort', 'delegations.invitation_from_id', '=', 'invitation_from_sort.id')

            ->where('delegations.event_id', $currentEventId)
            ->whereIn('invitation_status.code', \App\Models\Delegation::ASSIGNABLE_STATUS_CODES)

            ->select('delegates.*');

        if ($searchKey = $request->input('search_key')) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('delegates.name_en', 'like', "%{$searchKey}%")
                    ->orWhere('delegates.name_ar', 'like', "%{$searchKey}%")
                    ->orWhere('delegates.code', 'like', "%{$searchKey}%")
                    ->orWhere('delegations.code', 'like', "%{$searchKey}%");
            });
        }


        $badgePrintedFilter = $request->input('badge_printed');
        if ($badgePrintedFilter !== null) {
            if ($badgePrintedFilter == '1') {
                $query->where('delegates.badge_printed', true);
            } elseif ($badgePrintedFilter == '0') {
                $query->where('delegates.badge_printed', false);
            }
        }

        if ($continentIds = $request->input('continent_id')) {
            if (is_array($continentIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->whereIn('continent_id', $continentIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->where('continent_id', $continentIds);
                });
            }
        }

        if ($countryIds = $request->input('country_id')) {
            if (is_array($countryIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->whereIn('country_id', $countryIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->where('country_id', $countryIds);
                });
            }
        }

        if ($invitation_from = $request->input('invitation_from')) {
            if (is_array($invitation_from)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->whereIn('invitation_from_id', $invitation_from);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->where('invitation_from_id', $invitation_from);
                });
            }
        }

        $limit = $request->limit ? $request->limit : 20;

        $delegates = $query->orderBy('country_sort.sort_order', 'asc')
            ->orderBy('invitation_from_sort.sort_order', 'asc')
            ->orderBy('delegations.id', 'asc')
            ->orderBy('delegates.team_head', 'desc')
            ->orderBy('delegates.id', 'asc')
            ->paginate($limit);

        return view('admin.delegates.badge-printed-index', compact('delegates'));
    }

    public function updateBadgePrintedStatus(Request $request)
    {
        $validated = $request->validate([
            'delegate_id' => 'required|integer|exists:delegates,id',
            'badge_printed' => 'required|boolean',
        ]);

        try {
            $delegate = Delegate::findOrFail($validated['delegate_id']);
            $delegate->badge_printed = $validated['badge_printed'];
            $delegate->save();

            return response()->json([
                'status' => 'success',
                'message' => __db('badge_printed_status_updated_successfully')
            ]);
        } catch (\Exception $e) {
            Log::error('Badge Printed Status Update Failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => __db('badge_printed_status_update_failed')
            ], 500);
        }
    }

    public function exportNonBadgePrinted(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = Delegate::with([
            'delegation.country',
            'delegation.continent',
            'delegation.invitationFrom',
        ])
            ->where('badge_printed', false)
            ->whereHas('delegation', function ($delegationQuery) use ($currentEventId) {
                $delegationQuery->where('event_id', $currentEventId);
            })
            ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
            ->join('countries as country_sort', 'delegations.country_id', '=', 'country_sort.id')
            ->join('dropdown_options as invitation_from_sort', 'delegations.invitation_from_id', '=', 'invitation_from_sort.id')
            ->select('delegates.*');

        if ($continentIds = $request->input('continent_id')) {
            if (is_array($continentIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->whereIn('continent_id', $continentIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->where('continent_id', $continentIds);
                });
            }
        }

        if ($countryIds = $request->input('country_id')) {
            if (is_array($countryIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->whereIn('country_id', $countryIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->where('country_id', $countryIds);
                });
            }
        }

        if ($invitation_from = $request->input('invitation_from')) {
            if (is_array($invitation_from)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->whereIn('invitation_from_id', $invitation_from);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->where('invitation_from_id', $invitation_from);
                });
            }
        }

        $delegates = $query->orderBy('country_sort.sort_order', 'asc')
            ->orderBy('invitation_from_sort.sort_order', 'asc')
            ->orderBy('delegations.id', 'asc')
            ->orderBy('delegates.team_head', 'desc')
            ->orderBy('delegates.id', 'asc')
            ->get();

        return Excel::download(new \App\Exports\NonBadgePrintedDelegatesExport($delegates), 'non-badge-printed-delegates.xlsx');
    }

    public function exportBadgePrintedDelegates(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = Delegate::with([
            'delegation.country',
            'delegation.continent',
            'delegation.invitationFrom',
        ])
            ->where('badge_printed', true)
            ->whereHas('delegation', function ($delegationQuery) use ($currentEventId) {
                $delegationQuery->where('event_id', $currentEventId);
            })
            ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
            ->join('countries as country_sort', 'delegations.country_id', '=', 'country_sort.id')
            ->join('dropdown_options as invitation_from_sort', 'delegations.invitation_from_id', '=', 'invitation_from_sort.id')
            ->select('delegates.*');

        $badgePrintedFilter = $request->input('badge_printed');
        if ($badgePrintedFilter !== null) {
            if ($badgePrintedFilter == '1') {
                $query->where('delegates.badge_printed', true);
            } elseif ($badgePrintedFilter == '0') {
                $query->where('delegates.badge_printed', false);
            }
        }

        if ($continentIds = $request->input('continent_id')) {
            if (is_array($continentIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->whereIn('continent_id', $continentIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->where('continent_id', $continentIds);
                });
            }
        }

        if ($countryIds = $request->input('country_id')) {
            if (is_array($countryIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->whereIn('country_id', $countryIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->where('country_id', $countryIds);
                });
            }
        }

        if ($invitation_from = $request->input('invitation_from')) {
            if (is_array($invitation_from)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->whereIn('invitation_from_id', $invitation_from);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->where('invitation_from_id', $invitation_from);
                });
            }
        }

        $delegates = $query->orderBy('country_sort.sort_order', 'asc')
            ->orderBy('invitation_from_sort.sort_order', 'asc')
            ->orderBy('delegations.id', 'asc')
            ->orderBy('delegates.team_head', 'desc')
            ->orderBy('delegates.id', 'asc')
            ->get();

        $selectedDelegateIds = $request->input('delegate_ids');
        if ($selectedDelegateIds && is_array($selectedDelegateIds)) {
            $delegates = $delegates->whereIn('id', $selectedDelegateIds);

            if ($badgePrintedFilter == '0') {
                Delegate::whereIn('id', $selectedDelegateIds)->update(['badge_printed' => true]);
            }
        }

        if ($badgePrintedFilter == '0' || ($badgePrintedFilter === null && $delegates->first() && !$delegates->first()->badge_printed)) {
            return Excel::download(new \App\Exports\NonBadgePrintedDelegatesExport($delegates), 'non-badge-printed-delegates.xlsx');
        } else {
            return Excel::download(new \App\Exports\BadgePrintedDelegatesExport($delegates), 'badge-printed-delegates.xlsx');
        }
    }

    public function exportNonBadgePrintedDelegates(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $query = Delegate::with([
            'delegation.country',
            'delegation.continent',
            'delegation.invitationFrom',
        ])
            ->where('badge_printed', false)
            ->whereHas('delegation', function ($delegationQuery) use ($currentEventId) {
                $delegationQuery->where('event_id', $currentEventId);
            })
            ->join('delegations', 'delegates.delegation_id', '=', 'delegations.id')
            ->join('countries as country_sort', 'delegations.country_id', '=', 'country_sort.id')
            ->join('dropdown_options as invitation_from_sort', 'delegations.invitation_from_id', '=', 'invitation_from_sort.id')
            ->select('delegates.*');

        if ($continentIds = $request->input('continent_id')) {
            if (is_array($continentIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->whereIn('continent_id', $continentIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($continentIds) {
                    $delegationQuery->where('continent_id', $continentIds);
                });
            }
        }

        if ($countryIds = $request->input('country_id')) {
            if (is_array($countryIds)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->whereIn('country_id', $countryIds);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($countryIds) {
                    $delegationQuery->where('country_id', $countryIds);
                });
            }
        }

        if ($invitation_from = $request->input('invitation_from')) {
            if (is_array($invitation_from)) {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->whereIn('invitation_from_id', $invitation_from);
                });
            } else {
                $query->whereHas('delegation', function ($delegationQuery) use ($invitation_from) {
                    $delegationQuery->where('invitation_from_id', $invitation_from);
                });
            }
        }

        $delegates = $query->orderBy('country_sort.sort_order', 'asc')
            ->orderBy('invitation_from_sort.sort_order', 'asc')
            ->orderBy('delegations.id', 'asc')
            ->orderBy('delegates.team_head', 'desc')
            ->orderBy('delegates.id', 'asc')
            ->get();

        $selectedDelegateIds = $request->input('delegate_ids');
        if ($selectedDelegateIds && is_array($selectedDelegateIds)) {
            $delegates = $delegates->whereIn('id', $selectedDelegateIds);

            Delegate::whereIn('id', $selectedDelegateIds)->update(['badge_printed' => true]);
        }

        return Excel::download(new \App\Exports\NonBadgePrintedDelegatesExport($delegates), 'non-badge-printed-delegates.xlsx');
    }

    public function exportDelegations(Request $request)
    {
        return Excel::download(new DelegationExport, 'delegations.xlsx');
    }

    public function exportDelegates(Request $request)
    {
        return Excel::download(new DelegateExport, 'delegates.xlsx');
    }

    public function destroy(Delegation $delegation)
    {
        try {
            $delegation->interviews()->delete();
            $delegation->attachments()->delete();

            foreach ($delegation->delegates as $delegate) {
                if ($delegate->current_room_assignment_id) {
                    $oldAssignment = \App\Models\RoomAssignment::find($delegate->current_room_assignment_id);
                    // if ($oldAssignment) {
                    //     $oldRoom = \App\Models\AccommodationRoom::find($oldAssignment->room_type_id);
                    //     if ($oldRoom && $oldRoom->assigned_rooms > 0) {
                    //         $oldRoom->assigned_rooms = $oldRoom->assigned_rooms - 1;
                    //         $oldRoom->save();
                    //     }
                    //     $oldAssignment->active_status = 0;
                    //     $oldAssignment->save();
                    // }
                }
                $delegate->delete();
            }

            $delegation->delete();

            $this->logActivity(
                module: 'Delegation',
                action: 'delete-delegation',
                model: $delegation,
                delegationId: $delegation->id,
                message: [
                    'en' => auth()->user()->name . __db('delegation_deleted_notification') . $delegation->code . __db('all_assigned_escorts_drivers_hotels_freed'),
                    'ar' => auth()->user()->name . __db('delegation_deleted_notification') . $delegation->code . __db('all_assigned_escorts_drivers_hotels_freed')
                ]
            );

            return redirect()
                ->route('delegations.index')
                ->with('success', __db('delegation_deleted_successfully'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Delegation Deletion Failed: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while deleting the delegation.');
        }
    }
    public function importAttachments(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new DelegationAttachmentImport, $request->file('file'));

            return redirect()->route('delegations.index')
                ->with('success', __db('attachments_imported_successfully'));
        } catch (\Exception $e) {
            Log::error('Attachment Import Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', __db('attachment_import_failed') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function showImportForm()
    {
        return view('admin.delegations.import');
    }

    public function importDelegations(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $fileName = $request->file('file')->getClientOriginalName();
            Excel::import(new DelegationOnlyImport($fileName), $request->file('file'));

            return redirect()->route('admin.import-logs.index', ['import_type' => 'delegations'])
                ->with('success', __db('delegations_only_imported_successfully'));
        } catch (\Exception $e) {
            Log::error('Delegation Import Error: ' . $e->getMessage());
            return back()
                ->with('error', __db('delegation_import_failed') . ': ' . $e->getMessage());
        }
    }

    public function importDelegatesWithTravels(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $fileName = $request->file('file')->getClientOriginalName();
            Excel::import(new DelegateImport($fileName), $request->file('file'));

            return redirect()->route('admin.import-logs.index', ['import_type' => 'delegates'])
                ->with('success', __db('delegates_imported_successfully'));
        } catch (\Exception $e) {
            Log::error('Delegation Import Error: ' . $e->getMessage());
            return back()->with('error', __db('delegation_import_failed') . ': ' . $e->getMessage());
        }
    }
}
