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
							<li class="breadcrumb-item active"><?php echo $this->lang->line('appuser_list_label')?></li>
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
			              <h3 class="card-title">App User</h3>
			            </div>
			            <!-- /.card-header -->
			            <div class="card-body">
			            	<?php
								echo "Jumlah user Active : <b>".$count_online."</b><br>";
								echo "Jumlah user Sign Out : <b>".$count_offline."</b><br>";
								/* echo "<pre>";
								 print_r($graph_items);
								 echo "</pre>";*/
							?><br>
							<?php
								$attributes = array('class' => 'form-inline');
								echo form_open(site_url('appusers/search'),$attributes);
							?>
								<div class="form-group">
							   		<?php echo form_input(array(
							   			'name' => 'searchterm',
							   			'value' => '',
							   			'class' => 'form-control',
							   			'placeholder' => $this->lang->line('search_message')
							   		)); ?>

							  	</div>&nbsp;
							  	<button type="submit" class="btn btn-default"><?php echo $this->lang->line('search_button')?></button>
							<?php echo form_close(); ?>
							<br>
							<table id="example2" class="table table-striped table-bordered">
								<tr>
									<th><?php echo $this->lang->line('no_label')?></th>
									<th><?php echo $this->lang->line('username_label')?></th>
									<th><?php echo $this->lang->line('email_label')?></th>
									<th>Phone</th>
									<th>Card</th>
				                    <th>Status</th>
				                    <th>Created</th>
									<th><?php echo $this->lang->line('detail_label')?></th>

									<?php if(in_array('ban',$allowed_accesses)):?>
										<th><?php echo $this->lang->line('ban_label')?></th>
									<?php endif;?>
								</tr>
								<?php
									if(!$count=$this->uri->segment(3))
										$count = 0;
									if(isset($appusers) && count($appusers->result())>0):
										foreach($appusers->result() as $appuser):					
								?>
										<tr>
											<td><?php echo ++$count;?></td>
											<td><?php echo $appuser->username;?></td>
											<td><?php echo $appuser->email;?></td>
											<td><?php echo $appuser->phone;?></td>
											<td><?php
											$card = $appuser->id_card;
											$str = chunk_split($card, 4, ' ');
											echo '<p class="small">'.substr_replace($str, str_repeat("x", 4), 10, 4)."</p>";?>
												
											</td>

				                            <td><?php if($appuser->online == 1):?>
				                            <?php echo "active"?>
												<?php else:?>
													<?php
													echo '<p class="small">'.$this->appuser->ago($appuser->co)."</p>";
													?>
												<?php endif;?>
											</td>
											<td><?php echo date("d-m-Y", strtotime($appuser->added));?></td>
											<td><a href='<?php echo site_url('appusers/detail/'.$appuser->id);?>'><?php echo $this->lang->line('detail_label')?></a></td>
											<?php if(in_array('ban',$allowed_accesses)):?>
											<td>
												<?php if($appuser->is_banned == 1):?><button class="btn btn-sm btn-danger unban" 
													appuserid='<?php echo $appuser->id;?>'><?php echo $this->lang->line('unban_label')?></button>
												<?php else:?><button class="btn btn-sm btn-primary ban" 
												   appuserid='<?php echo $appuser->id;?>'><?php echo $this->lang->line('ban_label')?></button><?php endif;?>
											</td>

											<?php endif;?>
										</tr>
										<?php
										endforeach;
									else:
								?>
										<tr>
											<td colspan='7'>
												<span class='glyphicon glyphicon-warning-sign menu-icon'></span>
												<?php echo $this->lang->line('no_appuser_data_message')?>
											</td>
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
			

<script>
$(document).ready(function(){
	$(document).delegate('.ban','click',function(){
		var btn = $(this);
		var id = $(this).attr('appuserid');
		$.ajax({
			url: '<?php echo site_url('appusers/ban');?>/'+id,
			method:'GET',
			success:function(msg){
				if(msg == 'true')
					btn.addClass('unban').addClass('btn-danger').removeClass('btn-primary').removeClass('ban').html('Unban');
				else
					alert('System error occured. Please contact your system administrator.');
			}
		});
	});
	
	$(document).delegate('.unban','click',function(){
		var btn = $(this);
		var id = $(this).attr('appuserid');
		$.ajax({
			url: '<?php echo site_url('appusers/unban');?>/'+id,
			method:'GET',
			success:function(msg){
				if(msg == 'true')
					btn.addClass('ban').addClass('btn-primary').removeClass('btn-danger').removeClass('unban').html('Ban');
				else
					alert('System error occured. Please contact your system administrator.');
			}
		})
	});
});
</script>

<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>