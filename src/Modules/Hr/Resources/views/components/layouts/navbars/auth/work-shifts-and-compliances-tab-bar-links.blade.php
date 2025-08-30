<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-coffee"
    url="hr/break-rules"
    title="Break Rules"
    anchorClasses="{{ ($active == 'break-rules')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-clock"
    url="hr/shifts"
    title="Shifts"
    anchorClasses="{{ ($active == 'shifts')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-calendar-day"
    url="hr/days-of-week"
    title="Days of Week"
    anchorClasses="{{ ($active == 'days-of-week')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-clipboard-check"
    url="hr/compliance-standards"
    title="Compliance Standards"
    anchorClasses="{{ ($active == 'compliance-standards')? 'active': ''}}"
/>

