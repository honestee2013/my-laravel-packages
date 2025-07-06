<?php

namespace App\Modules\Hr\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Hr\Models\PayrollRun;
use App\Modules\Hr\Services\PayrollGenerator;


class PayrollRunController extends Controller
{
    public function generatePayroll(PayrollRun $payrollRun)
    {
        //try {
            $generator = new PayrollGenerator();
            $generator->generatePayroll($payrollRun);

            //return redirect()->route('payroll-runs.show', $payrollRun)
                //->with('success', 'Payroll generated successfully!');
                echo 'Payroll generated successfully!';
        //} catch (\Exception $e) {
           // echo 'Payroll generation failed: ' . $e->getMessage();
            //return back()->with('error', 'Payroll generation failed: ' . $e->getMessage());
        //}
    }
}








/*Schema::create('bonus_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
			$table->text('description')->nullable();
			$table->string('editable')->default('Yes');

            $table->timestamps();
        });    Performance Bonus	
Awarded based on individual/team KPIs		  
	
Annual Bonus	
Year-end discretionary bonus		  
	
Referral Bonus	
Payment for successful new hire referrals	  
	
Retention Bonus	
Incentive to retain key employees	  
	
Spot Bonus	
Immediate reward for exceptional contributions	  
	
Sales Commission	
Percentage-based sales performance incentive		  
	
Signing Bonus	
One-time payment upon hiring acceptance

*/



