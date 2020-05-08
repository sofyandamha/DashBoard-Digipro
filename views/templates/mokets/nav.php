<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" href="<?php echo site_url('profile');?>">
          <i class="fa fa-user-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="<?php echo site_url('logout');?>">
          <i class="fa fa-sign-out-alt"></i>
        </a>
<!--         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
          <div class="dropdown-divider"></div>
          <a href="" class="dropdown-item">
              <p>Sign Out</p>
          </a>
        </div> -->
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->