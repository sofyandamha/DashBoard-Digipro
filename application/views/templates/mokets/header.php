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
    
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url('assets/AdminLTE/dist/js/demo.js');?>"></script>
    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places&key=
    <?php echo $this->config->item('gmap_api_key');?>'></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <style type="text/css">
      .row {
        display : flex;
        align-items : center;
        margin-bottom: 15px;
    }
    .box {
      height: 20px;
      width: 20px;
      border: 1px solid black;
      margin-right : 5px;
    }

    .yellow {
      background-color: #FCDA31;
    }

    .blue {
      background-color: #0B549F;
    }

    .black {
      background-color: #04083D;
    }
    #container {
      height: 400px; 
    }

    .highcharts-figure, .highcharts-data-table table {
      min-width: 310px; 
      max-width: 800px;
      margin: 1em auto;
    }

    .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
    }
    .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
    }
    .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
    }
    .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
      padding: 0.5em;
    }
    .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
    }
    .highcharts-data-table tr:hover {
      background: #f1f7ff;
    }
    .img {
      background-image: url('../img/bg.png');
    }
    </style>
  </head>

  <body class="hold-transition sidebar-mini layout-fixed">