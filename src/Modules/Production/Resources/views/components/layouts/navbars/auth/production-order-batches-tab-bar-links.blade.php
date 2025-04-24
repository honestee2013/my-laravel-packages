

<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-boxes "
    url="production/production-batches"
    title="Manage Batches"
    anchorClasses="{{ ($active == 'batches')? 'active': ''}}"
/>



<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-cube "
    url="production/production-batch-inputs"
    title="Batch Inputs"
    anchorClasses="{{ ($active == 'inputs')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-box "
    url="production/production-batch-outputs"
    title="Batch Outputs"
    anchorClasses="{{ ($active == 'outputs')? 'active': ''}}"
/>



