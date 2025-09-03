<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DelegateTransport;
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
        
        $arrivalStatusDropdown = \App\Models\Dropdown::where('code', 'arrival_status')->first();
        
        if (!$arrivalStatusDropdown) {
            $this->error('Arrival status dropdown not found');
            return;
        }
        
        $arrivedStatus = $arrivalStatusDropdown->options()->where('value', 'Arrived')->first();
            
        if (!$arrivedStatus) {
            $this->error('Arrived status not found in dropdown options');
            return;
        }
        
        $passedArrivals = DelegateTransport::where('type', 'arrival')
            ->where('date_time', '<', Carbon::now())
            ->get();
            
        $updatedCount = 0;
        
        foreach ($passedArrivals as $arrival) {
            if ($arrival->status_id != $arrivedStatus->id) {
                $arrival->update(['status_id' => $arrivedStatus->id]);
                $updatedCount++;
            }
        }
        
        $this->info("Updated {$updatedCount} arrival statuses to 'Arrived'");
        
        $departureStatusDropdown = \App\Models\Dropdown::where('code', 'departure_status')->first();
        
        if ($departureStatusDropdown) {
            $departedStatus = $departureStatusDropdown->options()->where('value', 'Departed')->first();
            
            if ($departedStatus) {
                $passedDepartures = DelegateTransport::where('type', 'departure')
                    ->where('date_time', '<', Carbon::now())
                    ->get();
                    
                $departedCount = 0;
                
                foreach ($passedDepartures as $departure) {
                    if ($departure->status_id != $departedStatus->id) {
                        $departure->update(['status_id' => $departedStatus->id]);
                        $departedCount++;
                    }
                }
                
                $this->info("Updated {$departedCount} departure statuses to 'Departed'");
            }
        }
        
        $this->info('Flight status update completed.');
    }
}
