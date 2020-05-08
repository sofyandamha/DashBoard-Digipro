<?php $this->load->view('templates/mokets/header');?>

<?php 
	if($edit_mode){
		$data['mode'] = $edit_mode;
		//echo " >>>here " . $edit_mode;
		$this->load->view('templates/mokets/nav',$data);
	} else {
		$this->load->view('templates/mokets/nav');
	}

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- <a href="#" class="brand-link">
      <img src="" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">BTN DIGIPRO</span>
    </a> -->
    		<?php
				if($sidebar){
					/*
					$shop_id = $this->session->userdata('shop_id');
					if (isset($shop_id) && trim($shop_id) != "") {
							$this->load->view('templates/mokets/sidebar');
					}
					*/
					$this->load->view('templates/mokets/sidebar');
				}
			?>

    </div>

    <!-- /.sidebar -->
</aside>
<!-- <section class="content">
      <div class="container-fluid">
		</div>
</section> -->
<?php print_r($content[content]);?>

<!-- <aside class="control-sidebar control-sidebar-dark"> -->
    <!-- Control sidebar content goes here -->
    
  <!-- </aside> -->

<?php $this->load->view('templates/mokets/footer');?>