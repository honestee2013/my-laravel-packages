<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-fingerprint"
    url="hr/daily-attendance"
    title="Daily Attendance"
    anchorClasses="{{ ($active == 'daily-attendance')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-wallet"
    url="hr/daily-earnings"
    title="Daily Earnings"
    anchorClasses="{{ ($active == 'daily-earnings')? 'active': ''}}"
/>

