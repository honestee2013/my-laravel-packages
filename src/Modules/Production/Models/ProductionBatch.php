<?php

namespace App\Modules\Production\Models;

use App\Modules\Item\Models\Item;
use App\Modules\Core\Models\Status;
use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    protected $fillable = ['batch_number', 'production_order_request_id', 'status', 'note'];





   public static function boot()
{
    parent::boot();

    static::creating(function ($batch) {
        // Generate Batch Number
        $latestBatch = ProductionBatch::latest('id')->first();
        $nextBatchId = $latestBatch ? $latestBatch->id + 1 : 1;
        $batchNumber = 'BATCH-' . now()->format('Ymd') . '-' . str_pad($nextBatchId, 3, '0', STR_PAD_LEFT);

        // Assign the batch number
        $batch->batch_number = $batchNumber;

        // Set default status
        $status = Status::where('name', 'pending')->first();
        if ($status) {
            $batch->status_id = $status->id;
        } else {
            $batch->status_id = 1;

        }

        // Assign created_by field
        $batch->created_by = auth()->id();
    });
}






public function status()
{
    return $this->belongsTo(Status::class);
}

public function productionOrder()
{
    return $this->belongsTo(ProductionOrderRequest::class, "production_order_request_id");
}










public function inputItems()
{
    return $this->belongsToMany(Item::class, "production_batch_inputs", "production_batch_id", "item_id")->using(ProductionBatchInput::class);
}

public function outputItems()
{
    return $this->belongsToMany(Item::class, "production_batch_outputs", "production_batch_id", "item_id")->using(ProductionBatchOutput::class);
}


/*public function processLogs()
{
    return $this->hasMany(ProductionProcessLog::class);
}*/






    public function trackers()
    {
        return $this->hasMany(BatchTracker::class);
    }




}
