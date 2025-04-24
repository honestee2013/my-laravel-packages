<?php

namespace App\Modules\Production\Models;

use Carbon\Carbon;
use App\Modules\Item\Models\Item;
use App\Modules\Core\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProductionProcessProgress extends ProductionProcessLog
{
    protected $table = "production_process_logs";

    // Accessor for total downtime
    public function getTotalDowntimeAttribute()
    {
        return $this->getParentLogDowntimes();
    }

    public function getParentLogDowntimes()
    {
        // Find the parent log using the production_batch_id
        $parentLog = ProductionProcessLog::where('production_batch_id', $this->production_batch_id)->first();

        if (!$parentLog) {
            return 0; // Return 0 if no parent log is found
        }

        // Calculate total downtime
        $total = $parentLog->downtimes->sum(function ($downtime) {
            $startTime = Carbon::parse($downtime->start_time);
            $endTime = $downtime->end_time ? Carbon::parse($downtime->end_time) : now();
            return $endTime->diffInMinutes($startTime);
        });

        return abs($total); // Ensure a positive value
    }
}

