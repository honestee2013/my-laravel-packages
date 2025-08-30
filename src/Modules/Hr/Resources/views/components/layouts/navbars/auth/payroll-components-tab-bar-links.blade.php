<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-gift"
    url="hr/bonus-types"
    title="Bonus Types"
    anchorClasses="{{ ($active == 'bonus-types')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-plus-circle"
    url="hr/allowance-types"
    title="Allowance Types"
    anchorClasses="{{ ($active == 'allowance-types')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-hand-holding-usd"
    url="hr/tax-types"
    title="Tax Types"
    anchorClasses="{{ ($active == 'tax-types')? 'active': ''}}"
/>


<x-core.views::layouts.navbars.sidebar-link-item
    iconClasses="fas fa-list-alt"
    url="hr/deduction-types"
    title="Deduction Types"
    anchorClasses="{{ ($active == 'deduction-types')? 'active': ''}}"
/>

