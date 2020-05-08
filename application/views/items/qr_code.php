<!-- Generic page styles -->
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="<?php echo base_url('assets/blueimp/css/blueimp-gallery.min.css'); ?>">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.fileupload.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.fileupload-ui.css'); ?>">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.fileupload-noscript.css'); ?>"></noscript>
<noscript>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.fileupload-ui-noscript.css'); ?>"></noscript>
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
                        <li class="breadcrumb-item "><a href="<?php echo site_url("items/edit/" . $id); ?>"><?php echo $this->lang->line('item_edit_label') ?></a> <span class="divider"></span></li>
                        <li class="breadcrumb-item active"><?php echo $this->lang->line('gallery_label') ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Gallery</h3>
                </div>
            <div class="card-body">
            <!-- The file upload form used as target for the file upload widget -->
            <form id="fileupload" method="POST" enctype="multipart/form-data">
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="col-lg-7">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span><?php echo $this->lang->line('add_file_button') ?></span>
                            <input type="file" name="files[]" multiple>
                        </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span><?php echo $this->lang->line('start_upload_button') ?></span>
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span><?php echo $this->lang->line('cancel_upload_button') ?></span>
                        </button>
                        <button type="button" class="btn btn-danger delete">
                            <i class="glyphicon glyphicon-trash"></i>
                            <span><?php echo $this->lang->line('delete_label') ?></span>
                        </button>
                        <input type="checkbox" class="toggle">
                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                    </div>
                    <!-- The global progress state -->
                    <div class="col-lg-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>

                <div class="alert upload-success-message hide">
                </div>

                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped">
                    <tbody class="files"></tbody>
                </table>
            </form>

            <div clas="row">
                <div class="col-sm-12">
                    <!-- The blueimp Gallery widget -->
                    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                        <div class="slides"></div>
                        <h3 class="title"></h3>
                        <a class="prev">‹</a>
                        <a class="next">›</a>
                        <a class="close">×</a>
                        <a class="play-pause"></a>
                        <ol class="indicator"></ol>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="jumbotron">
                    <h1>Qr Code Generator</h1>
                    <p> Generate Qr code with Codeigniter.</p>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Object Id</th>
                            <th>Object Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Image url</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo $item->name; ?></td>
                            <?php
                            foreach ($this->category->get_all($this->shop->get_current_shop()->id)->result() as $cat) {

                                if ($item->cat_id == $cat->id) {
                                    echo "<td>" . $cat->name . "</td>";
                                }
                            }

                            foreach ($this->sub_category->get_all_by_cat_id($item->cat_id)->result() as $sub_cat) {

                                if ($item->sub_cat_id == $sub_cat->id) {
                                    echo "<td>" . $sub_cat->name . "</td>";
                                }
                            }
                            ?>
                            <td><?php echo $item->description ?></td>
                            <td><?php echo $img->path ?></td>

                        </tr>
                    </tbody>
                </table>


                <a href="<?= site_url() ?>/items/print_qr/<?= $item->id ?>" class="btn btn-success">Print Qr code</a>
            </div>
            </div>
        </div>
    </section>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload">
        <td>
            <span class="preview"></span>
        </td>
        <td class="title">
        		<input type="hidden" name="id[]" value="0"/>
        		<input type="hidden" name="parent_id" value="<?php echo $_SESSION['parent_id']; ?>"/>
        		<input type="hidden" name="type" value="<?php echo $_SESSION['type']; ?>"/>
        		<label>Desc:</label>
        		<textarea class="form-control" name="description[]"></textarea>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td class="title">
        		<input class="id" type="hidden" name="id[]" value="{%=file.id%}"/>
        		<input type="hidden" name="parent_id" value="<?php echo $_SESSION['parent_id']; ?>"/>
        		<label>Desc:</label>
        		<textarea class="form-control desc" name="description[]">{%=file.description%}</textarea>
        		{% if (file.error) { %}
        		    <div><span class="label label-danger">Error</span> {%=file.error%}</div>
        		{% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
        		<button class="btn btn-primary edit">
        		    <i class="glyphicon glyphicon-upload"></i>
        		    <span>Update</span>
        		</button>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script>
    var baseUrl = "<?php echo base_url(); ?>";
    var siteUrl = "<?php echo site_url() ?>";
</script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo base_url('assets/js/vendor/jquery.ui.widget.js'); ?>"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo base_url('assets/blueimp/js/tmpl.min.js'); ?>"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo base_url('assets/blueimp/js/load-image.min.js'); ?>"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php echo base_url('assets/blueimp/js/canvas-to-blob.min.js'); ?>"></script>
<!-- blueimp Gallery script -->
<script src="<?php echo base_url('assets/blueimp/js/jquery.blueimp-gallery.min.js'); ?>"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo base_url('assets/js/jquery.iframe-transport.js'); ?>"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo base_url('assets/js/jquery.fileupload.js'); ?>"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo base_url('assets/js/jquery.fileupload-process.js'); ?>"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo base_url('assets/js/jquery.fileupload-image.js'); ?>"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo base_url('assets/js/jquery.fileupload-audio.js'); ?>"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo base_url('assets/js/jquery.fileupload-video.js'); ?>"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo base_url('assets/js/jquery.fileupload-validate.js'); ?>"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo base_url('assets/js/jquery.fileupload-ui.js'); ?>"></script>
<!-- The main application script -->
<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="<?php echo base_url('assets/js/cors/jquery.xdr-transport.js'); ?>"></script>
<![endif]-->