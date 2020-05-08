<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>BTN DIGIPRO</title>

    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/fontawesome-free/css/all.min.css');?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css');?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css');?>">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/jqvmap/jqvmap.min.css');?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/dist/css/adminlte.min.css');?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css');?>">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/daterangepicker/daterangepicker.css');?>">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/summernote/summernote-bs4.css');?>">

    <!-- Header Multi dan ITEMs -->

    <script src="<?php echo base_url('assets/AdminLTE/plugins/jquery/jquery.min.js');?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/jquery-ui/jquery-ui.min.js');?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <!-- ChartJS -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/chart.js/Chart.min.js');?>" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/sparklines/sparkline.js');?>"></script>
    <!-- JQVMap -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/jqvmap/jquery.vmap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/AdminLTE/plugins/jqvmap/maps/jquery.vmap.usa.js');?>"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/jquery-knob/jquery.knob.min.js');?>"></script>
    <!-- daterangepicker -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/moment/moment.min.js');?>"></script>
    <script src="<?php echo base_url('assets/AdminLTE/plugins/daterangepicker/daterangepicker.js');?>"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js');?>"></script>
    <!-- Summernote -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/summernote/summernote-bs4.min.js');?>"></script>
    <!-- overlayScrollbars -->
    <script src="<?php echo base_url('assets/AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js');?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url('assets/AdminLTE/dist/js/adminlte.js');?>"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="<?php echo base_url('assets/AdminLTE/dist/js/pages/dashboard.js');?>"></script>
</head>
	<body class="hold-transition sidebar-mini layout-fixed">
		
		
<div class="wrapper">
  <!-- Navbar -->
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
        <a class="nav-link" href="<?php echo site_url('gcm');?>">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="<?php echo site_url('profile');?>">
          <i class="fa fa-user-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="<?php echo site_url('logout');?>">
          <i class="fa fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
  	 <a href="#" class="brand-link">
      <img src="<?php echo base_url('img/btn1.jpg');?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">BTN DIGIPRO</span>
    </a>
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
          <li class="nav-item">
            <a href="<?php echo site_url(reports);?>" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Report Management
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo site_url(notification);?>" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Notification Management
              </p>
            </a>
          </li>
        </ul>


      </nav>
  </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>404 Error Page</h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Home</a></li>
              <li class="breadcrumb-item active">404 Error Page</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <!-- <h2 class="headline text-warning"> 404</h2> -->
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
        <img src="<?php echo base_url('img/404.png');?>" alt="AdminLTE Logo" class="rounded float-left" style="width: 80%;">
        <div class="error-content">
          <a href="<?php echo site_url('dashboard'); ?>" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Back to Dashboard</a>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2019-2020 <a href="https://www.btn.co.id/">Bank BTN</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
		
	</body>
	