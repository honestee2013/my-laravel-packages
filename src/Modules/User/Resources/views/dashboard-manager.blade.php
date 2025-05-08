



    <x-dashboard.views::layouts.dashboards.default-dashboard>

        <x-slot name="mainTitle">
            <strong class="text-info text-gradient">Title </strong> Overview
        </x-slot>
    
        <x-slot name="subtitle">
            <span class="text-primary text-xs fst-italic">Subtitle</span>
        </x-slot>
    
        <x-slot name="controls">
            @include('dashboard.views::components.layouts.dashboards.dashboard-control')
        </x-slot>

        {{------ DASHBOAD BODY CONTENT --------}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-3">
                <livewire:dashboard.visualisation.widgets.cards.icon-card
                    recordModel="App\Models\User"
                    recordName="Total Users"

                    showRecordNameOnly="true"
                    column="id"
                    groupBy="id"
                    aggregationMethod="count"
                    :timeDuration="$timeDuration"
                    iconCSSClass="fas fa-hourglass-start text-lg opacity-10"
                    key="icon-card-1"
                />
            </div>

        </div>


    </x-dashboard.views::layouts.dashboards.default-dashboard>


 




