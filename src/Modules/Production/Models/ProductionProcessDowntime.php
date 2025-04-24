<?php

namespace App\Modules\Production\Models;

use Carbon\Carbon;
use App\Modules\Item\Models\Item;
use App\Modules\Core\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProductionProcessDowntime extends  Model
{
    //use HasFactory;

    //protected $table = "production_process_downtimes";

    protected $fillable = [
        'production_process_log_id',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'notes',
    ];





    public function productionProcessLog()
    {
        return $this->belongsTo(ProductionProcessLog::class, 'production_process_log_id');
    }



    // Automatically calculate duration on save or update
    protected static function booted()
    {
        static::saving(function ($downtime) {
            $downtime->computeDuration();
        });

        static::updating(function ($downtime) {
            $downtime->computeDuration();
        });
    }

    public function computeDuration()
    {
        if ($this->start_time) {
            $startTime = Carbon::parse($this->start_time);
            $endTime = $this->end_time ? Carbon::parse($this->end_time) : now();

            $this->duration = abs($endTime->diffInMinutes($startTime));
        }
    }






}
