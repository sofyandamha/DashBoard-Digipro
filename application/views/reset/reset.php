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
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
     			 <?php
	     		$attributes = array('id' => 'login-form');
	     		echo form_open(site_url('reset/'.$code), $attributes);
	     		?>
						<?php if($this->session->flashdata('success')): ?>
						<div class="alert alert-success fade in">
							<?php echo $this->session->flashdata('success');?>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						</div>
					<?php elseif($this->session->flashdata('error')):?>
						<div class="alert alert-danger fade in">
							<?php echo $this->session->flashdata('error');?>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						</div>
					<?php endif;?>
					
					<div class="form-group">
						<label>Password</label>
						<input class="form-control" type="password" id="password" placeholder="Password" name='password'>
					</div>
					
					<div class="form-group">
						<label>Confirm Password</label>
						<input class="form-control" type="password" id="conf_password" placeholder="Password" name='conf_password'>
					</div>
					
					<button class="btn btn-primary" type="submit">Save</button>
				</form>
										
					<?php echo form_close();  ?>
		<hr>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
	<script>
		$(document).ready(function(){
			$('#login-form').validate({
				rules:{
					password:{
						required: true,
						minlength: 4
					},
					conf_password:{
						required: true,
						equalTo: '#password'
					}
				},
				messages:{
					password:{
						required: "Please fill Password.",
						minlength: "Password must be greater than 4 characters."
					},
					conf_password:{
						required: "Please fill confirm password",
						equalTo: "Confirm Password do not match"
					}
				}
			});
		});
	</script>
<script src="<?php echo base_url('assets/AdminLTE/plugins/jquery/jquery.min.js');?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/AdminLTE/dist/js/adminlte.min.js');?>"></script>
</body>
</html>