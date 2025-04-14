<?php

namespace QuickerFaster\CodeGen\Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Core\Facades\DataTables\DataTableConfig;
use App\Modules\Core\Http\Livewire\DataTables\DataTable;
use App\Models\User; // Or the actual path to your User model

class DataTableTest extends TestCase
{
    use RefreshDatabase;


    protected function getDataTableTestCase($model = "App\\Models\\User") {
        $config = DataTableConfig::getConfigFileFields($model);
        $component = Livewire::test(DataTable::class, $config); // Provide necessary props
        return $component;
    }


    /** @test */
    public function test_datatable_component_renders_table_correctly()
    {
        $this->getDataTableTestCase()
            ->assertSee('name') // Check if columns are rendered
            ->assertSee('email');
    }

    /** @test */
    public function test_datatable_component_sorts_correctly()
    {
        $this->getDataTableTestCase()
            ->call('sortColumn', 'name') // Sort by name
            ->assertSet('sortField', 'name')
            ->assertSet('sortDirection', 'asc') // Default ascending

            ->call('sortColumn', 'name') // Sort by name again (toggle)
            ->assertSet('sortDirection', 'desc'); // Now descending
    }


    /** @test */
    /*public function test_datatable_component_search_works_correctly()
    {
        User::create(['name' => 'Test User 1', 'email' => 'test1@example.com', 'password' => '123']);
        User::create(['name' => 'Another User', 'email' => 'test2@example.com', 'password' => '123']);

        $component = $this->getDataTableTestCase();
        $x = $component->call("changeSearch", "Test");
dd($x, $component->html());


    // Test Livewire component behavior
    Livewire::test(DataTable::class, ['model' => User::class]) // Assuming DataTable expects a 'model'
        ->set('search', 'Test') // Simulate user searching for "Test"
        ->assertSee('Test User 1') // Should see this result
        ->assertDontSee('Another User'); // Should NOT see this result

        // $component->set('search', 'Test');

        // $this->assertTrue(str_contains($component->html(), 'Test User 1'));
        // $this->assertFalse(str_contains($component->html(), 'Another User'));

    }*/



    /** @test */
    /*public function test_datatable_component_pagination_works_correctly()
    {
        User::factory(20)->create(); // Create some users for pagination testing

        Livewire::test(DataTable::class, ['model' => User::class, 'columns' => ['name', 'email']])
            ->set('perPage', 10)
            ->assertSee('name') // Check if data is displayed
            ->assertSee('Showing 1 to 10 of 20 results'); // Basic pagination text check

        // You could add more detailed pagination tests here,
        // like checking the number of page links, etc.
    }


    /** @test */
    /*public function test_datatable_component_bulk_select_all_works_correctly()
    {
        User::factory(5)->create();

        Livewire::test(DataTable::class, ['model' => User::class, 'columns' => ['name', 'email']])
            ->set('selectAll', true)
            ->assertCount(5, $component->selectedRows) // Check if all rows are selected
            ->set('selectAll', false)
            ->assertEmpty($component->selectedRows);
    }*/


    /** @test */
    /*public function test_datatable_component_bulk_select_individual_rows_works_correctly()
    {
        $users = User::factory(3)->create();

        Livewire::test(DataTable::class, ['model' => User::class, 'columns' => ['name', 'email']])
            ->set('selectedRows', [$users[0]->id, $users[2]->id]) // Select first and third rows
            ->assertCount(2, $component->selectedRows)
            ->assertContains($users[0]->id, $component->selectedRows)
            ->assertContains($users[2]->id, $component->selectedRows);
    }*/



    /** @test */
    /*public function test_datatable_component_edit_record_event_dispatched()
    {
        $user = User::factory()->create();

        Livewire::test(DataTable::class, ['model' => User::class, 'columns' => ['name', 'email']])
            ->call('editRecord', $user->id, User::class)
            ->assertDispatched('openEditModalEvent', $user->id, User::class);
    }*/

    /** @test */
    /*public function test_datatable_component_show_detail_event_dispatched()
    {
        $user = User::factory()->create();

        Livewire::test(DataTable::class, ['model' => User::class, 'columns' => ['name', 'email']])
            ->call('showDetail', $user->id)
            ->assertDispatched('openShowItemDetailModalEvent', $user->id);
    }*/

    /** @test */
    /*public function test_datatable_component_delete_record_event_dispatched()
    {
        $user = User::factory()->create();

        Livewire::test(DataTable::class, ['model' => User::class, 'columns' => ['name', 'email']])
            ->call('deleteRecord', $user->id)
            ->assertDispatched('confirmDeleteEvent', $user->id);
    }*/


    /** @test */
    /*public function test_datatable_component_open_link_redirects_correctly()
    {
        $user = User::factory()->create();

        Livewire::test(DataTable::class, ['model' => User::class, 'columns' => ['name', 'email']])
            ->call('openLink', 'users.user.edit', $user->id)
            ->assertRedirect(route('users.user.edit', ['user' => $user->id]));
    }*/


}
