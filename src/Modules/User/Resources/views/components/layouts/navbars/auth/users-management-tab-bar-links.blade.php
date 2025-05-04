
@can('view_user')
    <x-core.views::layouts.navbars.sidebar-link-item
        iconClasses="fas fa-user"
        url="user/users"
        title="Manage Users"
        anchorClasses="{{ ($active == 'users')? 'active': ''}}"
    />
@endcan

@can('view_basic_info')
    <x-core.views::layouts.navbars.sidebar-link-item
        iconClasses="fas fa-address-card"
        url="user/basic-infos"
        title="Basic Info"
        anchorClasses="{{ ($active == 'basic-infos')? 'active': ''}}"
    />
@endcan

