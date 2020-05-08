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
							<li class="breadcrumb-item active">Report</li>
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
			              <h3 class="card-title">Report</h3>
			            </div>
			            <!-- /.card-header -->

			            <div class="card-body">
			            	<?php
								echo "Jumlah user saat ini : ".$count_online."<br>";
								echo "Jumlah user logout : ".$count_offline."<br>";
								/* echo "<pre>";
								 print_r($graph_items);
								 echo "</pre>";*/
							?><br>
								<table class="table table-striped table-bordered">
									<tr>
										<th><?php echo $this->lang->line('no_label')?></th>
										<th>Object</th>
										<th><?php echo $this->lang->line('appuser_name_label')?></th>
										<th><?php echo $this->lang->line('date_label')?></th>
										<th></th>
									</tr>
									<?php
										if(!$count=$this->uri->segment(3))
											$count = 0;
										if(isset($touches) && count($touches->result())>0):
											foreach($touches->result() as $touch):					
									?>
											<tr>
												<td><?php echo ++$count;?></td>
												<td><?php echo $this->item->get_info($touch->item_id)->name;?></td>
												<td><?php echo $this->appuser->get_info($touch->appuser_id)->username;?></td>
												<td><?php echo $this->common->date_formatting($touch->added);?></td>
												<td><a href='<?php echo site_url('touches/detail/'.$touch->appuser_id);?>'><?php echo $this->lang->line('detail_label')?></a></td>
											</tr>
											<?php
											endforeach;
										else:
									?>
											<tr>
												<td colspan='5'><?php echo $this->lang->line('no_touches_data_message')?></td>
											</tr>
									<?php
										endif;
									?>
								</table>
								
								<br/>
								<?php
									$pag['anchor_class'] = 'class="page-link" ';
									$this->pagination->initialize($pag);
									echo $this->pagination->create_links();
								?>
								
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>