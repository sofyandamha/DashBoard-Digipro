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
								<li class="breadcrumb-item active"><?php echo $this->lang->line('update_item_label') ?></li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>
			<section class="content">
				<div class="card">
			        <div class="card-header">
			          <h3 class="card-title"><legend>Object Management</legend></h3>
			        </div>
					<?php
					$attributes = array('id' => 'item-form', 'enctype' => 'multipart/form-data');
					echo form_open(site_url("items/edit/" . $item->id), $attributes);

					?>

					<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label>Object
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('item_name_tooltips') ?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>

								<?php echo form_input(array(
									'name' => 'name',
									'value' => html_entity_decode($item->name),
									'class' => 'form-control',
									'placeholder' => $this->lang->line('item_name_label'),
									'id' => 'name'
								)); ?>

							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line('cat_label') ?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('cat_tooltips') ?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<select class="form-control" name="cat_id" id="cat_id">
									<?php
									foreach ($this->category->get_all($this->shop->get_current_shop()->id)->result() as $cat) {
										echo "<option value='" . $cat->id . "'";
										if ($item->cat_id == $cat->id)
											echo " selected ";
										echo ">" . $cat->name . "</option>";
									}
									?>
								</select>
							</div>

						</div>

						<div class="col-sm-6">
							
							<div class="form-group">
								<label><?php echo $this->lang->line('search_tag_label') ?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('search_tag_tooltips') ?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>

								<?php echo form_input(array(
									'name' => 'search_tag',
									'value' => html_entity_decode($item->search_tag),
									'class' => 'form-control',
									'placeholder' => $this->lang->line('search_tag_label'),
									'id' => ''
								)); ?>

							</div>

							<div class="form-group">
								<label><?php echo $this->lang->line('url_label') ?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('url_tooltips') ?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>

								<?php echo form_input(array(
									'name' => 'url',
									'value' => html_entity_decode($item->url),
									'class' => 'form-control',
									'placeholder' => $this->lang->line('url_label'),
									'id' => ''
								)); ?>

							</div>

							
						</div>
					</div>
					<div class="form-group">
								<label><?php echo $this->lang->line('publish_label') ?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('publish_tooltips') ?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
									:
								</label>
								<?php
								echo form_checkbox("is_published", $item->is_published, $item->is_published);
								?>
							</div>
					<div class="form-group">
								<label><?php echo $this->lang->line('description_label') ?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('item_description_tooltips') ?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<textarea class="form-control" name="description" placeholder="<?php echo $this->lang->line('description_label') ?>" rows="8"><?php echo $item->description; ?></textarea>
							</div>
					<input type="submit" value="<?php echo $this->lang->line('update_button') ?>" class="btn btn-primary" />
					<a class="btn btn-primary" href="<?php echo site_url('items/gallery/' . $item->id); ?>"><?php echo $this->lang->line('goto_gallery_button') ?></a>
					<a class="btn btn-primary" href="<?php echo site_url('items/qr_code/' . $item->id); ?>"><?php echo $this->lang->line('goto_qr_button') ?></a>

					<a href="<?php echo site_url('items'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel_button') ?></a>
					</form><br/>
				</div>
				</div>
			</section>


		</div>
		<script>
			$(document).ready(function() {
				$('#item-form').validate({
					rules: {
						name: {
							required: true,
							minlength: 4,
							remote: {
								url: '<?php echo site_url("items/exists/" . $item->id); ?>',
								type: "GET",
								data: {
									name: function() {
										return $('#name').val();
									},
									sub_cat_id: function() {
										return $('#sub_cat_id').val();
									}
								}
							}
						},
						unit_price: {
							number: true
						}
					},
					messages: {
						name: {
							required: "Please fill item name.",
							minlength: "The length of item name must be greater than 4",
							remote: "item name is already existed in the system"
						},
						unit_price: {
							number: "Only number is allowed."
						}
					}
				});

				$('#cat_id').change(function() {
					var catId = $(this).val();
					$.ajax({
						url: '<?php echo site_url('items/get_sub_cats'); ?>/' + catId,
						method: 'GET',
						dataType: 'JSON',
						success: function(data) {
							$('#sub_cat_id').html("");
							$.each(data, function(i, obj) {
								$('#sub_cat_id').append('<option value="' + obj.id + '">' + obj.name + '</option>');
							});
							$('#name').val($('#name').val() + " ").blur();
						}
					});
				});

				$('#sub_cat_id').on('change', function() {
					$('#name').val($('#name').val() + " ").blur();
				});

				$(function() {
					$("[data-toggle='tooltip']").tooltip();
				});
			});
		</script>