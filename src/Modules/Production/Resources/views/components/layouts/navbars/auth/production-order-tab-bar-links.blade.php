

<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-list "
    url="production/production-order-requests"
    title="Requests"
    anchorClasses="{{ ($active == 'request')? 'active': ''}}"
/>



<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-box "
    url="production/production-order-items"
    title="Requested Products"
    anchorClasses="{{ ($active == 'items')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-check"
    url="production/production-order-approvals"
    title="Approvals"
    anchorClasses="{{ ($active == 'approval')? 'active': ''}}"
/>




{{--------- ProductionProcessInput Should Serve the same purpose as ProductionOrderItems ------------
<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-cube "
    url="production/production-order-resources"
    title="Resource Allocations"
    anchorClasses="{{ ($active == 'resource')? 'active': ''}}"
/>

-----}}



