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
			              <h3 class="card-title">Notification</h3>
			            </div>
			            <!-- /.card-header -->
			            <div class="card-body">
			
							<?php
								$attributes = array('id' =>'formKirimNotif', 'style' => 'display:none');
								echo form_open(site_url('sendNotification'),$attributes);
							?>										
							<div class="form-group">
								<label for="email">Judul notifikasi:</label>
								<input type="text" name="title" class="form-control" id="email" required>
							</div>
							<div class="form-group" style="display:none">
								<label for="to"></label>
								<input type="text" name="to" class="form-control" id="to">
							</div>
							<div class="form-group">
							<label for="comment">Isi notifikasi:</label>
							<textarea class="form-control" name="body" rows="5" id="comment" required></textarea>
							</div>
							<div class="form-group">
								<label for="link">Link (opsional):</label>
								<input type="text"  name="link" class="form-control" id="link">
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
							<?php echo form_close(); ?>
							<br>
							<!-- <?php
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
							<br> -->
							<table id="example2" class="table table-striped table-bordered">
								<tr>
									<th>Title</th>
									<th>Body</th>
									<th>Target users</th>
									<th>Reach users</th>				                  
																	

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
											<td><?php echo $appuser->title;?></td>
											<td><?php echo $appuser->body;?></td>
											<td><?php echo $appuser->total_target_user;?></td>
											<td><?php echo $appuser->reach_user;?></td>
				                           
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
var arrayRegId = new Array();

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
function checkThis(element,id,regid){
	if(id==0){
		if(element.checked){
			$('.checkboxs').attr('disabled', 'disabled');
			arrayRegId.push(regid);	
		}else{
			$('.checkboxs').removeAttr('disabled');
			arrayRegId.splice( arrayRegId.indexOf(regid), 1 );
		}
	}else{
		if(element.checked){
			arrayRegId.push(regid);	
		}else{
			arrayRegId.splice( arrayRegId.indexOf(regid), 1 );
		}		
	}
	if(arrayRegId.length > 0){
		$('#formKirimNotif').show('slow');
	} else {
		$('#formKirimNotif').hide('slow');
	}
	$("#to").val(arrayRegId);
	//console.log(arrayRegId);
}
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