<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/profil.js"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/profile.js"></script>

<div ng-controller="profilCtrl">
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

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->


                                <div class="single-pro-review-area mt-t-30 mg-b-15">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="product-payment-inner-st">
                                                    <ul id="myTabedu1" class="tab-review-design">
                                                        <li class="active"><a href="#description">Tentang Kami</a></li>
                                                        <li><a href="#INFORMATION" ng-click="getInfoIntegritas()">Informasi Zona Integritas</a></li>
                                                        <li><a href="#KONTAK" ng-click="getInfoKontak()">Hubungi Kami</a></li>
                                                    </ul>
                                                    <div id="myTabContent" class="tab-content custom-product-edit">
                                                        <div class="product-tab-list tab-pane fade active in" id="description">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="review-content-section">
                                                                        <div id="dropzone1" class="pro-ad">
                                                                            <form class="dropzone dropzone-custom needsclick add-professors" role="form" enctype="multipart/form-data">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <input ng-model="profil.alamat" type="text" class="form-control" placeholder="Alamat KPPN Sijunjung">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <input ng-model="profil.cso" type="text" class="form-control" placeholder="Nomor CSO">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-lg-12">
                                                                                        <div id="txtProfil">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <!-- ///////////////////////////////////// -->
                                                                                    </div>
                                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <input ng-model="profil.gambar" type="file" class="form-control" accept="image/*" onchange="angular.element(this).scope().uploadedFile(this)"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-lg-12" align="right">
                                                                                        <button type="button" class="btn btn-primary waves-effect waves-light" ng-click="uploadImage()">Simpan</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="product-tab-list tab-pane fade" id="INFORMATION">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="review-content-section">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="devit-card-custom">
                                                                                    <div class="form-group">
                                                                                        <div id="txtIntegritas">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <!-- ///////////////////////////////////// -->
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <input ng-model="integritas.gambar" type="file" class="form-control" accept="image/*" onchange="angular.element(this).scope().uploadedFile(this)"/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-12" align="right">
                                                                                <button type="button" class="btn btn-primary waves-effect waves-light" ng-click="updateIntegritas()">Simpan</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="product-tab-list tab-pane fade" id="KONTAK">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="review-content-section">
                                                                        <div class="row">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <input ng-model="kontak.email" type="text" class="form-control" placeholder="Input Email KPPN Sijunjung">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input ng-model="kontak.telp" type="text" class="form-control" placeholder="Input No. Telepon KPPN Sijunjung">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <input ng-model="kontak.website" type="text" class="form-control" placeholder="Input Alamat Website KPPN Sijunjung">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-12" align="left">
                                                                                <button type="button" class="btn btn-primary waves-effect waves-light" ng-click="updateKontak()">Simpan</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
