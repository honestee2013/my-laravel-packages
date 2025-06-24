<?php

namespace App\Modules\user\Models; // Important: Include the module namespace

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\User\Traits\Database\HasStatusCategoryFields; // Import the HasStatusFields trait
use App\Modules\Core\Traits\HasEditableTraits;


class UserStatusCategory extends Model
{
    use HasStatusCategoryFields, HasEditableTraits;

    protected $table = 'user_status_categories';
    protected $fillable = ['name', 'display_name', 'description', 'editable'];

    public function statuses(): HasMany
    {
        return $this->hasMany(UserStatus::class, 'category_id');
    }

    
}