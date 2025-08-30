<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>

  <x-slot name="pageHeader">
    @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => "Onboarding Management"])
</x-slot>

  <x-core.views::tab-bar>
    <x-hr.views::layouts.navbars.auth.onboarding-management-tab-bar-links active='onboarding-tasks' />
</x-core.views::tab-bar>


    <livewire:data-tables.data-table-manager model="App\Modules\hr\Models\OnboardingTask"
    pageTitle="Onboarding Tasks Overview"
    queryFilters=[]
    :hiddenFields="[
  'onTable' => 
  [
  ],
  'onNewForm' => 
  [
    0 => 'editable',
  ],
  'onEditForm' => 
  [
    0 => 'editable',
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
