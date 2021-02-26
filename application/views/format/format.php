<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/format.js"></script>

<div ng-controller="formatCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h4>Informasi Format Surat KPPN Sijunjung <span class="table-project-n"></span></h4>
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
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEditFormat" ng-show="btnShow" ng-click="getInfoFormat(noid)"><i class="fa fa-lock" aria-hidden="true"></i> Edit</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDeleteFormat" ng-show="btnShow"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                                                <button type="button" class="btn btn-secondary" ng-click="getFormat(newPageNumber)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAddFormat"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select ng-model="itemsPerPage" ng-change="getFormat(newPageNumber)" class="form-control dt-tb">
                                                    <option value="10">10</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                    <option value="250">250</option>
                                                    <option value="500">500</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- <select ng-model="filter.sl_account" ng-change="getMemberData(1)" class="form-control dt-tb">
                                                    <option ng-repeat="opt in account" value="{{opt.id}}">{{opt.nama}}</option>
                                                </select> -->
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6" style=" text-align: right;">
                                        <div class="row">
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-3">
                                                <!-- <select ng-model="filter.sl_year" ng-change="loadMonth()" class="year form-control dt-tb">
                                                    <option ng-if="years.length > 0" ng-repeat="opt in years" value="{{opt.years}}">{{opt.years}}</option>
                                                </select> -->
                                            </div>
                                            <!-- <div class="col-md-2">
                                                <select ng-model="filter.sl_month" ng-change="getRekapData(1)" class="month form-control dt-tb">
                                                    <option ng-repeat="opt in month" value="{{opt.id}}">{{opt.month}}</option>
                                                </select>
                                            </div> -->
                                            <div class="col-md-7">
                                                <input ng-model="filter.txt_search" class="form-control" ng-enter="getFormat(1)" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

                            <ul ng-if="format.length > 0" class="basic-list" style="margin-top: 20px;">
                                <li dir-paginate="x in format  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" ng-click="enableButton(x.id, $index)">{{ x.judul }}  
                                    <a ng-if="x.extension == 'pdf' || x.extension == 'zip' || x.extension == 'rar'" target="_blank" href="https://docs.google.com/viewer?url=<?php echo base_url(); ?>uploads/document/surat/{{ x.file }}&embedded=true"><i ng-if="x.file != ''" class="pull-right fa fa-paperclip"></i></a>
                                    <a ng-if="x.extension == 'doc' || x.extension == 'docx' || x.extension == 'xlxs' || x.extension == 'xls'" target="_blank" href="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo base_url(); ?>uploads/document/surat/{{ x.file }}"><i ng-if="x.file != ''" class="pull-right fa fa-paperclip"></i></a>
                                </li>
                            </ul>
                            <div align="right">
                                <dir-pagination-controls
                                   max-size="8"
                                   direction-links="true"
                                   boundary-links="true"
                                   on-page-change="getFormat(newPageNumber)" >
                                </dir-pagination-controls>
                           </div>

                                <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ========================================================= MODAL SECTION =========================================================== -->



<!-- ########################################## MODAL DELETE FORMAT SURAT ################################################ -->


    <div id="modalDeleteFormat" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Data Format Surat</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            Apakah anda yakin ingin menghapus data Format Surat <b> {{ judul }} ?</b>
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="deleteFormat()">Process</a>
                </div>
            </div>
        </div>
    </div>



 <!-- ########################################## MODAL ADD FORMAT SURAT ################################################ -->

    <div id="modalAddFormat" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Format Surat</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="judul">Judul Format Surat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.judul" type="text" class="form-control" placeholder="Judul Format Surat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="nama">File Format Surat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.file" type="file" class="form-control" onchange="angular.element(this).scope().uploadedFile(this)"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="insertFormat()">Process</a>
                </div>
            </div>
        </div>
    </div>


    <!-- ########################################## MODAL EDIT FORMAT SURAT ################################################ -->

    <div id="modalEditFormat" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Format Surat</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="judul">Judul Format Surat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.format_judul" type="text" class="form-control" placeholder="Input Judul Format Surat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="waktu">File Format Surat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.format_file" type="file" class="form-control" onchange="angular.element(this).scope().uploadedFile(this)"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="updateFormat()">Process</a>
                </div>
            </div>
        </div>
    </div>


</div>
