<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>

  <x-slot name="pageHeader">
    @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => "Employees Management"])
</x-slot>

  <x-core.views::tab-bar>
    <x-hr.views::layouts.navbars.auth.employees-management-tab-bar-links active='employee-documents' />
</x-core.views::tab-bar>


    <livewire:data-tables.data-table-manager model="App\Modules\hr\Models\EmployeeDocument"
    pageTitle="Employee Documents"
    queryFilters=[]
    :hiddenFields="[
  'onTable' => 
  [
    0 => 'file_path',
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


    <x-slot name="pageFooter">
    @include('core.views::components.layouts.navbars.auth.content-footer', [ ])
</x-slot>

</x-core.views::layouts.app>
