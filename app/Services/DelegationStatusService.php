<?php

namespace App\Services;

use App\Models\Delegation;
use App\Models\Delegate;
use Carbon\Carbon;

class DelegationStatusService
{
    public function updateDelegateParticipationStatus(Delegate $delegate)
    {
        if (!$delegate->exists) {
            $delegate->participation_status = 'to_be_arrived';
            $delegate->save();
            return;
        }

        $arrivalTransport = $delegate->delegateTransports()->where('type', 'arrival')->latest('date_time')->first();
        $departureTransport = $delegate->delegateTransports()->where('type', 'departure')->latest('date_time')->first();

        $newStatus = 'to_be_arrived';

        if ($arrivalTransport) {
            $currentDateTime = now();
            $arrivalDateTime = $arrivalTransport->date_time ? Carbon::parse($arrivalTransport->date_time) : null;

            if ($arrivalDateTime && $arrivalDateTime <= $currentDateTime) {
                if ($departureTransport) {
                    $departureDateTime = $departureTransport->date_time ? Carbon::parse($departureTransport->date_time) : null;
                    if ($departureDateTime && $departureDateTime <= $currentDateTime) {
                        $newStatus = 'departed';
                    } else {
                        $newStatus = 'arrived';
                    }
                } else {
                    $newStatus = 'arrived';
                }
            }
        }

        if ($delegate->participation_status !== $newStatus) {
            $delegate->participation_status = $newStatus;
            $delegate->save();
        }
    }

    public function updateTransportStatuses(Delegate $delegate)
    {
        $transports = $delegate->delegateTransports;
        $currentDateTime = now();

        foreach ($transports as $transport) {
            if ($transport->date_time) {
                $transportDateTime = Carbon::parse($transport->date_time);

                if ($transport->type === 'arrival') {
                    if ($transportDateTime <= $currentDateTime) {
                        $transport->status = 'arrived';
                    } else {
                        $transport->status = 'to_be_arrived';
                    }
                } elseif ($transport->type === 'departure') {
                    if ($transportDateTime <= $currentDateTime) {
                        $transport->status = 'departed';
                    } else {
                        $transport->status = 'to_be_departed';
                    }
                }

                $transport->save();
            }
        }
    }

    public function updateDelegationParticipationStatus(Delegation $delegation)
    {
        $delegates = $delegation->delegates;

        if ($delegates->isEmpty()) {
            $newStatus = 'not yet arrived';
            $participationStatusOption = \App\Models\DropdownOption::whereHas('dropdown', function ($query) {
                $query->where('code', 'participation_status');
            })->where('value', $newStatus)->first();

            if ($participationStatusOption && $delegation->participation_status_id !== $participationStatusOption->id) {
                $delegation->participation_status_id = $participationStatusOption->id;
                $delegation->save();
            }

            return;
        }

        $totalDelegates = $delegates->count();
        $arrivedCount = 0;
        $departedCount = 0;
        $toBeArrivedCount = 0;

        foreach ($delegates as $delegate) {
            if ($delegate->participation_status === 'departed') {
                $departedCount++;
            } elseif ($delegate->participation_status === 'arrived') {
                $arrivedCount++;
            } elseif ($delegate->participation_status === 'to_be_arrived') {
                $toBeArrivedCount++;
            }
        }

        $newStatus = 'not yet arrived';

        if ($departedCount === $totalDelegates) {
            $newStatus = 'departured';
        } elseif ($arrivedCount === $totalDelegates) {
            $newStatus = 'arrived';
        } elseif ($arrivedCount > 0) {

            if ($toBeArrivedCount > 0) {
                $newStatus = 'Partially Arrived';
            } elseif ($departedCount > 0) {
                $newStatus = 'Partially Departured';
            }
        } elseif ($toBeArrivedCount > 0) {

            if ($arrivedCount > 0 || $departedCount > 0) {
                $newStatus = 'Partially Arrived';
            } else {
                $newStatus = 'Not Yet Arrived';
            }
        } elseif ($departedCount > 0) {
            $newStatus = 'Partially Departured';
        }

        $participationStatusOption = \App\Models\DropdownOption::whereHas('dropdown', function ($query) {
            $query->where('code', 'participation_status');
        })->where('value', $newStatus)->first();

        if ($participationStatusOption && $delegation->participation_status_id !== $participationStatusOption->id) {
            $delegation->participation_status_id = $participationStatusOption->id;
            $delegation->save();
        }
    }

    public function updateAllDelegationStatuses()
    {
        $delegations = Delegation::whereHas('delegates.delegateTransports')->get();

        foreach ($delegations as $delegation) {
            foreach ($delegation->delegates as $delegate) {
                $this->updateTransportStatuses($delegate);
                $this->updateDelegateParticipationStatus($delegate);
            }

            $this->updateDelegationParticipationStatus($delegation);
        }
    }

    public function syncTransportInfo(Delegate $delegate, ?array $transportData, string $type): void
    {
        if (empty($transportData) || (empty($transportData['date_time']) || empty($transportData['mode']))) {
            return;
        }

        $data = [
            'mode' => $transportData['mode'] ?? null,
            'airport_id' => ($transportData['mode'] ?? null) === 'flight' ? ($transportData['airport_id'] ?? null) : null,
            'flight_no' => ($transportData['mode'] ?? null) === 'flight' ? ($transportData['flight_no'] ?? null) : null,
            'flight_name' => ($transportData['mode'] ?? null) === 'flight' ? ($transportData['flight_name'] ?? null) : null,
            'date_time' => $transportData['date_time'] ?? null,
            'status' => $transportData['status'] ?? null,
            'comment' => $transportData['comment'] ?? null,
        ];

        $delegate->delegateTransports()->updateOrCreate(['type' => $type], $data);
    }

    public function updateAllStatus(Delegate $delegate)
    {
        $this->updateTransportStatuses($delegate);

        $this->updateDelegateParticipationStatus($delegate);

        $this->updateDelegationParticipationStatus($delegate->delegation);
    }
}
