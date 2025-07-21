<?php
$menuGroups = \App\Models\MenuGroup::with('menuItems')->get();
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @foreach ($menuGroups as $menuGroup)
        @can($menuGroup->permission_name)
            <li class="nav-item">
                <!-- Check if any menu item within this menu group is active -->
                @php
                    $isActive = false;
                    foreach ($menuGroup->menuItems as $menuItem) {
                        if (request()->is($menuItem->route)) {
                            $isActive = true;
                            break;
                        }
                    }
                @endphp
                <a class="nav-link collapsed @if ($isActive) active @endif" href="#"
                    data-toggle="collapse" data-target="#collapseMenuGroup{{ $menuGroup->id }}" aria-expanded="true"
                    aria-controls="collapseMenuGroup{{ $menuGroup->id }}">
                    <i class="{{ $menuGroup->icon }}"></i>
                    <span>{{ $menuGroup->name }}</span>
                </a>

                <!-- Collapse menu for the group -->
                <div id="collapseMenuGroup{{ $menuGroup->id }}"
                    class="collapse @if ($isActive) show @endif" aria-labelledby="headingTwo"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @foreach ($menuGroup->menuItems as $menuItem)
                            @can($menuItem->permission_name)
                                <a class="collapse-item @if (request()->is($menuItem->route)) active @endif"
                                    href="{{ url($menuItem->route) }}">{{ $menuItem->name }}</a>
                            @endcan
                        @endforeach
                    </div>
                </div>
            </li>
        @endcan
    @endforeach
</ul>
