<?php

namespace App\Modules\Production\Models;

use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ProductionOrderResource extends Pivot
{
    
    protected $table = "production_order_items";


    protected $fillable = [
        'production_order_request_id',
        'item_id',
        'quantity',
        'note',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrderRequest::class,  "production_order_request_id", );
    }

    public function item()
    {
        return $this->belongsTo(Item::class, "item_id");
    }


}
