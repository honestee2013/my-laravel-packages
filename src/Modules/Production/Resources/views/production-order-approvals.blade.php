
<x-core.views::layouts.app>
    <x-slot name="sidebar">
        <x-core.views::layouts.navbars.auth.sidebar moduleName="production">
            <x-production.views::layouts.navbars.auth.sidebar-links />
        </x-core.views::layouts.navbars.auth.sidebar>
    </x-slot>

    <x-slot name="pageHeader">
        @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => "Production Order Management"])
    </x-slot>

    <x-core.views::tab-bar>
        <x-production.views::layouts.navbars.auth.production-order-tab-bar-links active='approval'  />
    </x-core.views::tab-bar>

    <livewire:core.data-tables.data-table-manager model="App\\Modules\\Production\\Models\\ProductionOrderApproval"
        pageTitle="Production Order Approvals"
        queryFilters=[]
        :hiddenFields="[
            'onTable' => [],
        ]"
        :queryFilters="[
            ['status_id', '=', app('App\Modules\Core\Models\Status')->where('name', 'pending')->first()->id]
        ]"
    />

    <x-slot name="pageFooter">
        @include('core.views::components.layouts.navbars.auth.content-footer', [ ])
    </x-slot>
</x-core.views::layouts.app>


