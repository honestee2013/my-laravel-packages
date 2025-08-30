<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-clipboard-check"
    url="hr/onboarding-tasks"
    title="Onboarding Tasks"
    anchorClasses="{{ ($active == 'onboarding-tasks')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-check-square"
    url="hr/user-onboarding-statuses"
    title="User Onboarding Status"
    anchorClasses="{{ ($active == 'user-onboarding-status')? 'active': ''}}"
/>

