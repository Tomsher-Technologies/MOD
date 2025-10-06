<?php
// app/Console/Commands/UpdateFlightStatuses.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DelegateTransport;
use App\Services\DelegationStatusService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UpdateFlightStatuses extends Command
{
    protected $signature = 'flights:update-statuses';
    protected $description = 'Automatically update flight statuses and related participation statuses';

    public function handle()
    {
        $this->info('Updating flight statuses...');

        $service = new DelegationStatusService();
        // $oneHourAgo = Carbon::now()->subHour();
        // $thirtyMinutesAgo = now()->subMinutes(30);
        $fiveMinutesAgo = now()->subMinutes(5);

        $affectedDelegateIds = collect();
        $affectedDelegationIds = collect();

        $passedArrivals = DelegateTransport::with('delegate.delegation')
            ->where('type', 'arrival')
            ->where('date_time', '<', $fiveMinutesAgo)
            ->where('status', '!=', 'arrived')
            ->get();

        $updatedArrivals = 0;
        foreach ($passedArrivals as $arrival) {
            $arrival->status = 'arrived';
            $arrival->save();
            $updatedArrivals++;
            if ($arrival->delegate) {
                $affectedDelegateIds->push($arrival->delegate_id);
                if ($arrival->delegate->delegation) {
                    $affectedDelegationIds->push($arrival->delegate->delegation->id);
                }
            }
        }
        $this->info("Updated {$updatedArrivals} arrival statuses to 'Arrived'");

        $passedDepartures = DelegateTransport::with('delegate.delegation')
            ->where('type', 'departure')
            ->where('date_time', '<', $fiveMinutesAgo)
            ->where('status', '!=', 'departed')
            ->get();

        $updatedDepartures = 0;
        foreach ($passedDepartures as $departure) {
            $departure->status = 'departed';
            $departure->save();
            $updatedDepartures++;
            if ($departure->delegate) {
                $affectedDelegateIds->push($departure->delegate_id);
                if ($departure->delegate->delegation) {
                    $affectedDelegationIds->push($departure->delegate->delegation->id);
                }
            }
        }
        $this->info("Updated {$updatedDepartures} departure statuses to 'Departed'");

        $affectedDelegateIds = $affectedDelegateIds->unique()->values();
        if ($affectedDelegateIds->isNotEmpty()) {
            $delegates = \App\Models\Delegate::with(['delegateTransports', 'delegation'])
                ->whereIn('id', $affectedDelegateIds)->get();

            foreach ($delegates as $delegate) {
                $service->updateDelegateParticipationStatus($delegate);
            }
        }

        $affectedDelegationIds = $affectedDelegationIds->unique()->values();
        if ($affectedDelegationIds->isNotEmpty()) {
            $delegations = \App\Models\Delegation::with(['delegates'])->whereIn('id', $affectedDelegationIds)->get();

            foreach ($delegations as $delegation) {
                $service->updateDelegationParticipationStatus($delegation);
            }
        }

        $this->info('Flight status update completed.');
    }
}
