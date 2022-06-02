<div class="navbar-custom topnav-navbar topnav-navbar-dark">
    <div class="container-fluid">

        <!-- LOGO -->


        <ul class="list-unstyled topbar-right-menu float-right mb-0">
            <li class="dropdown notification-list topbar-dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle arrow-none" id="topbar-languagedrop" href="all_profile.php"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="dripicons-user"></i>
                    <span class="align-middle">Danh sách sinh viên</span>
                </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle arrow-none" id="topbar-languagedrop" href="list_tasks.php"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="dripicons-document-edit"></i>
                    <span class="align-middle">Danh sách bài tập</span>
                </a>
            </li>

            <li class="dropdown notification-list d-none d-sm-inline-block">
                <a class="nav-link dropdown-toggle nav-user arrow-none" data-toggle="dropdown" id="topbar-userdrop"
                    href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="account-user-avatar">
                        <img src="assets/images/users/avatar.png" alt="user-image" class="rounded-circle">
                    </span>
                    <span>
                        <span class="account-user-name"><?php echo htmlspecialchars($_SESSION['account']); ?></span>
                        <span class="account-position">Intern</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                    aria-labelledby="topbar-userdrop">
                    <!-- item-->
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                    </div>

                    <!-- item-->
                    <a href="profile.php" class="dropdown-item notify-item">
                        <i class="mdi mdi-account-circle mr-1"></i>
                        <span>My Account</span>
                    </a>
                    <!-- item-->
                    <a href="logout.php" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout mr-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </li>

        </ul>
    </div>
</div>