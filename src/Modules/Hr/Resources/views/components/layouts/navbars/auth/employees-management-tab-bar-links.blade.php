<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-briefcase"
    url="hr/employee-profiles"
    title="Employee Profile"
    anchorClasses="{{ ($active == 'employee-profile')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-paperclip"
    url="hr/employee-documents"
    title="Employee Documents"
    anchorClasses="{{ ($active == 'employee-documents')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-heading"
    url="hr/job-titles"
    title="Job Titles"
    anchorClasses="{{ ($active == 'job-titles')? 'active': ''}}"
/>

