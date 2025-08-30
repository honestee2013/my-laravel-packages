<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>

  <x-slot name="pageHeader">
    @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => "Payroll Processing"])
</x-slot>

  <x-core.views::tab-bar>
    <x-hr.views::layouts.navbars.auth.payroll-processing-tab-bar-links active='payroll_runs' />
</x-core.views::tab-bar>


    <livewire:data-tables.data-table-manager model="App\Modules\hr\Models\PayrollRun"
    pageTitle="Payroll Runs Overview"
    queryFilters=[]
    :hiddenFields="[
  'onTable' => 
  [
    0 => 'roleAllowances',
    1 => 'roleBonuses',
    2 => 'empployeeAllowances',
    3 => 'empployeeBonuses',
    4 => 'roleDeductions',
    5 => 'employeeDeductions',
  ],
  'onNewForm' => 
  [
    0 => 'editable',
    1 => 'status',
    2 => 'created_by',
    3 => 'created_at',
    4 => 'approved_by',
    5 => 'approved_at',
    6 => 'paid_by',
    7 => 'paid_at',
    8 => 'cancelled_by',
    9 => 'cancelled_at',
    10 => 'roleAllowances',
    11 => 'roleBonuses',
    12 => 'empployeeAllowances',
    13 => 'empployeeBonuses',
    14 => 'roleDeductions',
    15 => 'employeeDeductions',
  ],
  'onEditForm' => 
  [
    0 => 'editable',
    1 => 'status',
    2 => 'created_by',
    3 => 'created_at',
    4 => 'approved_by',
    5 => 'approved_at',
    6 => 'paid_by',
    7 => 'paid_at',
    8 => 'cancelled_by',
    9 => 'cancelled_at',
    10 => 'roleAllowances',
    11 => 'roleBonuses',
    12 => 'empployeeAllowances',
    13 => 'empployeeBonuses',
    14 => 'roleDeductions',
    15 => 'employeeDeductions',
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
