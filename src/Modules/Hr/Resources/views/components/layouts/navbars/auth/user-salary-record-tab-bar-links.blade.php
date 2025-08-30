<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-money-bill-alt"
    url="hr/user-salaries"
    title="User Salaries"
    anchorClasses="{{ ($active == 'user-salaries')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-chart-line"
    url="hr/salary-history"
    title="Salary History"
    anchorClasses="{{ ($active == 'salary-history')? 'active': ''}}"
/>

