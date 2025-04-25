<?php

namespace QuickerFaster\CodeGen\Http\Livewire\Forms;

use Livewire\Component;
use QuickerFaster\CodeGen\Http\Livewire\DataTables\DataTableManager;



class FormManager extends DataTableManager
{



    /*protected $listeners = [
    ];*/




   /* public function render() {
        return view('core.views::forms.form-manager');
    }*/

    public function render()
    {
        return view('core.views::forms.form-manager', []);
    }


}
