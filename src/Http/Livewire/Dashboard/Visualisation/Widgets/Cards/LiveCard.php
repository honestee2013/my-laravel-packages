<?php

namespace QuickerFaster\CodeGen\Http\Livewire\Dashboard\Visualisation\Widgets\Cards;


use Livewire\Component;

use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Modules\Core\Traits\DataTable\DataTableFieldsConfigTrait;



class LiveCard extends Component
{

    protected $listeners = [
        'recordSavedEvent' => '$refresh',
    ];



    public function mount()
    {

    }


    public function render()
    {
        return view('dashboard.views::components.visualisation.widgets.cards.live-card');

    }

}
