<?php

namespace App\Modules\Production\Listeners;

use App\Modules\Core\Events\DataTableFormEvent;
use App\Modules\Core\Listeners\DatatableFormListener;
use App\Modules\Core\Models\Status;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Modules\Production\Models\ProductionProcessLog;
use  App\Modules\Production\Events\ProductionProcessLogEvent;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ProductionProcessProgressListener extends ProductionProcessLogListener{



}
