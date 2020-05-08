<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AR BTN | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/fontawesome-free/css/all.min.css');?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/dist/css/adminlte.min.css');?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  	<script src="<?php echo base_url('js/jquery.js');?>"></script>
	<script src="<?php echo base_url('js/bootstrap.min.js');?>"></script>
	<script src="<?php echo base_url('js/dashboard.js');?>"></script>
	<script src="<?php echo base_url('js/jquery.validate.js');?>"></script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><img src="<?php echo base_url('img/digipro.png');?>" style="opacity: .8" width="200px" height="60px"></a>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
     			 <?php
	        		$attributes = array('id' => 'login-form','method' => 'POST');
	        		echo form_open(site_url('login'), $attributes);
	        	?>
						<?php if($this->session->flashdata('success')):?>
							<div class='alert alert-danger alert-dismissible'>
								<i class="icon fas fa-check"></i>
								<?php echo $this->session->flashdata('success');?>
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							</div>
						<?php elseif($this->session->flashdata('error')):?>
							<div class='alert alert-danger alert-dismissible'>
								<i class="icon fas fa-ban"></i>
								<?php echo $this->session->flashdata('error');?>
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							</div>							
						<?php endif;?>
						<div class="input-group mb-3">
							<input class="form-control" type="text" id="inputEmail" placeholder="Username" name='user_name'>
							<div class="input-group-text">
				              <span class="fas fa-envelope"></span>
				            </div>
						</div>
						
						<div class="input-group mb-3">
							<input class="form-control" type="password" id="inputPassword" placeholder="Password" name='user_pass'>
							<div class="input-group-text">
				              <span class="fas fa-lock"></span>
				            </div>
						</div>
						
						<!--
						<div class="form-group">
						   <input type="radio" name="user_type" value="mokets" checked="checked"/>&nbsp;&nbsp;<label><font color="#fff">MOKETS Admin</font></label>&nbsp;&nbsp;&nbsp;&nbsp;
						   <input type="radio" name="user_type" value="shops" />&nbsp;&nbsp;<label><font color="#fff">Shop Admin</font></label>
						</div>
						-->		
						<button class="btn btn-primary" type="submit">Sign in</button>
										
					<?php echo form_close();  ?>
		<hr>
      <!-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> -->

      <!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url('assets/AdminLTE/plugins/jquery/jquery.min.js');?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/AdminLTE/dist/js/adminlte.min.js');?>"></script>
<script>
		$(document).ready(function(){
			$('#login-form').validate({
				rules:{
					user_name: "required",
					user_pass: "required"
				},
				messages:{
					user_name: "Please fill username.",
					user_pass: "Please fill password"
				}
			});
		});
	</script>
</body>
</html>
