<div class="content-wrapper">
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
						<li class="breadcrumb-item"><a href="<?php echo site_url() . "/dashboard"; ?>"><?php echo $this->lang->line('dashboard_label') ?></a></li>
						<li class="breadcrumb-item "><a href="<?php echo site_url('items'); ?>"><?php echo $this->lang->line('item_list_label') ?></a></li>
						<li class="breadcrumb-item active"><?php echo $this->lang->line('search_result_label') ?></li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<section class="content">
		<div class="card">
			<div class="card-body">
			
		
		<div class='row'>
			<div class='col-sm-10'>
				<?php
				$attributes = array('class' => 'form-inline');
				echo form_open(site_url('items/search'), $attributes);
				?>
				<div class="form-group">

					<?php echo form_input(array(
						'name' => 'searchterm',
						'value' => html_entity_decode($searchterm),
						'class' => 'form-control',
						'placeholder' => 'Search',
						'id' => ''
					)); ?>

				</div>

				<div class="form-group">
					<select class="form-control" name="cat_id">
						<option value="x"><?php echo $this->lang->line('select_cat_message') ?></option>
						<?php
						foreach ($this->category->get_all()->result() as $cat) {
							echo "<option value='" . $cat->id . "'";
							if ($cat_id == $cat->id) echo " selected ";
							echo ">" . $cat->name . "</option>";
						}
						?>
					</select>
				</div>
				
				<button type="submit" class="btn btn-default">
					<option value="0"><?php echo $this->lang->line('search_button') ?></option>
				</button>
				<a href='<?php echo site_url('items'); ?>' class="btn btn-default"><?php echo $this->lang->line('reset_button') ?></a>
				</form>
			</div>
			<div class='col-sm-2'>
				<a href='<?php echo site_url('items/add'); ?>' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span>
					<?php echo $this->lang->line('add_new_item_button') ?></a>
			</div>
		</div>

		<br />

		<!-- Message -->
		<?php if ($this->session->flashdata('success')) : ?>
			<div class="alert alert-success fade in">
				<?php echo $this->session->flashdata('success'); ?>
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			</div>
		<?php elseif ($this->session->flashdata('error')) : ?>
			<div class="alert alert-danger fade in">
				<?php echo $this->session->flashdata('error'); ?>
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			</div>
		<?php endif; ?>
		<div class="wrapper wrapper-content animated fadeInRight">
			<table class="table table-striped table-bordered">
								<tr>
									<th><?php echo $this->lang->line('no_label')?></th>
									<th>Object Name</th>
				                    <!-- <th></th> -->
									<th><?php echo $this->lang->line('category_name_label')?></th>
									
				                    <th><?php echo $this->lang->line('item_qr_label')?></th>
									<?php 
										if(!$this->session->userdata('is_shop_admin')) { 
											if(in_array('edit',$allowed_accesses)):?>
												<th><?php echo $this->lang->line('edit_label')?></th>
									<?php endif; } else { ?>
												<th><?php echo $this->lang->line('edit_label')?></th>
									<?php } ?>
									
									<?php 
										if(!$this->session->userdata('is_shop_admin')) { 
											if(in_array('delete',$allowed_accesses)):?>
											<th><?php echo $this->lang->line('delete_label')?></th>
									<?php endif; } else { ?>
											<th><?php echo $this->lang->line('delete_label')?></th>
									<?php } ?>
														
									<?php 
										if(!$this->session->userdata('is_shop_admin')) { 	
											if(in_array('publish',$allowed_accesses)):?>
												<th><?php echo $this->lang->line('publish_label')?></th>
									<?php endif; } else { ?>
												<th><?php echo $this->lang->line('publish_label')?></th>
									<?php } ?>
								</tr>
								<?php
									if(!$count=$this->uri->segment(3))
										$count = 0;
									if(isset($items) && count($items->result())>0):
										foreach($items->result() as $item):					
								?>
										<tr>
											<td><?php echo ++$count;?></td>
											<td><?php echo $item->name;?></td>
											<td><?php echo $this->category->get_info($item->cat_id)->name;?></td>
											
				                            <td><?php echo $item->qr_code;?></td>
											<?php 
												if(!$this->session->userdata('is_shop_admin')) { 
													if(in_array('edit',$allowed_accesses)):?>
														<td><a href='<?php echo site_url("items/edit/".$item->id);?>'><i class='fas fa-edit'></i></a></td>
											<?php endif; } else { ?>
														<td><a href='<?php echo site_url("items/edit/".$item->id);?>'><i class='fas fa-edit'></i></a></td>
											<?php } ?>
											
											
											<?php 
												if(!$this->session->userdata('is_shop_admin')) { 
													if(in_array('delete',$allowed_accesses)):?>
														<td><a href='<?php echo site_url("items/delete/".$item->id);?>'><i class='fa fa-trash'></i></a></td>
											<?php endif; } else { ?>
														<td><a href='<?php echo site_url("items/delete/".$item->id);?>'><i class='fa fa-trash'></i></a></td>
											<?php } ?>	
										
											
											<?php 
												if(!$this->session->userdata('is_shop_admin')) { 
													if(in_array('publish',$allowed_accesses)):?>
														<td>
															<?php if($item->is_published == 1):?>
															
																<button class="btn btn-sm btn-primary unpublish"   
																	itemId='<?php echo $item->id;?>'>Yes
																</button>
																
															<?php else:?>
															
																<button class="btn btn-sm btn-danger publish"
																itemId='<?php echo $item->id;?>'>No</button>
															
															<?php endif;?>
														</td>
											<?php endif; } else { ?>
														<td>
															<?php if($item->is_published == 1):?>
															
																<button class="btn btn-sm btn-primary unpublish"   
																	itemId='<?php echo $item->id;?>'>Yes
																</button>
																
															<?php else:?>
															
																<button class="btn btn-sm btn-danger publish"
																itemId='<?php echo $item->id;?>'>No</button>
															
															<?php endif;?>
														</td>
											<?php } ?>
										</tr>
										<?php
										endforeach;
									else:
								?>
										<tr>
											<td colspan='7'><?php echo $this->lang->line('no_item_data_message')?></td>
										</tr>
								<?php
									endif;
								?>
							</table>
		</div>
	</div>
</div>
		<br/>
		<?php
			$pag['anchor_class'] = 'class="page-link" ';
			$this->pagination->initialize($pag);
			echo $this->pagination->create_links();
		?>
	</section>
</div>
<script>
	$(document).ready(function() {
		$(document).delegate('.publish', 'click', function() {

			var btn = $(this);
			var id = $(this).attr('itemId');

			$.ajax({
				url: '<?php echo site_url('items/publish'); ?>/' + id,
				method: 'GET',
				success: function(msg) {
					if (msg == 'true')
						btn.addClass('unpublish').addClass('btn-primary')
						.removeClass('publish').removeClass('btn-danger')
						.html('Yes');
					else
						alert('System error occured. Please contact your system administrator.');
				}
			});
		});

		$(document).delegate('.unpublish', 'click', function() {

			var btn = $(this);
			var id = $(this).attr('itemId');

			$.ajax({
				url: '<?php echo site_url('items/unpublish'); ?>/' + id,
				method: 'GET',
				success: function(msg) {
					if (msg == 'true')
						btn.addClass('publish').addClass('btn-danger')
						.removeClass('unpublish').removeClass('btn-primary')
						.html('No');
					else
						alert('System error occured. Please contact your system administrator.');
				}
			});
		});


		$(document).delegate('.show', 'click', function() {

			var btn = $(this);
			var id = $(this).attr('itemId');

			$.ajax({
				url: '<?php echo site_url('items/showOnHome'); ?>/' + id,
				method: 'GET',
				success: function(msg) {
					if (msg == 'true')
						btn.addClass('no-show').addClass('btn-primary')
						.removeClass('show').removeClass('btn-danger')
						.html('Yes');
					else
						alert('System error occured. Please contact your system administrator.');
				}
			});
		});

		$(document).delegate('.no-show', 'click', function() {

			var btn = $(this);
			var id = $(this).attr('itemId');

			$.ajax({
				url: '<?php echo site_url('items/hideOnHome'); ?>/' + id,
				method: 'GET',
				success: function(msg) {
					if (msg == 'true')
						btn.addClass('show').addClass('btn-danger')
						.removeClass('no-show').removeClass('btn-primary')
						.html('No');
					else
						alert('System error occured. Please contact your system administrator.');
				}
			});
		});
	});
</script>