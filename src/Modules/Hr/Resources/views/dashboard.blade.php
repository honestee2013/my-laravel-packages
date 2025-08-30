
<x-core.views::layouts.app>
    <x-slot name="sidebar">
        <x-core.views::layouts.navbars.auth.sidebar moduleName="hr">
        </x-core.views::layouts.navbars.auth.sidebar>
    </x-slot>


    <x-slot name="pageHeader">
        @include('core.views::components.layouts.navbars.auth.content-header', [ "pageTitile" => ""])
    </x-slot>



    <livewire:dashboard.dashboard-manager />



    <x-slot name="pageFooter">
        @include('core.views::components.layouts.navbars.auth.content-footer', [ ])
    </x-slot>

































    {{--<livewire:dashboard.livewire.visualisation.widgets.cards.live-card />
    <livewire:dashboard.livewire.visualisation.widgets.counters.count-up count-to="23980" />

    @php
        $duration = "+10 year";
    @endphp
    <livewire:dashboard.livewire.visualisation.widgets.counters.count-down :end-time="strtotime($duration)" />


    <livewire:dashboard.livewire.visualisation.widgets.charts.chart

    chart-id="myChart"
    chart-type="line"
    :chart-data="[
        'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        'datasets' => [
            [
                'label' => 'My First Dataset',
                'data' => [65, 59, 80, 81, 56, 55, 40],
                'fill' => false,
                'borderColor' => 'rgb(75, 192, 192)',
                'tension' => 0.1
            ]
        ]
    ]"
    :chart-options="[
        'responsive' => true,
        'plugins' => [
            'legend' => [
                'position' => 'top',
            ],
        ],
        'scales' => [
            'x' => [
                'beginAtZero' => true
            ],
            'y' => [
                'beginAtZero' => true
            ]
        ]
    ]"
/>





<livewire:dashboard.livewire.visualisation.widgets.progresses.progress-bar
    :progress="40"
    label="Loading"
    color="success"

/>


<livewire:dashboard.livewire.visualisation.widgets.steppers.stepper-wizard
    :steps="[
        ['label' => 'Step 1', 'content' => '<p>Content for Step 1</p>'],
        ['label' => 'Step 2', 'content' => '<p>Content for Step 2</p>'],
        ['label' => 'Step 3', 'content' => '<p>Content for Step 3</p>'],
        ['label' => 'Step 4', 'content' => '<p>Content for Step 3</p>'],
        ['label' => 'Step 5', 'content' => '<p>Content for Step 3</p>'],
    ]"
/>


--}}






</x-core.views::layouts.app>


