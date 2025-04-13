<?php

namespace App\Modules\Core\Http\Livewire\Feedback;

use Livewire\Component;

class AlertMessage extends Component
{
    public $messageType = 'success'; // default type
    public $message;

    protected $listeners = [
        "setFeedbackMessageEvent" => "setFeedbackMessage",
    ];



    public function setFeedbackMessage($message)
    {

        $this->message = "Some messages";

            /*$this->message = session('success_message');
            $this->messageType = 'success'; // Customize for different types
*/
    }

    public function render()
    {
        return view('core.views::feedback.alert-message');

    }
}
