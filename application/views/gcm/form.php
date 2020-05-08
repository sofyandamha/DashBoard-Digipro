			<div class="content-wrapper">
			    <!-- Content Header (Page header) -->
			    <section class="content-header">
			      <div class="container-fluid">
			        <div class="row mb-2">
			          <div class="col-sm-6">
			          </div>
			          <div class="col-sm-6">
			          	<?php
							$this->lang->load('ps', 'english');
						?>
			            <ol class="breadcrumb float-sm-right">
			            	<li class="breadcrumb-item"><a href="<?php echo site_url() . "/dashboard";?>"><?php echo $this->lang->line('dashboard_label')?></a></li>
							<li class="breadcrumb-item active"><?php echo $this->lang->line('gcm_title')?></li>
			            </ol>
			          </div>
			        </div>
			      </div><!-- /.container-fluid -->
			    </section>

			    <!-- Message -->
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

			<?php echo form_open( site_url('shops/push_message'), array( 'id' => 'gcm-form' ));?>

			    <section class="content">
			      <div class="row">
			        <div class="col-12">
			          <div class="card">
			            <div class="card-header">
			              <h3 class="card-title">Report Customer</h3>
			            </div>
			            <!-- /.card-header -->
			            <div class="card-body">
			            		<div class="form-group">
						<label>
							<?php 
								if($this->gcm_token->count_all() > 0) {
									echo $this->lang->line('total_label'); 
									echo $this->gcm_token->count_all();
									
									if($this->gcm_token->count_all() == 1) {
										echo $this->lang->line('device_label'); 
									} else {
										echo $this->lang->line('device_label'); 
									}
									
									echo $this->lang->line('registered_label'); 
								}
								?> 
						</label>
						<br>
						
						<label><?php echo $this->lang->line('gcm_message_label')?> 
							<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('gcm_message_tooltips')?>">
								<span class='glyphicon glyphicon-info-sign menu-icon'>
							</a>
						</label>
						
						<textarea class="form-control" name="message" placeholder="<?php echo $this->lang->line('gcm_message_label')?>" rows="8"></textarea>
					</div>
					<hr/>
					<?php 
						if($this->gcm_token->count_all() > 0) {
					?>
					  
					<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('gcm_send_btn')?></button>
					
					<?php 
						} else {
							echo $this->lang->line('sorry_no_device');
						}
					?>
						</div>
					</div>
					<?php echo form_close();?>
				</div>
				</div>
			</section>
			</div>

			<script>
				$(document).ready(function(){
					$('#gcm-form').validate({
						rules:{
							message:{
								required: true,
								minlength: 1
							}
						},
						messages:{
							message:{
								required: "Please Fill Push Message.",
								minlength: "The length of message must be greater than 1"
							}
						}
					});
				});

				$(function () { $("[data-toggle='tooltip']").tooltip(); });
			</script>