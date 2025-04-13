<?php

namespace app\Modules\Core\Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Core\Http\Livewire\DataTables\DataTable;
use App\Modules\Core\Http\Livewire\DataTables\DataTableControl;
use App\Modules\Core\Http\Livewire\DataTables\DataTableManager;
use App\Modules\Core\Traits\DataTable\DataTableControlsTrait;
use App\Modules\Core\Traits\DataTable\DataTableFieldsConfigTrait;

class DataTableControlTest extends TestCase
{

    //use DataTableFieldsConfigTrait, DataTableControlsTrait;

    /** @test */
    /*public function test_datatable_control_loaded_successfully()
    {
        Livewire::test(DataTableControl::class,
            $this->getData(),
        )
        ->assertSeeInOrder( ['File', 'Rows', 'Search', 'Columns']);
    }*/


    /*public function test_datatable_control_file_export_table_successfully() {
        // Asuming that the data base seeder always seeds the user 'admin' with email "admin@softui.com"
        $data = $this->getData();
        $data["hiddenFields"]['onQuery'] = array_unique([...$data["hiddenFields"]['onQuery'], "password_confirmation"]);
        $data["sortField"] = "name";
        $dataTable =  Livewire::test(DataTableControl::class, $data);
        $fileInfo = $dataTable->call("export", "xlsx");
        $fileInfo->assertStatus(200); // Better approach should be used eg. $fileInfo->assertDownload("filename)
    }


    public function test_datatable_control_change_per_page_successfully() {
        $dataTable =  Livewire::test(DataTable::class, $this->getData());
        $dataTable->call("changePerPage", 5)
            ->assertSee("Showing 1 to 5");
    }


    public function test_datatable_control_search_successfully() {
        // Asuming that the data base seeder always seeds the user 'admin' with email "admin@softui.com"
       $dataTable =  Livewire::test(DataTable::class, $this->getData());
        $dataTable->call("changeSearch", "admin@softui.com")
            ->assertSee("Showing 1 to 1");
    }


    public function test_datatable_control_show_hide_columns_successfully() {
        $dataTable =  Livewire::test(DataTable::class, $this->getData());
        $dataTable->call("showHideColumns", [$dataTable->columns[0]]);
        $this->assertEquals($dataTable->visibleColumns[0], $dataTable->columns[0]);
    }



    public function test_datatable_control_toggle_sort_successfully() {
        // Asuming that the data base seeder always seeds the user 'admin' with email "admin@softui.com"
        $dataTable =  Livewire::test(DataTable::class, $this->getData());
        $dataTable->call("sortColumn", 'email');
        $dataTable->assertViewHas('data', function ($data)    {
            // Asuming that the seeded record is more than one user
            return $data[0]->email !== "admin@softui.com";
         });
    }



    private function getData() {
        $model = "App\\Models\\User";
        $modelName = "User";
        $moduleName = "Core";

        $data = $this->configTableFields(strtolower($moduleName), strtolower($modelName));

        $data["model"] = $model;
        $data["modelName"] = $modelName;
        $data["moduleName"] = $moduleName;

        $data["controls"] = $this->getPreparedControls("all");//$data["controls"]
        return $data;
    }*/



   /*public function test_datatable_search_successfully(): void
    {

        $model = "App\\Models\\User";
        $modelName = "User";
        $moduleName = "Core";

        $data = $this->configTableFields(strtolower($moduleName), strtolower($modelName));

        $data["model"] = $model;
        $data["modelName"] = $modelName;
        $data["moduleName"] = $moduleName;

       $component = Livewire::test(DataTable::class, $data);


        // If custom controls are passed, use them. Otherwise, fetch default controls from the trait.
        /*$this->controls = $this->getPreparedControls($this->controls);

        $this->visibleColumns = $data["columns"];// Show all columns by default
        // Hidden on table index view
        if ($this->hiddenFields['onTable'])
            $this->visibleColumns = array_diff($this->visibleColumns, $this->hiddenFields['onTable']);* /


    }*/




    /*public function test_datatable_manager_table_fields_definitions_initiated_successfully(): void
    {
        $component = Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"]);
        $this->assertNotEmpty($component->fieldDefinitions);
    }


    public function test_datatable_manager_model_config_file_loaded_successfully(): void
    {
        $component = Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User", "moduleName" => "Core"]);
        // If config file is loaded successfully and user wants the 'ID' field to be part of the 'fieldDefinitions
        // then the 'ID' data type must not be 'bigint' (which is assign by default) when config file is absent
        if (isset($component->fieldDefinitions['id']))
            $this->assertNotEquals($component->fieldDefinitions['id'], "bigint");
        else  // If config file is loaded, 'ID' field is not expected to be part of 'fieldDefinitions
            $this->assertFalse(isset($component->fieldDefinitions['id']));
    }*/





}
