<?php

namespace App\Modules\Production\Models;

use App\Modules\Item\Models\Item;
use App\Modules\Core\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProductionProcessInput extends  Pivot
{
    use HasFactory;

    protected $table = "production_process_inputs";

    protected $fillable = [
        'production_process_log_id',
        'item_id',
        'actual_quantity',
        'planned_quantity',
    ];


    public function productionProcessLog()
    {
        return $this->belongsTo(ProductionProcessLog::class, 'production_process_log_id');
    }


    public function item()
    {
        return $this->belongsTo(Item::class, "item_id");
    }




}
