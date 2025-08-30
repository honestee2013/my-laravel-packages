<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>

  <x-slot name="pageHeader">
    @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => "Onboarding Management"])
</x-slot>

  <x-core.views::tab-bar>
    <x-hr.views::layouts.navbars.auth.onboarding-management-tab-bar-links active='user-onboarding-status' />
</x-core.views::tab-bar>


    <livewire:data-tables.data-table-manager model="App\Modules\hr\Models\UserOnboardingStatus"
    pageTitle="User Onboarding Status"
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
