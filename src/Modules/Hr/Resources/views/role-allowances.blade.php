<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>

  <x-slot name="pageHeader">
    @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => "Allowances & Bonuses"])
</x-slot>

  <x-core.views::tab-bar>
    <x-hr.views::layouts.navbars.auth.allowances-and-bonuses-tab-bar-links active='role-allowances' />
</x-core.views::tab-bar>


    <livewire:data-tables.data-table-manager model="App\Modules\hr\Models\RoleAllowance"
    pageTitle="Role Allowances Overview"
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


    <x-slot name="pageFooter">
    @include('core.views::components.layouts.navbars.auth.content-footer', [ ])
</x-slot>

</x-core.views::layouts.app>
