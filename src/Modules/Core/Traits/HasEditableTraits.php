<?php

namespace App\Modules\Core\Traits;


trait HasEditableTraits
{

   public function getEditableAttribute($value)
    {
        return  $value ? 'Yes' : 'No';
    }
}
