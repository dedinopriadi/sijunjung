<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/kajian.js"></script>

<div ng-controller="kajianCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Informasi Kajian <span class="table-project-n"><?php echo $masjidName; ?></span></h1>
                            </div>
                        </div>
                        <br>
                        <div class="sparkline13-graph" style="margin-top: -57px;">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="row">
                                    <!-- <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3">

                                            </div>
                                            <div class="col-md-5">

                                            </div>
                                            <div class="col-md-3">
                                                
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-12" style=" text-align: right;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEditKajian" ng-show="btnShow" ng-click="getInfoKajian(noid)"><i class="fa fa-lock" aria-hidden="true"></i> Edit</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDeleteKajian" ng-show="btnShow"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                                                <button type="button" class="btn btn-secondary" ng-click="getKajian(newPageNumber)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAddKajian"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select ng-model="itemsPerPage" ng-change="getKajian(newPageNumber)" class="form-control dt-tb">
                                                    <option value="">Default</option>
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
                                            <div class="col-md-2">
                                                <!-- <select ng-model="filter.sl_month" ng-change="getRekapData(1)" class="month form-control dt-tb">
                                                    <option ng-repeat="opt in month" value="{{opt.id}}">{{opt.month}}</option>
                                                </select> -->
                                            </div>
                                            <div class="col-md-5">
                                                <input ng-model="filter.txt_search" class="form-control" ng-enter="getKajian(1)" placeholder="Search">
                                                <input type="hidden" class="masjid" value="<?php echo $masjidId; ?>">
                                                <input type="hidden" class="admin" value="<?php echo $vendorId; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="toolbar">
                                    
                                    
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar" class="my-special-table">
                                    <thead>
                                        <tr>
                                            <th data-field="id">No.</th>
                                            <th data-field="nik">Judul Kajian</th>
                                            <th data-field="nik">Lokasi Kajian</th>
                                            <th data-field="nama">Tanggal</th>
                                            <th data-field="usia">Waktu</th>
                                            <th data-field="usia">Admin Input</th>
                                        </tr>
                                    </thead>
                                    <tbody ng-if="kajian.length > 0">
                                        <!-- <tr dir-paginate="x in members|itemsPerPage:50"> -->
                                        <tr dir-paginate="x in kajian  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" ng-click="enableButton(x.id, $index)">
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ x.judul }}</td>
                                            <td>{{ x.lokasi }}</td>
                                            <td>{{ x.tanggal }}</td>
                                            <td>{{ x.waktu }}</td>
                                            <td>{{ x.admin }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div align="right">
                                    <dir-pagination-controls
                                       max-size="8"
                                       direction-links="true"
                                       boundary-links="true"
                                       on-page-change="getKajian(newPageNumber)" >
                                    </dir-pagination-controls>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ========================================================= MODAL SECTION =========================================================== -->


 <!-- ########################################## MODAL DELETE KAJIAN ################################################ -->


    <div id="modalDeleteKajian" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Data Kajian</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            <label for="confirm">Apakah anda yakin ingin menghapus data {{ judul }} yang akan dilaksanakan pada {{ tgl }} {{ jam }} ?</label>
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="deleteKajian()">Process</a>
                </div>
            </div>
        </div>
    </div>



 <!-- ########################################## MODAL ADD KAJIAN ################################################ -->

    <div id="modalAddKajian" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Kajian</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="judul">Judul Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.judul" type="text" class="form-control" placeholder="Input Judul Kajian"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="lokasi">Lokasi Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.lokasi" type="text" class="form-control" placeholder="Input Lokasi Kajian"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="tanggal">Tanggal Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.tanggal" type="text" class="pick form-control" data-date-format="yyyy-mm-dd" placeholder="Input Tanggal Kajian"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="waktu">Waktu Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.waktu" type="text" class="timepick form-control" placeholder="Input Waktu Kajian"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="insertKajian()">Process</a>
                </div>
            </div>
        </div>
    </div>



 <!-- ########################################## MODAL EDIT KAJIAN ################################################ -->

    <div id="modalEditKajian" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Kajian</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="judul">Judul Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.kajian_judul" type="text" class="form-control" placeholder="Input Judul Kajian"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="lokasi">Lokasi Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.kajian_lokasi" type="text" class="form-control" placeholder="Input Lokasi Kajian"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="tanggal">Tanggal Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.kajian_tgl" type="text" class="form-control" placeholder="Input Tanggal Kajian"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="waktu">Waktu Kajian</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.kajian_jam" type="text" class="form-control" placeholder="Input Waktu Kajian"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="updateKajian()">Process</a>
                </div>
            </div>
        </div>
    </div>




</div>