<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-users"
    url="hr/role-allowances"
    title="Role Allowances"
    anchorClasses="{{ ($active == 'role-allowances')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-people-arrows"
    url="hr/role-bonuses"
    title="Role Bonuses"
    anchorClasses="{{ ($active == 'role-bonuses')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-user-tag"
    url="hr/employee-allowances"
    title="Employee Allowances"
    anchorClasses="{{ ($active == 'employee-allowances')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-trophy"
    url="hr/employee-bonuses"
    title="Employee Bonuses"
    anchorClasses="{{ ($active == 'employee-bonuses')? 'active': ''}}"
/>

