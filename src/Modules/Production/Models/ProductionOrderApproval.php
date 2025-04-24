<?php
namespace App\Modules\Production\Models;

use App\Models\User;
use App\Modules\Item\Models\Item;
use App\Modules\Core\Models\Status;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Traits\Database\DatabaseQueryTrait;

class ProductionOrderApproval extends ProductionOrderRequest
{

    use DatabaseQueryTrait;

    protected $table = "production_order_requests";
    protected $fillable = [
        'order_number',
        'item_id',
        'quantity',
        'status_id',
        'due_date',

        'is_auto_generated',


        'approved_at',
        'approved_by',
        'is_approved',
        'is_verified',



        'start_date',
        'end_date',
        'created_by',
        'supervisor_id',


        'remarks'
    ];









    /*public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }


    public function items()
    {
        return $this->hasMany(ProductionOrderItem::class);
    }








    public function batches()
    {
        return $this->hasMany(Batch::class);
    }




    public function logs()
    {
        return $this->hasMany(ProductionLog::class);
    }


    public function item()
    {
        return $this->belongsTo(Item::class);
    }



    public function status()
    {
        return $this->belongsTo(Status::class);
    }



    public static function boot()
    {
        parent::boot();

        static::creating(function ($productionOrder) {
            //if (!$productionOrder->order_number) {
                // Generate Production Order Number
                $latestOrder = ProductionOrder::latest('id')->first();
                $nextOrderId = $latestOrder ? $latestOrder->id + 1 : 1;
                $orderNumber = 'PO-' . now()->format('Ymd') . '-' . str_pad($nextOrderId, 3, '0', STR_PAD_LEFT);

                // Assign the numbers
                $productionOrder->order_number = $orderNumber;
            //}

            if (!$productionOrder->status_id) {
                // to be adjusted by $this->setStatusIdAttribute()
                $productionOrder->status_id = 1;

            }
            /*$batch = new Batch();
            $batch->production_order_id = $productionOrder->id;
            $batch->save();* /


        });




    }*/



    public function setStatusIdAttribute($value)
    {
        $this->attributes['status_id'] = $value;

        if (!$this->attributes['status_id']) {
            $status = Status::where('name', '=', 'pending')->first();

            if ($status) {
                $this->attributes['status_id'] =  $status->id;
            } else {
                $this->attributes['status_id'] = 1;
            }
        }
    }








}

/*

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'status_id',
        'start_date',
        'end_date',
        'created_by',
        'supervisor_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'status_id',
        'output_quantity',
        'production_order_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function resourceItems()
    {
        return $this->hasMany(BatchResourceItem::class);
    }

    public function productItems()
    {
        return $this->hasMany(BatchProductItem::class);
    }

    public function processLogs()
    {
        return $this->hasMany(ProductionProcessLog::class);
    }
}

class BatchResourceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'item_id',
        'quantity_used',
        'expected_quantity',
        'unit_id',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

class BatchProductItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'item_id',
        'quantity_produced',
        'expected_quantity',
        'unit_id',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

class ProductionProcessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'process_id',
        'operator_id',
        'supervisor_id',
        'start_time',
        'end_time',
        'notes',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function resources()
    {
        return $this->hasMany(ProcessResource::class, 'process_log_id');
    }

    public function outputs()
    {
        return $this->hasMany(ProcessOutput::class, 'process_log_id');
    }
}

class Process extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function logs()
    {
        return $this->hasMany(ProductionProcessLog::class);
    }
}

class ProcessResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'process_log_id',
        'item_id',
        'quantity_used',
        'unit_id',
    ];

    public function processLog()
    {
        return $this->belongsTo(ProductionProcessLog::class, 'process_log_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

class ProcessOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'process_log_id',
        'item_id',
        'quantity_produced',
        'unit_id',
    ];

    public function processLog()
    {
        return $this->belongsTo(ProductionProcessLog::class, 'process_log_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
*/
