<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="far fa-calendar-alt"
    url="hr/time-off-requests"
    title="Time Off Requests"
    anchorClasses="{{ ($active == 'time-off-requests')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-chart-pie"
    url="hr/leave-balances"
    title="Leave Balances"
    anchorClasses="{{ ($active == 'leave-balances')? 'active': ''}}"
/>

