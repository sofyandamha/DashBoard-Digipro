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
							<li class="breadcrumb-item active">Report List</li>
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
			              <h3 class="card-title">Report Customer</h3>
			            </div>
			            <!-- /.card-header -->
			            <div class="card-body">

			            	
									<?php
									$attributes = array('class' => 'form-inline');
									echo form_open(site_url('reports/dompdf'), $attributes);
									?>							  	
									  	<div class="col-xs-6">
										  	<div class="form-group">
												<select name="id"  class="form-control">
												<!-- <option value="0">Select Report</option> -->
												<option value="cs">Customer Management</option>
												<option value="lm">Loyalty Management</option>
												<option value="om">Object Management</option>
												</select> 
											</div>
										  </div>&nbsp;
										<div class="col-xs-6">
											<div class="form-group">
									  			<select name="file_format"  class="form-control">
													<option value="pdf">PDF</option>
													<option value="csv">Excel</option>
												</select>
										  	</div>
									  	</div>&nbsp;
									  	<input type="submit" name="generate" value="Generate"  class="btn btn-default">
									  	<!-- <button type="submit" class="btn btn-default" style="margin-right: 5px;">
						                    <i class="fas fa-print"></i> Generate
						                </button> -->
									</form>
						</div>
					</div>
				</div>
				</div>
			</section>
			</div>