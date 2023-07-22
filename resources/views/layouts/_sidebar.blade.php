<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block bg-black ">
    <div class="logo">
        <a href="/" class="d-flex ">
            <img src="{{asset('assets/images/logo.png')}}" class="logo-nav" />
            <h5 class="my-auto text-yellow">Athletics Performance</h5>
        </a>
    </div>
    <div class=" menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <li class="active">
                    <a href="/">
                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>
                <li>
                    <a href="/meetings" class="text-white">
                        <i class="fas fa-chart-bar"></i>Meetings</a>
                </li>
                <li>
                    <a href="/events" class="text-white">
                        <i class="fas fa-chart-bar"></i>Events</a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow text-white" href="#">
                        <i class="fas fa-copy"></i>Users</a>
                    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list bg-black">
                        <li>
                            <a href="/users" class="text-white">Users</a>
                        </li>
                        <li>
                            <a href="/athletes" class="text-white">Athletes</a>
                        </li>
                        <li>
                            <a href="/competitors" class="text-white">Competitors</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/records" class="text-white">
                        <i class="fas fa-chart-bar"></i>Records</a>
                </li>
                <li>
                    <a href="/results" class="text-white">
                        <i class="fas fa-chart-bar"></i>Results</a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<!-- END MENU SIDEBAR-->