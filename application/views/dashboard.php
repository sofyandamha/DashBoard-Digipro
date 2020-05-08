<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Welcome, <?php echo $this->user->get_logged_in_user_info()->user_name;?>!</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-md-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $this->appuser->count_all($this->shop->get_current_shop()->id);?></h3>

                <p>Customer Management</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
            </div>
          </div>          <!-- ./col -->
          <div class="col-md-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $this->item->count_all();?></h3>

                <p>Object Management</p>
              </div>
              <div class="icon">
                <i class="ion ion-pinpoint"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                  <h3 class="card-title">Durasi Text</h3>
              </div>
              <div class="card-body">
                <canvas id="durasiText" style="height: 300px;"></canvas>

              </div>
            </div>
          </div> -->
          <!-- /.col-md-6 -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                  <h3 class="card-title">Durasi Gambar</h3>
              </div>
              <div class="card-body">
                <figure class="highcharts-figure">
                    <div id="gambar"></div>
                    <div class="row small">
                  <div class='box yellow'></div>
                  <span>= Menit 1-3 Total : 45, Percentage 54.8%</span>
                </div>

                <div class="row small">
                  <div class='box blue'></div>
                  <span>= Menit 3-5 Total : 25, Percentage 29.9%</span>
                </div>

                <div class="row small">
                  <div class='box black'></div>
                  <span>= Menit 5 > Total : 12, Percentage 15.3%</span>
                </div>
                </figure>
                
            </div>
              </div>

          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                  <h3 class="card-title">Durasi Video</h3>
              </div>
              <div class="card-body">
                <figure class="highcharts-figure">
                    <div id="video"></div>
                    <div class="row small">
                  <div class='box yellow'></div>
                  <span>= Menit 1-3 Total : 25, Percentage 26.3%</span>
                </div>

                <div class="row small">
                  <div class='box blue'></div>
                  <span>= Menit 3-5 Total : 15, Percentage 15.8%</span>
                </div>

                <div class="row small">
                  <div class='box black'></div>
                  <span>= Menit 5 > Total : 55, Percentage 57.9%</span>
                </div>
                </figure>
            </div>
              </div>
              

          </div>

          <!-- /.col-md-6 -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                  <div class="card-header">
                      <h3 class="card-title">Top View</h3>
                  </div>
                  <div class="card-body">
                      <figure class="highcharts-figure">
                          <div id="container" style="width: 900px"></div>
                          <div id="sliders">
                              <table>
                                  <tr>
                                      <td><label for="alpha">Alpha Angle</label></td>
                                      <td><input id="alpha" type="range" min="0" max="45" value="15"/> <span id="alpha-value" class="value"></span></td>
                                  </tr>
                                  <tr>
                                      <td><label for="beta">Beta Angle</label></td>
                                      <td><input id="beta" type="range" min="-45" max="45" value="15"/> <span id="beta-value" class="value"></span></td>
                                  </tr>
                                  <tr>
                                      <td><label for="depth">Depth</label></td>
                                      <td><input id="depth" type="range" min="20" max="100" value="50"/> <span id="depth-value" class="value"></span></td>
                                  </tr>
                              </table>
                          </div>
                      </figure>
                  </div>
                </div>

            </div>
          
        </div>

      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
    $sub_cat_id = 0;
      $items = $this->item->get_all()->result();

      $item_arr = array();
      foreach ($items as $item) {
        $item_arr[$item->name] = $this->touch->count_all_id($item->id);
      }
      
      arsort($item_arr);
      $graph_arr = array();
      foreach ($item_arr as $name=>$count) {
        $graph_arr[] = $name;
      }
      arsort($item_arr);
      $pie_arr = array();
      $i = 0;
      foreach ($item_arr as $name=>$count) {
        if(($i++) < 10){
          $pie_arr[] = $count;
          
        }
      }
      
      $count = count($items);
      $cat_name = $this->category->get_cat_name_by_id($cat_id);
      $sub_cat_name = $this->sub_category->get_sub_cat_name_by_id($sub_cat_id);

      $graph_items = json_encode($graph_arr);
      $pie_items= json_encode($pie_arr);
    ?>
<script type="text/javascript">
  Highcharts.chart('gambar', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0
        }
    },
    credits: {
    enabled: false
    },
    title: {
        text: 'User Activity Gambar'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 35,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Percentage',
        data: [
            {
                name: '1-3 Menit',
                y: 45,
                sliced: true,
                selected: true,
                color: "#FCDA31"
            },
            {
                name: '3-5 Menit',
                y: 25,
                sliced: true,
                selected: true,
                color: "#0B549F"
            },
            {
                name: '5 > Menit',
                y: 12,
                sliced: true,
                selected: true,
                color: "#04083D"
            }
            
        ]
    }]
});
</script>

<script type="text/javascript">
  Highcharts.chart('video', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0
        }
    },
    title: {
        text: 'User Activity Video'
    },
    credits: {
    enabled: false
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 35,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Percentage',
        data: [
            {
                name: '1-3 Menit',
                y: 25,
                sliced: true,
                selected: true,
                color: "#FCDA31"
            },
            {
                name: '3-5 Menit',
                y: 15,
                sliced: true,
                selected: true,
                color: "#0B549F"
            },
            {
                name: '5 > Menit',
                y: 55,
                sliced: true,
                selected: true,
                color: "#04083D"
            }
            
        ]
    }]
});
</script>

<script type="text/javascript">
  // Set up the chart
var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'container',
        type: 'column',
        options3d: {
            enabled: true,
            alpha: 15,
            beta: 15,
            depth: 50,
            viewDistance: 25
        }
    },
    credits: {
    enabled: false
    },
    title: {
        text: 'Top 10 Popular Content Object '
    },
    xAxis: {
        categories: <?php echo $graph_items; ?>,
        labels: {
            skew3d: true,
            style: {
                fontSize: '16px'
            }
        },
        title: {
                enabled: true,
                text: 'Object'
            }
    },
   yAxis: {
          title: {
              enabled: true,
              text: '<b>View</b>'
          }
      },
    subtitle: {
        text: 'BTN DIGIPRO'
    },
    plotOptions: {
        column: {
            depth: 25,
            colorByPoint: true
        },
    },
    // colors:colors,
    series: [{
        showInLegend: false,
        name: "View",
        data: <?php echo $pie_items;?>
    }]
});

function showValues() {
    $('#alpha-value').html(chart.options.chart.options3d.alpha);
    $('#beta-value').html(chart.options.chart.options3d.beta);
    $('#depth-value').html(chart.options.chart.options3d.depth);
}

// Activate the sliders
$('#sliders input').on('input change', function () {
    chart.options.chart.options3d[this.id] = parseFloat(this.value);
    showValues();
    chart.redraw(false);
});

showValues();
</script>