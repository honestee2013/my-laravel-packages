<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-file-invoice-dollar"
    url="hr/role-taxes"
    title="Role Taxes"
    anchorClasses="{{ ($active == 'role-taxes')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fas fa-minus-circle"
    url="hr/role-deductions"
    title="Role Deductions"
    anchorClasses="{{ ($active == 'role-deductions')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-file-invoice-dollar"
    url="hr/employee-taxes"
    title="Employee Taxes"
    anchorClasses="{{ ($active == 'employee-taxes')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-minus"
    url="hr/employee-deductions"
    title="Employee Deductions"
    anchorClasses="{{ ($active == 'employee-deductions')? 'active': ''}}"
/>

