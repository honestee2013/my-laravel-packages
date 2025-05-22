<?php 



namespace QuickerFaster\CodeGen\Traits\GUI;



trait HasDisplayName
{
    
    public function getDisplayNameAttribute()
    {

        return $this->displayFields
            ? implode(' - ', array_map(function ($field) {
                return $this->{$field};
            }, $this->displayFields))
            : null;
    }


}


