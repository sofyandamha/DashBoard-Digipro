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
										<td><?php echo $appuser->username;?></td>
										<?php // print_r($appuser); ?>
									</tr>
									<tr>
										<th><?php echo $this->lang->line('email_label')?></th>
										<td><?php echo $appuser->email;?></td>
									</tr>
									<tr>
										<th>Phone</th>
										<td><?php echo $appuser->phone;?></td>
									</tr>
									<tr>
										<th>Card</th>
										<td><?php
										// echo $appuser->id_card;
										$card = $appuser->id_card;
										$str = chunk_split($card, 4 , ' ');
										echo substr_replace($str, str_repeat('x', 4), 10, 4);
										?>
											
										</td>
									</tr>
									<tr>
										<th>Point</th>
										<td><?php echo $this->rating->get_poin_by_id($appuser->id)->total_poin;?></td>
									</tr>
									<!-- <tr>
										<th>Activity User</th>
										<td><?php for ($i=0; $i < count($touch) ; $i++) {  echo $touch[$i]['name']."&nbsp;".","; } ?></td>
									</tr> -->
								</table>
								<br/>
								<table class="table table-striped table-bordered">

											<legend>Activity User</legend>
											<tr>
												<th>Name </th>
												<th>Date</th>

											</tr>
											<?php
											for ($i=0; $i < count($touch) ; $i++) { ?>
												<tr>
													<td><?php print_r($touch[$i]['name']); ?></td>
													<td><?php print_r($touch[$i]['added']); ?></td>

												</tr>

											<?php 	} ?>

								</table>
							</br>
								<a class="btn btn-primary" href="<?php echo site_url('appusers');?>" class="btn"><?php echo $this->lang->line('back_button')?></a>
								
								</div>

							</div>
						</div>
					</div>
				</section>

				</div>


			</div>
