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
									<li class="breadcrumb-item active"><?php echo $this->lang->line('add_new_item_button') ?></li>
								</ol>
							</div>
						</div>
					</div><!-- /.container-fluid -->
				</section>
				<section class="content">
					<div class="card">
						<?php
						$attributes = array('id' => 'item-form', 'enctype' => 'multipart/form-data');
						echo form_open(site_url('items/add'), $attributes);
						?>
						<div class="card-header">
							<legend>Object</legend>
						</div>

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
										'value' => '',
										'class' => 'form-control',
										'placeholder' => 'object',
										'id' => '',
										'required oninvalid'=>'this.setCustomValidity("please fill out this field")',
										'oninput'=>'setCustomValidity()'
									)); ?>

								</div>

								<!--<div class="form-group">
                            <label><?php /*echo $this->lang->line('item_qr_label')*/ ?>
                                <a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php /*echo $this->lang->line('item_qr_tooltips')*/ ?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
                                </a>
                            </label>

                            <?php /*echo form_input(array(
                                'name' => 'qr_code',
                                'value' => '',
                                'class' => 'form-control',
                                'placeholder' => $this->lang->line('item_qr_label'),
                                'id' => ''
                            ));

                            */ ?>


                        </div>-->


								<div class="form-group">
									<label>Category
										<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('cat_tooltips') ?>" >
											<span class='glyphicon glyphicon-info-sign menu-icon'>
										</a>
									</label>
									<select class="form-control" name="cat_id" id="cat_id">
										
										<?php
										$categories = $this->category->get_all();
										foreach ($categories->result() as $cat)
											echo "<option value='" . $cat->id . "'>" . $cat->name . "</option>";
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
										'value' => '',
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
										'value' => '',
										'class' => 'form-control',
										'placeholder' => $this->lang->line('url_label'),
										'id' => ''
									)); ?>

								</div>
								
							</div>
						</div>
						<div class="form-group">
									<label><?php echo $this->lang->line('description_label') ?>
										<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('item_description_tooltips') ?>">
											<span class='glyphicon glyphicon-info-sign menu-icon'>
										</a>
									</label>
									<textarea class="form-control" name="description" placeholder="<?php echo $this->lang->line('description_label') ?>" rows="8"></textarea>
						</div>
						<input type="submit" name="save" value="<?php echo $this->lang->line('save_button') ?>" class="btn btn-primary" />
						<input type="submit" name="gallery" value="<?php echo $this->lang->line('save_go_button') ?>" class="btn btn-primary" />
						<input type="submit" name="qr_code" value="<?php echo $this->lang->line('save_gr_button') ?>" class="btn btn-primary" />

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
									url: '<?php echo site_url("items/exists"); ?>',
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
								required: "Please fill item Name.",
								minlength: "The length of item Name must be greater than 4",
								remote: "Item Name is already existed in the system"
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