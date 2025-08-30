<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-user-slash"
    url="hr/exempted-roles"
    title="Exempted Roles"
    anchorClasses="{{ ($active == 'exempted-roles')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-user-times"
    url="hr/exempted-employee"
    title="Exempted Employee"
    anchorClasses="{{ ($active == 'exempted-employee')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-door-open"
    url="hr/check-in-only-roles"
    title="Check-In-Only Roles"
    anchorClasses="{{ ($active == 'check-in-only-roles')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-user-check"
    url="hr/check-in-only-employee"
    title="Check-In-Only Employee"
    anchorClasses="{{ ($active == 'check-in-only-employee')? 'active': ''}}"
/>

