<?php

namespace QuickerFaster\CodeGen\Http\Livewire\User;

use Livewire\Component;


class UserProfile extends Component
{
    public $user;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('user.views::profile');
        
    }
}


