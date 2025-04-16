


<div id="sidebar" class="sidebar border-0 border-radius-xl my-3 fixed-start ms-3 ">

  <ul class="navbar-nav">
    <x-core.views::layouts.navbars.auth.sidebar-header moduleName="{{$moduleName}}" />
        {{ $slot }}
    <x-core.views::layouts.navbars.auth.sidebar-footer moduleName="{{$moduleName}}" />
  </ul>
</div>








