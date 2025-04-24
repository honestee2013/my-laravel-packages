<?php

namespace App\Modules\Production\Listeners;

use App\Modules\Core\Events\DataTableFormEvent;
use App\Modules\Core\Listeners\DatatableFormListener;
use App\Modules\Core\Models\Status;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Modules\Production\Models\ProductionProcessLog;
use  App\Modules\Production\Events\ProductionProcessLogEvent;
use App\Modules\Production\Models\ProductionBatch;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ProductionProcessLogListener extends DatatableFormListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DataTableFormEvent $event): void
    {
        $this->handleModelEvent($event);

    }

    protected function handleModelEvent($event) {

        if (!str_contains($event->model, "ProductionProcessLog")
            && !str_contains($event->model, "ProductionProcessProgress")
        ) {
            return;
        }

        if ($event->eventName == "AfterCreate" || $event->eventName == "AfterUpdate")  {
            $this->handleProgressAndStatus($event);
        }
    }



    protected function handleProgressAndStatus($event)
    {

        // Cache status IDs to minimize database queries
        $statuses = Status::whereIn('name', ['in_progress', 'completed', 'pending'])->pluck('id', 'name');
        $inProgressStatus = $statuses['in_progress'];
        $completedStatus = $statuses['completed'];
        $pendingStatus = $statuses['pending'];

        $statusChanged = false;

        if (!isset($event->newRecord['id'])) {
            return;
        }

        $productionProcessLog = ProductionProcessLog::find($event->newRecord['id']);

        if (isset($event->newRecord['progress']) && intval($event->newRecord['progress']) != intval($event->oldRecord['progress'])) {
            // Handle progress updates and adjust status_id accordingly
            if ($event->newRecord['progress'] == 100) {
                $productionProcessLog->status_id = $completedStatus;
                $statusChanged = true;
            } elseif ($event->newRecord['progress'] > 0 && ($productionProcessLog->status_id == $pendingStatus || $productionProcessLog->status_id == $completedStatus) ) {
                $productionProcessLog->status_id = $inProgressStatus;
                $statusChanged = true;
            } elseif ($event->newRecord['progress'] == 0) {
                $productionProcessLog->status_id = $pendingStatus;
                $statusChanged = true;
            }
        }

        // Handle status_id updates and adjust progress accordingly
        if (!$statusChanged && $event->newRecord['status_id']) {
            if ($event->newRecord['status_id'] == $completedStatus) {
                $productionProcessLog->progress = 100;
            } elseif ($event->newRecord['status_id'] == $pendingStatus) {
                $productionProcessLog->progress = 0;
            }
        }

        $productionProcessLog->save();


        // Update the status of the production batch and production order
        $productionBatch = ProductionBatch::find($productionProcessLog->production_batch_id);
        if ($productionBatch && $productionProcessLog->status_id != $pendingStatus && $productionBatch->status_id == $pendingStatus) {
            $productionBatch->status_id = $inProgressStatus;
            $productionBatch->save();
        }

        // Update the status of the production order
        if ($productionBatch && $productionBatch->productionOrder->status_id == $pendingStatus && $productionBatch->status_id != $pendingStatus) {
            $productionBatch->productionOrder->status_id = $inProgressStatus;
            $productionBatch->productionOrder->save();
        }







    }




}
