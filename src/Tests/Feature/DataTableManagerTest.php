<?php

namespace QuickerFaster\CodeGen\Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Core\Http\Livewire\DataTables\DataTable;
use App\Modules\Core\Http\Livewire\DataTables\DataTableManager;
use App\Models\User; // Or the actual path to your User model

class DataTableManagerTest extends TestCase
{
    use RefreshDatabase; // If you're using a database

    /** @test */
    public function test_datatable_manager_page_loaded_successfully()
    {
        $response = $this->get('/core/test-data-table-manager'); // Adjust path
        $response->assertStatus(200);
    }

    /** @test */
    public function test_datatable_manager_model_and_module_name_initiated_successfully()
    {
        Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"])
            ->assertSet('model', 'App\\Models\\User')
            ->assertSet('modelName', 'User')  // Assert model name
            ->assertSet('moduleName', 'User'); // Because we have default module name extraction logic
    }

    /** @test */
    public function test_datatable_manager_table_fields_definitions_initiated_successfully()
    {
        $commoponent = Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"]);
        $this->assertNotEmpty($commoponent->get('fieldDefinitions'));
    }

    /** @test */
    public function test_datatable_manager_model_config_file_loaded_successfully()
    {
        Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"])
            ->assertSee('name') // Assert for specific fields from config
            ->assertSee('email')
            ->assertSee('roles');

        // Or, more directly:
        $component = Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"]);
        $this->assertArrayHasKey('name', $component->fieldDefinitions);
        $this->assertArrayHasKey('email', $component->fieldDefinitions);
        $this->assertArrayHasKey('password', $component->fieldDefinitions);
    }

    /** @test */
    public function test_datatable_manager_hidden_fields_initiated_successfully()
    {
        $component = Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"]);
        $this->assertNotEmpty($component->get('hiddenFields'));
        $this->assertContains('password', $component->hiddenFields['onTable']);
        $this->assertContains('password_confirmation', $component->hiddenFields['onQuery']);
        // ... assert for other hidden fields
    }


    /** @test */
    public function test_datatable_manager_controls_initiated_successfully()
    {
        $component = Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"]);
        $this->assertNotEmpty($component->get('controls'));
        $this->assertContains('addButton', $component->controls);
        $this->assertArrayHasKey('files', $component->controls);
        $this->assertArrayHasKey('bulkActions', $component->controls);
        // ... assert for other controls
    }

    /** @test */
    public function test_datatable_manager_child_components_rendered()
    {
        // Test if child components are rendered
        $component = Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"])
            ->assertSeeLivewire(DataTable::class)
            ->assertSeeLivewire(\App\Modules\Core\Http\Livewire\DataTables\DataTableForm::class)
            ->assertSeeLivewire(\App\Modules\Core\Http\Livewire\DataTables\DataTableControl::class);

        // Assert 'columns' property is correctly initialized
        $componentColumns = $component->get('columns');

        $this->assertIsArray($componentColumns); // Ensure it's an array
        $this->assertNotEmpty($componentColumns); // Ensure it's not empty
    }




    /** @test */
    /*public function test_datatable_manager_page_title_generated_correctly()
    {
        Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"])
            ->assertSee('User Records'); // Or whatever the generated title should be
    }*/


    /** @test */
    public function test_datatable_manager_open_add_relationship_item_modal_event()
    {
        Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User"])
            ->call('openAddRelationshipItemModal', 'App\Models\User') // App\Models\User should be replace by a relationships
            ->assertDispatched('open-child-modal-event'); // Should be optimised to check the passed parameters
    }

}

























/*class DataTableManagerTest extends TestCase
{


    /** @test * /
    public function test_datatable_manager_page_loaded_successfully()
    {
        $response = $this->get('/core/test-data-table-manager'); // Adjust this path to match your route
        $response->assertStatus(200);
    }


    public function test_datatable_manager_model_and_module_name_initiated_successfully(): void
    {
        Livewire::test(DataTableManager::class, ['model' => "App\\Models\\User", "moduleName" => "Core"])
        ->assertSet('model', 'App\\Models\\User')
        ->assertSet('moduleName', 'Core');
    }




    public function test_datatable_manager_table_fields_definitions_initiated_successfully(): void
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
    }

}*/


