<?php

namespace App\Modules\Production\Models;


use Carbon\Carbon;
use App\Models\User;
use App\Modules\Item\Models\Item;
use App\Modules\Core\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;





class ProductionProcessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'production_process_id',
        'operator_id',
        'status_id',
        'progress',
        'supervisor_id',
        'start_time',
        'expected_end_time',
        'actual_end_time',
        'notes',
    ];

    protected $appends = ['total_downtime'];


    public function status()
    {
        return $this->belongsTo(Status::class);
    }


    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public function productionProcess()
    {
        return $this->belongsTo(ProductionProcess::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }


    public function inputItems()
    {
        return $this->belongsToMany(Item::class, "production_process_inputs", "production_process_log_id", "item_id")->using(ProductionProcessInput::class);
    }

    public function outputItems()
    {
        return $this->belongsToMany(Item::class, "production_process_outputs", "production_process_log_id", "item_id")->using(ProductionProcessOutput::class);
    }

    public function downtimes()
    {
        return $this->hasMany(ProductionProcessDowntime::class, 'production_process_log_id', 'id');
    }




    public function getTotalDowntimeAttribute()
    {
        $total = $this->downtimes->sum(function ($downtime) {
            $startTime = Carbon::parse($downtime->start_time);
            $endTime = $downtime->end_time ? Carbon::parse($downtime->end_time) : now();

            return $endTime->diffInMinutes($startTime);
        });

        return abs($total);
    }









    public static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $batchNumber = $log->batch?->batch_number ?? 'Unknown Batch';
            $processName = $log->productionProcess?->name ?? 'Unknown Process';
            $log->display_name = $batchNumber . " (" . $processName . ")";

        });

        static::saving(function ($log) {
            $batchNumber = $log->batch?->batch_number ?? 'Unknown Batch';
            $processName = $log->productionProcess?->name ?? 'Unknown Process';
            $log->display_name = $batchNumber . " (" . $processName . ")";
        });
    }


    




}
