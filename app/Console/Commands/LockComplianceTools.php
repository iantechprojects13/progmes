<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationFormModel;
use App\Models\TestModel;
use Carbon\Carbon;

class LockComplianceTools extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:lock-compliance-tools';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lock compliance tools on deadline.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Set timezone to Philippine time
        $targetDateTime = Carbon::create(2025, 5, 16, 14, 8, 0, 'Asia/Manila');
        $now = Carbon::now('Asia/Manila');
        
        $this->info("Current time: " . $now->toDateTimeString());
        $this->info("Target time: " . $targetDateTime->toDateTimeString());
        
        if ($now->between($targetDateTime, $targetDateTime->copy()->addMinute())) {
            $this->info("Executing lock operation...");
            $count = EvaluationFormModel::where('status', 'In progress')
                ->where('effectivity', '2024-2025')
                ->update([
                    'status' => 'Locked',
                ]);
            $this->info("Locked {$count} evaluation forms.");
        } else {
            $this->info("Not yet time to lock forms.");
        }

    }
}
