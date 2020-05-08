<!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url('img/fokhwar.png');?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $this->user->get_logged_in_user_info()->user_name;?></a>
          <?php $logged_in_user = $this->user->get_logged_in_user_info();?>
          <!-- <p class="text-muted small"><?php /* echo $this->role->get_name($logged_in_user->role_id); */?></p> -->
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
            <a href="<?php echo site_url('dashboard');?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <!-- <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php /* echo site_url(users);*/?> " class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registered User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php /* echo site_url(profile);*/?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Edit Profile</p>
                </a>
              </li>
            </ul>
          </li> -->
          <li class="nav-item">
            <a href="<?php echo site_url(appusers);?>" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Customer Management
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url(poin);?>" class="nav-link">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Loyalty Management
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url(items);?>" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Object Management
              </p>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="<? /*php echo site_url(categories);*/?>" class="nav-link">
              <i class="nav-icon fas fa-store-alt"></i>
              <p>
                Promo Management
              </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="<?php echo site_url(reports);?>" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Report Management
              </p>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="<?php echo site_url(notification);?>" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Notification Management
              </p>
            </a>
          </li> -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Notification
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo site_url(notification);?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Send Notification</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo site_url(listnotification);?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Notification list</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>


      </nav>
