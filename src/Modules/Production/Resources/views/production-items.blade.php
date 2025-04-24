<x-core.views::layouts.app>
    <x-slot name="sidebar">
        <x-core.views::layouts.navbars.auth.sidebar moduleName="production">
            <x-production.views::layouts.navbars.auth.sidebar-links />
        </x-core.views::layouts.navbars.auth.sidebar>
    </x-slot>
    <livewire:core.data-tables.data-table-manager model="App\\Modules\\Production\\Models\\ProductionItem"

        pageTitle="Production Item Management"
        recordName="Production Item"
        queryFilters=[]
        :hiddenFields="[
            'onTable' => [],
        ]"
        :queryFilters="[

        ]"

    />

</x-core.views::layouts.app>
