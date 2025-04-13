<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
        <x-hr.views::layouts.navbars.auth.sidebar-links />
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>

    

    <livewire:core.data-tables.data-table-manager model="App\Modules\hr\Models\Department"
    pageTitle="Departments Management"
    queryFilters=[]
    :hiddenFields="[
  'onTable' => 
  [
  ],
  'onNewForm' => 
  [
  ],
  'onEditForm' => 
  [
  ],
  'onQuery' => 
  [
  ],
]"
    :queryFilters="[
]"
/>


    
</x-core.views::layouts.app>
