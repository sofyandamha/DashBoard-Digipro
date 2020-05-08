
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Blank Page</h1> -->
           
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Durasi Gambar
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="pieChart" style="height: 300px;"></canvas>
              </div>
              <!-- /.card-body-->
            </div>
        </div>
        <div class="col-md-6">
          <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Durasi Text
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="myDoughnutChart" style="height: 300px;"></canvas>
              </div>
              <!-- /.card-body-->
            </div>
        </div>
        
      </div>
      <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Durasi video</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body">
                    <canvas id="barChart"  width="400" height="200"></canvas>
              </div>
              <!-- /.card-body -->
      </div>
      
      
      <!-- /.card -->

    </section>
  </div>
  
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawGraphChart);
				google.setOnLoadCallback(drawPieChart);

                google.setOnLoadCallback(drawGraphChartUser);
                google.setOnLoadCallback(drawPieChartUser);


                function drawGraphChartUser() {
                    console.log("drawGraphChartUser");
                    var data = google.visualization.arrayToDataTable(<?php echo $graph_users;?>);
                    var options = {
                        title: 'Total user didalam museum',
                        vAxis: {title: 'Users',  titleTextStyle: {color: 'red'}, minValue:0, maxValue:1000},
                        colors:['#e57373'],
                        backgroundColor: { fill:'transparent' }
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('chart_div_user'));
                    chart.draw(data, options);
                }

                function drawPieChartUser() {
                    console.log("drawPieChartUser");
                    var data = google.visualization.arrayToDataTable(<?php echo $pie_users;?>);
                    var options = {
                        title: 'Total user didalam museum',
                        backgroundColor: { fill:'transparent' }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart_user'));
                    chart.draw(data, options);
                }
				
				function drawGraphChart() {
					
					var data = google.visualization.arrayToDataTable(<?php echo $graph_items;?>);
					var options = {
						title: 'Total Touch Counts (All Items From ' + '<?php echo $cat_name;?> and ' + '<?php echo $sub_cat_name;?>)',
						vAxis: {title: 'Items',  titleTextStyle: {color: 'red'}, minValue:0, maxValue:1000},
						colors:['#e57373'],
						backgroundColor: { fill:'transparent' }
					};
					
					var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
					chart.draw(data, options);
				}
				
				function drawPieChart() {
			     	
			     	var data = google.visualization.arrayToDataTable(<?php echo $pie_items;?>);
			     	var options = {
			       		title: 'Top 5 Popular Items From ' + '<?php echo $cat_name;?> and ' + '<?php echo $sub_cat_name;?>)',
			       		backgroundColor: { fill:'transparent' }
			     	};
			
			     	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
			     	chart.draw(data, options);
			   }
			   
			   $('#cat_id').change(function(){
			   	var catId = $(this).val();
			   	$.ajax({
			   		url: '<?php echo site_url('items/get_sub_cats');?>/'+catId,
			   		method: 'GET',
			   		dataType: 'JSON',
			   		success:function(data){
			   			$('#sub_cat_id').html("");
			   			$.each(data, function(i, obj){
			   			    $('#sub_cat_id').append('<option value="'+ obj.id +'">' + obj.name + '</option>');
			   			});
			   			$('#name').val($('#name').val() + " ").blur();
			   		}
			   	});
			   });
			   
			   $('#sub_cat_id').on('change', function(){
			   	$('#name').val($('#name').val() + " ").blur();
			   });
			   
			</script>

