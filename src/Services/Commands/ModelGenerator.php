<?php

namespace App\Modules\Core\Services\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

class ModelGenerator extends Command
{



    public function generateModel($module, $modelName, $modelData)
    {


        $fields = $modelData['fields'] ?? [];
        $relations = $modelData['relations'] ?? [];

        $modelPath = app_path("Modules/{$module}/Models/{$modelName}.php"); // Modular Path

        if (!File::exists(dirname($modelPath))) {
            File::makeDirectory(dirname($modelPath), 0755, true); // Create directory if it doesn't exist
        }

        $stub = $this->getModelStub($module, $modelName, $modelData);
        File::put($modelPath, $stub);

        echo "Model created: {$modelPath}";
    }

    protected function getModelStub($module, $modelName, $modelData)
    {

        $fields = $modelData['fields'] ?? [];
        $relations = $modelData['relations'] ?? [];

        $moduleStubPath = app_path("Modules/{$module}/Stubs/Models/{$modelName}.stub");
        if (File::exists($moduleStubPath)) {
            $stub = File::get($moduleStubPath);
        } else {
            $coreStubPath = app_path('Modules/Core/Stubs/model.stub'); // Fallback
            if (!File::exists($coreStubPath)) {
                $this->error("Model stub not found: {$coreStubPath} or {$moduleStubPath}");
                return "";
            }
            $stub = File::get($coreStubPath);
        }


        $relationDefinitions = '';
        foreach ($relations as $relationName => $relationData) {
            $type = $relationData['type'];
            $relatedModel = $relationData['model'];


            switch ($type) {
                case 'belongsToMany':
                    $pivotTable = $relationData['pivotTable'] ?? Str::singular(Str::lower($modelName)) . '_' . Str::singular(Str::lower(class_basename($relatedModel)));
                    $relatedKey = $relationData['relatedKey'] ?? Str::singular(Str::lower(class_basename($relatedModel))) . '_id';
                    $localKey = $relationData['localKey'] ?? Str::singular(Str::lower($modelName)) . '_id';

                    $relationDefinitions .= "   public function {$relationName}(){\n";
                    $relationDefinitions .= "\t\treturn \$this->{$type}('{$relatedModel}', '{$pivotTable}', '{$localKey}', '{$relatedKey}');\n";
                    $relationDefinitions .= "\t}\n\n";
                    break;

                case 'belongsTo':
                    $foreignKey = $relationData['foreignKey'];
                    $relationDefinitions .= "   public function {$relationName}(){\n";
                    $relationDefinitions .= "\t\treturn \$this->{$type}('{$relatedModel}', '{$foreignKey}');\n";
                    $relationDefinitions .= "\t}\n\n";
                    break;

                case 'hasOne':
                case 'hasMany':
                    $relationDefinitions .= "   public function {$relationName}(){\n";
                    $relationDefinitions .= "\t\treturn \$this->{$type}('{$relatedModel}');\n";
                    $relationDefinitions .= "\t}\n\n";
                    break;

                case 'morphToMany':
                    $pivotTable = $relationData['pivotTable'];
                    $relatedPivotKey = $relationData['relatedPivotKey'];
                    $morphType = $relationData['morphType'];
                    $foreignKey = $relationData['foreignKey'];
                    $tableName = $pivotTable;

                    $relationDefinitions .= "   public function {$relationName}(){\n";
                    $relationDefinitions .= "\t\treturn \$this->{$type}('{$relatedModel}', '{$pivotTable}', '{$foreignKey}', '{$relatedPivotKey}', '{$morphType}');\n";
                    $relationDefinitions .= "\t}\n\n";
                    break;

                case 'morphTo':
                    $relationDefinitions .= "   public function {$relationName}(){\n";
                    $relationDefinitions .= "\t\treturn \$this->{$type}();\n"; // No parameters needed for morphTo
                    $relationDefinitions .= "\t}\n\n";
                    break;
            }
        }

        $fillableProperties = [];
        foreach ($fields as $fieldName => $fieldType) {
            $fillableProperties[] = "'{$fieldName}'";
        }
        $fillableString = implode(', ', $fillableProperties);

        $stub = str_replace('{{module}}', $module, $stub);
        $stub = str_replace('{{modelName}}', $modelName, $stub);
        $stub = str_replace('{{relations}}', $relationDefinitions, $stub);
        $stub = str_replace('{{fillable}}', $fillableString, $stub);


        $tableName = ""; // Default table name
        if ($modelData['isPivot'] ?? false) {
            $pivotTable = Str::snake(Str::singular(class_basename($modelName)));
            $tableName = "  protected \$table = '{$pivotTable}';\n";
        }

        $stub = str_replace('{{tableName}}', $tableName, $stub);

        return $stub;
    }






}
