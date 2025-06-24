<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Traits\Database\HasStatusFields; // Import the HasStatusFields trait
use App\Modules\Core\Traits\HasEditableTraits;


class UserStatus extends Model
{
    use HasStatusFields, HasEditableTraits;

    protected $table = 'user_statuses';
    protected $fillable = ['name', 'display_name', 'description', 'category_id', 'editable'];

    public function category()
    {
        return $this->belongsTo(UserStatusCategory::class, 'category_id');
    }
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->display_name : null;
    }



}

// Similarly for other status models (e.g., ProductionStatus, HRStatus)