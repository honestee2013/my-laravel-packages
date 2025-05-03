<hr class = 'horizontal dark' /> 

@if(auth()->user()?->can('view_user'))
    <x-core.views::layouts.navbars.sidebar-link-item
        iconClasses="fas fa-users sidebar-icon"
        url="user/users"
        title="Manage Users"
    />
@endif


@if(auth()->user()?->can('view_job_title'))
    <x-core.views::layouts.navbars.sidebar-link-item
        iconClasses="fas fa-briefcase sidebar-icon"
        url="user/job-titles"
        title="Job Titles"
    />
@endif


@if(auth()->user()?->can('view_employee_profile'))
    <x-core.views::layouts.navbars.sidebar-link-item
        iconClasses="fas fa-id-card-alt sidebar-icon"
        url="user/employee-profiles"
        title="Employee Profiles"
    />
@endif
