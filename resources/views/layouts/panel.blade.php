<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}} - {{$website_name}}</title>
    <meta name="robots" content="follow, index, all">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Cesi Intership">
    <meta property="og:description" content="CESI RALLYE">
    <meta name="author" content="Lilian, Nicolas, Alexis">
    <meta property="og:title" content="{{$title}} - {{$website_name}}">
    <meta property="og:type" content="Site">
    <meta property="og:image" content="{{ asset('img/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}"/>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawesome-5/css/all.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('css/admin/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/adminlte.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/overlayScrollbars/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/daterangepicker/daterangepicker.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/dataTables.bootstrap4.min.css') }}">
    <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
</head>
<body class="sidebar-mini layout-fixed" style="height: auto;">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a>
            </li>
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('panel') }}" class="brand-link">
            <span class="brand-text font-weight-light">Cesi Internship</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
            <div class="os-resize-observer-host observed">
                <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
            </div>
            <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
                <div class="os-resize-observer"></div>
            </div>
            <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 284px;"></div>
            <div class="os-padding">
                <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                    <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                        <nav class="mt-2">
                            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-flat nav-child-indent"
                                data-widget="treeview" role="menu" data-accordion="false">

                                <?php

                                $permissions = null;

                                $admin_navbar = [
                                    'Users Management' => [
                                        'icon' => 'fas fa-tachometer-alt',
                                        'menu' => [
                                            'Student' => [
                                                'icon' => 'far fa-circle nav-icon',
                                                'permission' => 'USERS_SHOW_STUDENT',
                                                'route' => route("panel_users", ["student"])
                                            ],
                                            'Delegate' => [
                                                'icon' => 'far fa-circle nav-icon',
                                                'permission' => 'USERS_SHOW_DELEGATE',
                                                'route' => route("panel_users", ["delegate"])
                                            ],
                                            'Pilote' => [
                                                'icon' => 'far fa-circle nav-icon',
                                                'permission' => 'USERS_SHOW_PILOTE',
                                                'route' => route("panel_users", ["pilote"])
                                            ],
                                            'Admin' => [
                                                'icon' => 'far fa-circle nav-icon',
                                                'permission' => 'USERS_SHOW_ADMIN',
                                                'route' => route("panel_users", ["admin"])
                                            ]
                                        ]
                                    ],
                                    'Permissions' => [
                                        'icon' => 'fas fa-tachometer-alt',
                                        'permission' => 'ACCESS_PERMISSIONS',
                                        'route' => route("panel_permissions")
                                    ]
                                ];


                                function checkCurrent($nav)
                                {

                                    foreach ($nav as $name => $value) {
                                        if (isset($value['menu']))
                                            return checkCurrent($value['menu']);
                                        $route = (isset($value['route']) ? $value['route'] : '#');
                                        $current = $route == url()->current();
                                        if ($current == $route)
                                            return true;
                                    }
                                    return false;
                                }

                                function showNav($nav)
                                {
                                    foreach ($nav as $name => $value) {
                                        if (!isset($value['menu']) && !isset($value['route']))
                                            continue;
                                        if (!isset($value['menu']) && isset($value['permission']) && !\Permission::can($value['permission']))
                                            continue;
                                        $currentMenu = false;
                                        if (isset($value['menu'])) {
                                            $currentMenu = checkCurrent($value['menu']) ? "menu-open" : "";
                                            echo '<li class="nav-item has-treeview ' . ($currentMenu ? "menu-open" : "") . '">';
                                        } else
                                            echo '<li class="nav-item">';
                                        $route = (isset($value['route']) ? $value['route'] : '#');
                                        $current = $route == url()->current();
                                        echo '<a class="nav-link' . ($current || $currentMenu ? " active" : "") . '" href="' . $route . '">';
                                        echo '<i class=" ' . $value['icon'] . ' nav-icon"></i>  <p>' . $name;
                                        if (isset($value['menu']))
                                            echo '<i class="fas fa-angle-left right"></i></p>';
                                        else
                                            echo '</p>';
                                        echo '</a>';
                                        if (isset($value['menu'])) {
                                            echo '<ul class="nav nav-treeview">';
                                            showNav($value['menu']);
                                            echo '</ul>';
                                        }
                                        echo '</li>';
                                    }
                                }


                                showNav($admin_navbar);
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
                <div class="os-scrollbar-track">
                    <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
                </div>
            </div>
            <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
                <div class="os-scrollbar-track">
                    <div class="os-scrollbar-handle" style="height: 21.1111%; transform: translate(0px, 0px);"></div>
                </div>
            </div>
            <div class="os-scrollbar-corner"></div>
        </div>
    </aside>


    <div class="content-wrapper">
        <div style="padding: 15px;">
            @yield('content')
        </div>
    </div>

    <footer class="main-footer">
        <strong>Copyright © 2014-<?= date("Y")?> <a href="https://adminlte.io">AdminLTE.io</a> | Cesi
            Internship.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.1.0-rc
        </div>
    </footer>
</div>

<script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/boostrap/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/boostrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/boostrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/admin/adminlte.min.js') }}"></script>


<script>
    function confirmDel(url) {
        if (confirm("Are you sure to delete that ?"))
            window.location.href=''+url+'';
        else
            return false;
    }

    const LOADING_MSG = "Loading";
    const ERROR_MSG = "Error";
    const INTERNAL_ERROR_MSG = "Internal error, please retry";
    const FORBIDDEN_ERROR_MSG = "Access is forbidden"
    const SUCCESS_MSG = "Success";

</script>
<script src="{{ asset('js/form.js') }}"></script>
</body>
</html>

