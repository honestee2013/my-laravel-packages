<?php

namespace QuickerFaster\CodeGen\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

use QuickerFaster\CodeGen\Services\Commands\BladeGenerator;
use QuickerFaster\CodeGen\Services\Commands\ModelGenerator;
use QuickerFaster\CodeGen\Services\Commands\ConfigGenerator;
use QuickerFaster\CodeGen\Services\Commands\MigrationGenerator;
use QuickerFaster\CodeGen\Services\Commands\SidebarLinksGenerator;


class GenerateFromSchema extends Command
{
    protected $signature = 'app:generate-from-schema {schema_file}';
    protected $description = 'Generate migrations, models, and other files from a schema definition.';

    public function handle()
    {
        $schemaFile = $this->argument('schema_file');

        if (!File::exists($schemaFile)) {
            $this->error("Schema file not found: {$schemaFile}");
            return Command::FAILURE;
        }

        $schema = Yaml::parseFile($schemaFile);

        foreach ($schema['models'] as $modelName => $modelData) {
            $module = $modelData['module']; // Get the module name
            
            (new MigrationGenerator())->generateMigration($module, $modelName, $modelData);
            (new ModelGenerator())->generateModel($module, $modelName, $modelData);
            (new ConfigGenerator())->generateConfigFile($module, $modelName, $modelData, $this); // New: Generate config file
            (new BladeGenerator())->generateBladeFile($module, $modelName, $modelData, $this); // We'll add this later
            (new SidebarLinksGenerator())->generateSidebarLinks($module, $modelName, $modelData, $this); // Generate sidebar links
        }

        $this->info('Files generated successfully!');
        return Command::SUCCESS;
    }


}





