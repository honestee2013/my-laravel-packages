<?php

namespace App\Modules\Core\Http\Livewire\Feedback;

use Livewire\Component;

class FormManager extends Component
{

    protected $listeners = [
    ];




    public function render() {
        return view('core.views::forms.form-manager');
    }


}
