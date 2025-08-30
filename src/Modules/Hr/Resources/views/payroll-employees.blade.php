<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>

  <x-slot name="pageHeader">
    @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => "Payroll Processing"])
</x-slot>

  <x-core.views::tab-bar>
    <x-hr.views::layouts.navbars.auth.payroll-processing-tab-bar-links active='payroll-employees' />
</x-core.views::tab-bar>


    <livewire:data-tables.data-table-manager model="App\Modules\hr\Models\PayrollEmployee"
    pageTitle="Payroll Employees Details"
    queryFilters=[]
    :hiddenFields="[
  'onTable' => 
  [
    0 => 'payroll_run_id',
  ],
  'onNewForm' => 
  [
    0 => 'payroll_run_id',
  ],
  'onEditForm' => 
  [
    0 => 'payroll_run_id',
  ],
  'onQuery' => 
  [
    0 => 'payroll_run_id',
  ],
]"
    :queryFilters="[
]"
/>


    <x-slot name="pageFooter">
    @include('core.views::components.layouts.navbars.auth.content-footer', [ ])
</x-slot>

</x-core.views::layouts.app>
