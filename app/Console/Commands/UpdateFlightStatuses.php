<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DelegateTransport;
use App\Services\DelegationStatusService;
use Carbon\Carbon;

class UpdateFlightStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flights:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update flight statuses based on current time';


    public function handle()
    {
        $this->info('Updating flight statuses...');
        
        $passedArrivals = DelegateTransport::where('type', 'arrival')
            ->where('date_time', '<', Carbon::now())
            ->get();
            
        $updatedCount = 0;
        
        foreach ($passedArrivals as $arrival) {
            if ($arrival->status != 'arrived') {
                $arrival->status = 'arrived';
                $arrival->save();
                $updatedCount++;
            }
        }
        
        $this->info("Updated {$updatedCount} arrival statuses to 'Arrived'");
        
        $passedDepartures = DelegateTransport::where('type', 'departure')
            ->where('date_time', '<', Carbon::now())
            ->get();
            
        $departedCount = 0;
        
        foreach ($passedDepartures as $departure) {
            if ($departure->status != 'departed') {
                $departure->status = 'departed';
                $departure->save();
                $departedCount++;
            }
        }
        
        $this->info("Updated {$departedCount} departure statuses to 'Departed'");
        
        // Update delegation statuses based on updated delegate statuses
        $this->info('Updating delegation statuses...');
        $delegationService = new DelegationStatusService();
        $delegationService->updateAllDelegationStatuses();
        
        $this->info('Flight status update completed.');
    }
}
