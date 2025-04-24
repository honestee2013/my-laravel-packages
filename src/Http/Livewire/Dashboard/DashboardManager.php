<?php

namespace QuickerFaster\CodeGen\Http\Livewire\Dashboard;


use Livewire\Component;

use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Modules\Production\Models\ProductionProcess;
use App\Modules\Production\Models\ProductionProcessLog;
use App\Modules\Production\Models\ProductionItemionProcess;
use App\Modules\Production\Models\ProductionItemionProcessLog;
use App\Modules\Core\Traits\DataTable\DataTableFieldsConfigTrait;

class DashboardManager extends Component
{



    public $timeDuration = "this_month";
    public $selectedProcessId;
    public $selectedProcessLogId;
    public $selectedProcessName;


    protected $listeners = [
        //'configChangedEvent' => '$refresh',
    ];



    public function mount()
    {
        // This is the default process to be configured by the user
        /*$defaultProcess = ProductionProcess::where("name", "Parboiling")->first();
        if ($defaultProcess) {
            $this->selectedProcessName = $defaultProcess->name;
            $this->selectedProcessId = $defaultProcess->id;
            $this->selectedProcessLogId = ProductionProcessLog::where("production_process_id", $this->selectedProcessId)?->first()?->id;
        }*/
    }

    public function updatedSelectedProcessId($newId) {
        /*$this->selectedProcessName = ProductionProcess::findOrFail($newId)->name;
        $this->selectedProcessLogId = ProductionProcessLog::where("production_process_id", $newId)->first()->id;

        //$this->dispatch("configChangedEvent", ["recordName" => "Completed " . ucfirst($this->selectedProcessName) ]);
        $this->dispatch("configChangedEvent", [
            "filters" => [
                ['production_process_id', '=', $newId ?? 0],
                ['production_process_log_id', '=', $this->selectedProcessLogId ?? 0],
            ],
        ]);*/
    }




    public function updatedTimeDuration()
    {
        $this->dispatch("configChangedEvent", ["timeDuration" => $this->timeDuration]);
    }









    public function render()
    {
        return view('dashboard.views::dashboard-manager', []);
    }

}
