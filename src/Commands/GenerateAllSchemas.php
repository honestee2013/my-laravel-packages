<?php

namespace QuickerFaster\CodeGen\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateAllSchemas extends Command
{
    protected $signature = 'app:generate-all-schemas {--modules= : Comma-separated list of modules to process in order}';
    protected $description = 'Generate schemas from YAML files in a sequential order';
    /*
        // Execute all the .yaml files inside all modules in 'app/Modules/Core/Yamls/Modules'
        php artisan app:generate-all-schemas

        // Execute all the .yaml files inside the listed modules in a sequencial order provided by --modules=
        php artisan app:generate-all-schemas --modules=Mudule1,Module2,Module-nth,

        // Execute all the .yaml files listed in protected $modules = [...] modules in a sequencial order provided in the strict order
        protected $modules = [
            'Module1' => ['first.yaml', 'second.yaml'], // String order
            'Module2' => ['first.yaml', 'second.yaml', third.yaml'], // Strict order
            'Module3' => [], // Any order
            'Module4' => [], // Any order
        ];
    */

    // Define strict module execution order
    protected $modules = [
        'Core' => [],
        //'Organization' => [],
        'User' => ['user_management.yaml', 'user_status_management.yaml'],
        'Hr' =>  [
            'employee_management.yaml',
            'onboarding_and_employee_lifecycle.yaml',

            // Payroll & Attendance related files

            'payroll_processing.yaml', 
            'payroll_components.yaml',
            'payroll_bonuses_and_allowances.yaml', 
            'payroll_deductions.yaml', 
            
            'payroll_attendance_and_earning.yaml',
            'payroll_attendance_exemption.yaml',

            'shifts_and_compliance.yaml',
            'role_scheduling.yaml',
            'time_off_and_leaves.yaml',


            'salaries_and_payment_structure.yaml',
            'user_salary_records.yaml',

             'departments.yaml',
        ],
        //'Profile' => [],
        //'Item' => [],
        /*'Warehouse'=> [
            'environmental_condition.yaml',
            'storage_type.yaml',
            'warehouse.yaml',
        ],*/


    ];

    public function handle()
    {
        $basePath = base_path('app/Modules/Core/Yamls/Modules');

        // Get CLI argument and determine module execution order
        $cliModules = $this->option('modules');
        $modulesToProcess = $cliModules ? explode(',', $cliModules) : array_keys($this->modules);

        foreach ($modulesToProcess as $module) {
            $module = ucfirst(trim($module));
            $searchPath = "$basePath/$module";

            if (!File::exists($searchPath)) {
                $this->error("Module '$module' not found!");
                continue;
            }

            // Get YAML files (strict order if defined in $modules)
            $files = File::allFiles($searchPath);
            $yamlFiles = collect($files)->filter(fn($file) => $file->getExtension() === 'yaml');

            // If module has predefined order, use that instead
            if (!empty($this->modules[$module])) {
                $yamlFiles = collect($this->modules[$module])
                    ->map(fn($path) => new \SplFileInfo("$searchPath/$path"))
                    ->filter(fn(\SplFileInfo $file) => File::exists($file->getRealPath()));
            }

            if ($yamlFiles->isEmpty()) {
                $this->warn("No YAML files found for '$module'");
                continue;
            }

            foreach ($yamlFiles as $file) {
                $relativePath = str_replace(base_path() . '/', '', $file->getRealPath());
                $this->info("Running: php artisan app:generate-from-schema $relativePath");

                $this->call('app:generate-from-schema', ['schema_file' => $relativePath]);
            }
        }

        $this->info("All YAML schemas have been processed successfully!");
    }
}
