<?php

namespace App\Modules\Hr\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BonusAndDeductionTypeSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed bonus types
        $bonusTypes = [
            ['name' => 'Performance Bonus', 'description' => 'Bonus awarded based on individual or team performance.', 'editable' => 'No'],
            ['name' => 'Annual Bonus', 'description' => 'Yearly bonus distributed to employees.', 'editable' => 'No'],
            ['name' => 'Referral Bonus', 'description' => 'Bonus for successfully referring a new hire.', 'editable' => 'No'],
            ['name' => 'Retention Bonus', 'description' => 'Bonus to encourage employees to stay with the company.', 'editable' => 'No'],
            ['name' => 'Spot Bonus', 'description' => 'On-the-spot bonus for exceptional contributions.', 'editable' => 'No'],
        ];

        foreach ($bonusTypes as $type) {
            ///DB::table('bonus_types')->insert($type);
        }

        // Seed deduction types
        $deductionTypes = [
            ['name' => 'Tax Withholding', 'description' => 'Amount deducted for income tax.', 'editable' => 'No'],
            ['name' => 'Health Insurance', 'description' => 'Employee contribution to health insurance premiums.', 'editable' => 'No'],
            ['name' => 'Retirement Plan', 'description' => 'Contribution to the company\'s retirement savings plan.', 'editable' => 'No'],
            ['name' => 'Loan Repayment', 'description' => 'Deduction for repayment of company loans.', 'editable' => 'No'],
            ['name' => 'Garnishments', 'description' => 'Court-ordered wage garnishments.', 'editable' => 'No'],
        ];

        foreach ($deductionTypes as $type) {
            ///DB::table('deduction_types')->insert($type);
        }


    }





}