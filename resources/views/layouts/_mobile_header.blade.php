<!-- HEADER MOBILE-->
<header class="header-mobile d-block d-lg-none bg-black">
    <div class="header-mobile__bar">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <a class="logo d-flex" href="/">
                    <img src="{{asset('assets/images/logo.png')}}" class="logo-nav" />
                    <h5 class="text-yellow my-auto">Athletics Performance</h5>
                </a>
                <button class="hamburger hamburger--slider bg-black" style="color: #ffdc11 !important" type="button">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <nav class="navbar-mobile">
        <div class="container-fluid">
            <ul class="navbar-mobile__list list-unstyled bg-gray">
                <li>
                    <a href="/" class="active">
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
        </div>
    </nav>
</header>
<!-- END HEADER MOBILE-->