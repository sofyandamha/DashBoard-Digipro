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
							<li class="breadcrumb-item active">Edit Poin</li>
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
			              <legend>Edit Poin</legend>
			            </div>
			            <!-- /.card-header -->
			            <div class="card-body">
								<?php echo form_close(); ?>
						<?php
						$attributes = array('id' => 'appuser-form','enctype' => 'multipart/form-data');
						echo form_open(site_url("poin/edit/".$poin->id), $attributes);
						?>

							<div class="form-group">
								<label>Poin 
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('feed_description_tooltips')?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<?php 
									echo form_input(array(
										'name' => 'poin',
										'value' => '',
										'class' => 'form-control',
										'placeholder' => $this->poins->get_poin_by_id($poin->appuser_id)->poin,
										'id' => 'name',
										'maxlength' => 8
									));
								?>
							</div>
						
						<input type="submit" value="<?php echo $this->lang->line('update_button')?>" class="btn btn-primary"/>
						
						<a href="<?php echo site_url('poin');?>" class="btn btn-primary"><?php echo $this->lang->line('cancel_button')?></a>


							</div>
						</div>
					</div>
				</section>
					
				</div>
			</div>
