<?php

namespace App\Modules\Hr\Database\Seeders;



use App\Models\WorkDay;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkDaySeeder extends Seeder
{
    public function run()
    {
        $days = [
            ['day_name' => 'Monday', 'day_of_week' => 1, 'editable' => 'No'],
            ['day_name' => 'Tuesday', 'day_of_week' => 2, 'editable' => 'No'],
            ['day_name' => 'Wednesday', 'day_of_week' => 3, 'editable' => 'No'],
            ['day_name' => 'Thursday', 'day_of_week' => 4, 'editable' => 'No'],
            ['day_name' => 'Friday', 'day_of_week' => 5, 'editable' => 'No'],
            ['day_name' => 'Saturday', 'day_of_week' => 6, 'editable' => 'No'],
            ['day_name' => 'Sunday', 'day_of_week' => 7, 'editable' => 'No'],

            ['day_name' => 'Monday to Friday', 'day_of_week' => 8, 'editable' => 'No'],
            ['day_name' => 'Monday to Saturday', 'day_of_week' => 9, 'editable' => 'No'],
            ['day_name' => 'Monday to Sunday', 'day_of_week' => 10, 'editable' => 'No'],

        ];

        foreach ($days as $day) {
            DB::table('work_days')->insert($day);
        }
    }
}