<?php

namespace App\Modules\Core\Traits;


trait EditableTraits
{

   public function getEditableAttribute($value)
    {
        return  $value ? 'Yes' : 'No';
    }
}
