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
							?>
							<br>
							<table id="table" class="table table-striped table-bordered">
					            <thead>
					                <tr>
					                    <th>No</th>
					                    <th>Username</th>
					                    <th>Email</th>
					                    <th>Phone</th>
					                    <th>Card</th>
					                    <th>Category</th>
					                    <th>Status</th>
					                    <th>Created</th>
					                    <th>Detail</th>
					                </tr>
					            </thead>
					            <tbody>
					            </tbody>
					        </table>
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
<script src="<?php echo base_url('assets/AdminLTE/plugins/datatables/jquery.dataTables.js');?>"></script>
<script src="<?php echo base_url('assets/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.js');?>"></script>
<script type="text/javascript">
 
var table;
$(document).ready(function() {
 
    //datatables
    table = $('#table').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('appusers/ajax_list')?>",
            "type": "POST", // ajax source
            data: {
            <?php echo $this->security->get_csrf_token_name();?>: '<?php echo $this->security->get_csrf_hash();?>',
        }
        },
	        "columnDefs": [
		        { 
		            "targets": 8,
		            "render": function(data, type, row, meta){
		               return '<a href="<?php echo site_url('appusers/detail')?>/'+ row[8] +'">Detail</a>';  
		            }
		        }            
		    ] ,
		    "columns": [
			    { "orderable": false },
			    null,
			    { "orderable": false },
			   	{ "orderable": false },
			   	{ "orderable": false },
			    { "orderable": false },
			    { "orderable": false },
			    null,
			    { "orderable": false },
			  ]
			    });
    	table.on( 'draw', function () {
	    $('tr td:nth-child(5)').each(function (){
	          $(this).addClass('small')
			    })
	    $('tr td:nth-child(6)').each(function (){
	          $(this).addClass('small')
			    })
	    $('tr td:nth-child(7)').each(function (){
	          $(this).addClass('small')
			    })
			});

 
});
</script>