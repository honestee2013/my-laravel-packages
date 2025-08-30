<?php

namespace App\Modules\Hr\Listeners;




use App\Modules\Hr\Events\PayrollRunEvent;
use App\Modules\Hr\Services\PayrollGenerator;
use QuickerFaster\CodeGen\Services\AccessControl\AccessControlPermissionService;
use QuickerFaster\CodeGen\Services\GUI\SweetAlertService;


class PayrollRunListener
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
public function handle(PayrollRunEvent $event): void
{

    if ($event->data["eventName"] == "BeforeUpdate")  {
        $this->handleModelEvent($event);
    }
}

protected function handleModelEvent($event)
{

    if (!isset($event->data['oldRecords'])) {
        return;
    }

    /** @var PayrollRun $payrollRun */
    $payrollRun = $event->data['oldRecords']->first();

    $action = $event->data['actionName'] ?? null;

    switch ($action) {
        case 'generate_payroll':
            $this->handleGeneratePayroll($payrollRun, $event);
            break;

        case 'approve_payroll':
            $this->handleApprovePayroll($payrollRun, $event);
            break;

        case 'process_payment':
            $this->handleProcessPayment($payrollRun, $event);
            break;

        case 'cancel_payroll':
            $this->handleCancelPayroll($payrollRun, $event);
            break;

        default:
            // No action or unknown action
            break;
    }
}

/**
 * Handle payroll generation
 */protected function handleGeneratePayroll($payrollRun, $event)
{
    $status = strtolower($payrollRun->status);

    if ($status !== 'draft') {
        if ($status === "generated") {
            SweetAlertService::showError($event->data['context'], "Error!", "Payroll has already been generated.");
        } elseif ($status === "approved") {
            SweetAlertService::showError($event->data['context'], "Error!", "Payroll cannot be generated again after approval.");
        } else {
            SweetAlertService::showError($event->data['context'], "Error!", "Payroll generation not allowed in current status.");
        }
        return;
    }

    (new PayrollGenerator())->generatePayroll($payrollRun);

    $payrollRun->status = 'generated';
    $payrollRun->created_by = auth()->id();
    $payrollRun->created_at = now();
    $payrollRun->save();
    SweetAlertService::showSuccess($event->data['context'], "Success!", "Payroll has been generated.");

}


/**
 * Handle payroll approval
 */
protected function handleApprovePayroll($payrollRun, $event)
{
    $status = strtolower($payrollRun->status);

    if ($status !== 'generated') {
        if ($status === 'approved') {
            SweetAlertService::showError($event->data['context'], "Error!", "Payroll has already been approved.");
        } else {
            SweetAlertService::showError($event->data['context'], "Error!", "Only generated payroll can be approved.");
        }
        return;
    }

    $payrollRun->status = 'approved';
    $payrollRun->approved_by = auth()->id();
    $payrollRun->approved_at = now();
    $payrollRun->save();

    SweetAlertService::showSuccess($event->data['context'], "Success!", "Payroll has been approved.");
}


/**
 * Handle payroll payment
 */
protected function handleProcessPayment($payrollRun, $event)
{
    $status = strtolower($payrollRun->status);

    if ($status !== 'approved') {
        SweetAlertService::showError($event->data['context'], "Error!", "Only approved payroll can be processed.");
        return;
    }

    // Payment logic (if any external service is involved, plug here)
    $payrollRun->status = 'paid';
    $payrollRun->paid_by = auth()->id();
    $payrollRun->paid_at = now();
    $payrollRun->save();

    SweetAlertService::showSuccess($event->data['context'], "Success!", "Payroll payment processed.");
}


/**
 * Handle payroll cancellation
 */
protected function handleCancelPayroll($payrollRun, $event)
{
    $status = strtolower($payrollRun->status);

    if ($status === 'processed') {
        SweetAlertService::showError($event->data['context'], "Error!", "Processed payroll cannot be cancelled.");
        return;
    } else if ($status === 'cancelled') {
        SweetAlertService::showError($event->data['context'], "Error!", "Payroll has already been cancelled.");
        return;
    } else if ($status !== 'draft' && $status !== 'generated') {
        SweetAlertService::showError($event->data['context'], "Error!", "Only draft or generated payroll can be cancelled.");
        return;
    }

    $payrollRun->status = 'cancelled';
    $payrollRun->cancelled_by = auth()->id();
    $payrollRun->cancelled_at = now();
    $payrollRun->save();

    SweetAlertService::showSuccess($event->data['context'], "Success!", "Payroll has been cancelled.");
}







}
