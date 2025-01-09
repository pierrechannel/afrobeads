<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="">
        <span class="d-none d-lg-block">tujeSoft</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="GET" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <!-- Notification Nav -->
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li class="notification-item"><i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>
            <!-- Add More Notifications Here -->
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>
          </ul>
        </li>

        <!-- Messages Nav -->
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <!-- Add message items here -->
            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>
          </ul>
        </li>

        <!-- Profile Nav -->
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">K. Anderson</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Kevin Anderson</h6>
              <span>Web Designer</span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-person"></i><span>My Profile</span></a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-box-arrow-right"></i><span>Sign Out</span></a></li>
          </ul>
        </li>
      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
