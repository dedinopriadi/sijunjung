<?php
$id = $satkerInfo->satker_id;
$kode  = $satkerInfo->satker_kd;
$nama  = $satkerInfo->satker_nama;
$email = $satkerInfo->satker_email;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Satker Management
        <small>Edit Satker</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Edit Detail Satuan Kerja</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="editSatker" action="<?php echo base_url() ?>saveSatker" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="satker_kd">Kode Satker</label>
                                        <input type="text" class="form-control" id="satker_kd" value="<?php echo $kode; ?>" name="satker_kd" placeholder="Kode Satker"  maxlength="15" required >
                                        <input type="hidden" value="<?php echo $id; ?>" name="satker_id" id="satker_id" />    
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="satker_email">Email Satker</label>
                                        <input type="email" class="form-control" id="satker_email" value="<?php echo $email; ?>" name="satker_email" maxlength="100" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="satker_nama">Name Satker</label>
                                        <input type="text" class="form-control" placeholder="Nama Satuan Kerja" value="<?php echo $nama; ?>" id="satker_nama" name="satker_nama" maxlength="200" required>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/satker.js" type="text/javascript"></script>