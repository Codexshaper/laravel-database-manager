<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar">
    <div class="logo">
        <a href="#"><img src="{{ dbm_asset('img/logo.png') }}" alt="" class="img-responsive"></a>
    </div>
    <div class="sidebar-scroll">
        <nav>
            <ul class="nav">
                <li class="menu-title"> menus   </li>
                <li><a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a></li>
                <li>
                    <a href="#subPages" data-toggle="collapse" class="collapsed"><i class="fas fa-th-large"></i> <span>Pages</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                    <div id="subPages" class="collapse ">
                        <ul class="nav">
                            <li><a href="#" class="">Profile</a></li>
                            <li><a href="#" class="">Login</a></li>
                            <li><a href="#" class="">Lockscreen</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#database" data-toggle="collapse" class="collapsed"><i class="fas fa-th-large"></i> <span>Database</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
                    <div id="database" class="collapse ">
                        <ul class="nav">
                            <li><a href="{{ route('dbm.table') }}" class="">Table</a></li>
                            <li><a href="{{ route('dbm.crud') }}" class="">Crud</a></li>
                            <li><a href="{{ route('dbm.permission') }}" class="">Permission</a></li>
                            <li><a href="{{ route('dbm.backup') }}" class="">Backup</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- END LEFT SIDEBAR -->
