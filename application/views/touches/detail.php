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
							<li class="breadcrumb-item active"><?php echo $this->lang->line('appuser_info_label')?></li>
			            </ol>
			          </div>
			        </div>
			      </div><!-- /.container-fluid -->
			    </section>
				<section class="content">
			      <div class="row">
			        <div class="col-12">
			          <div class="card">
			            <div class="card-header">
			              <legend><?php echo $this->lang->line('appuser_detail_label')?></legend>
			            </div>
			            <!-- /.card-header -->
			            <div class="card-body">
								<?php echo form_close(); ?>
								
							
								<table class="table table-striped table-bordered">
									<tr>
										<th><?php echo $this->lang->line('username_label')?></th>
										<td><?php echo $touches->item_id;?></td>
									</tr>
									<tr>
										<th><?php echo $this->lang->line('email_label')?></th>
										<td><?php echo $touches->appuser_id;?></td>
									</tr>
									<tr>
										<th><?php echo $this->lang->line('aboutme_label')?></th>
										<td><?php echo $touch->about_me;?></td>
									</tr>
								</table>
								<br>
								<a class="btn btn-primary" href="<?php echo site_url('appusers');?>" class="btn"><?php echo $this->lang->line('back_button')?></a>
								</div>

							</div>
						</div>
					</div>
				</section>
					
				</div>
					
				
			</div>
