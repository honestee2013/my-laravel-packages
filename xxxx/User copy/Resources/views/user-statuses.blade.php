<x-core.views::layouts.app>
    <x-slot name="sidebar">
    <x-core.views::layouts.navbars.auth.sidebar moduleName="user">
        <x-user.views::layouts.navbars.auth.sidebar-links />
    </x-core.views::layouts.navbars.auth.sidebar>
</x-slot>





    <livewire:data-tables.data-table-manager model="App\\Modules\\User\\Models\\UserStatus"
    pageTitle="User Status Management" :readOnlyFields="['name']"

/>


    

</x-core.views::layouts.app>
