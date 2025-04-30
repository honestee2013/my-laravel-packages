<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Core\Traits\EditableTraits; // Import the EditableTraits trait

class Status extends Model
{
    use HasFactory, EditableTraits;

    protected $fillable = [
        'name',
        'description',
    ];


    // App\Modules\Production\Models\Status.php
    public static function getStatusId($name)
    {
        return self::where('name', $name)->value('id') ?? 0; // Defaults to 0 if not found
    }
    



}
