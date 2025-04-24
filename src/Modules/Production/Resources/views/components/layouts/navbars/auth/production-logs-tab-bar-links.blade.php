

<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-dolly"
    url="production/production-logs"
    title="Log Productions"
    anchorClasses="{{ ($active == 'production-logs')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-cube "
    url="production/production-log-inputs"
    title="Log Inputs"
    anchorClasses="{{ ($active == 'production-log-inputs')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-box "
    url="production/production-log-outputs"
    title="Log Outputs"
    anchorClasses="{{ ($active == 'production-log-outputs')? 'active': ''}}"
/>



<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-exclamation-triangle "
    url="production/production-log-downtimes"
    title="Log Downtimes"
    anchorClasses="{{ ($active == 'production-log-downtimes')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fas fa-sync-alt"
    url="production/production-order-progress"
    title="Log Progress"
    anchorClasses="{{ ($active == 'progress')? 'active': ''}}"
/>












{{--<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-cube "
    url="production/production-order-resources"
    title="Production Items"
    anchorClasses="{{ ($active == 'resource')? 'active': ''}}"
/>
--}}


