<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/link.js"></script>

<div ng-controller="linkCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <!-- <h1>Data Donatur <span class="table-project-n"><?php echo $masjidName; ?></span></h1> -->
                            </div>
                        </div>
                        <br>
                        <div class="sparkline13-graph" style="margin-top: -57px;">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="row">
                                    <!-- /////////////////////////// -->
                                    <div class="col-md-12" style=" text-align: right;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- /////////////////////////// -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <!-- /////////////////////////// -->
                                            </div>
                                            <div class="col-md-6">
                                                <!-- /////////////////////////// -->
                                        </div>
                                        <div class="col-md-3">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6" style=" text-align: right;">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <!-- /////////////////////////// -->
                                            </div>
                                            <div class="col-md-3">
                                                <!-- /////////////////////////// -->
                                            </div>
                                            <div class="col-md-2">
                                                <!-- /////////////////////////// -->
                                            </div>
                                            <div class="col-md-5">
                                                <input type="hidden" class="masjid" value="<?php echo $masjidId; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ========================================================= MODAL SECTION =========================================================== -->



 <!-- ########################################## MODAL EDIT LINK ################################################ -->

    <div id="modalEditLink" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Link Kajian <?php echo $masjidName; ?></h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="link">Link Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.link_alamat" type="text" class="form-control" placeholder="Link Kajian"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="updateLink()">Process</a>
                </div>
            </div>
        </div>
    </div>


</div>
