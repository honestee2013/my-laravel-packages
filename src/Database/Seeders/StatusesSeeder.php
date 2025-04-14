<?php

namespace QuickerFaster\CodeGen\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Pending', 'description' => 'Waiting for approval or further processing.'],
            ['name' => 'Approved', 'description' => 'Has been reviewed and authorized.'],
            ['name' => 'Rejected', 'description' => 'Not approved and marked for denial.'],
            ['name' => 'Completed', 'description' => 'Fully processed and finished.'],
            ['name' => 'Canceled', 'description' => 'Stopped or terminated before completion.'],
            ['name' => 'Returned', 'description' => 'Sent back to the original source.'],
            ['name' => 'On Hold', 'description' => 'Temporarily paused for some reason.'],
            ['name' => 'In Progress', 'description' => 'Currently being worked on.'],
            ['name' => 'Under Review', 'description' => 'Being examined or evaluated.'],
            ['name' => 'Under Maintenance', 'description' => 'Being shutdown for maintenance and repair.'],
            ['name' => 'Scheduled', 'description' => 'Planned for future action or execution.'],
            ['name' => 'Paused', 'description' => 'Process is temporarily halted.'],
            ['name' => 'Error', 'description' => 'Process encountered an issue.'],
        ];

        DB::table('statuses')->insert($statuses);
    }
}



