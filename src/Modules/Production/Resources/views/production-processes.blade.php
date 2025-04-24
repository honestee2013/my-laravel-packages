<x-core.views::layouts.app>
    <x-slot name="sidebar">
        <x-core.views::layouts.navbars.auth.sidebar moduleName="production">
            <x-production.views::layouts.navbars.auth.sidebar-links />
        </x-core.views::layouts.navbars.auth.sidebar>
    </x-slot>
    <livewire:core.data-tables.data-table-manager model="App\\Modules\\Production\\Models\\ProductionProcess"

        pageTitle="Production Process Management"
        recordName="Production Process"

        queryFilters=[]
        :hiddenFields="[
            'onTable' => [],
        ]"
        :queryFilters="[

        ]"

    />

</x-core.views::layouts.app>
