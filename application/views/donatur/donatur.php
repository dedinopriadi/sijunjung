<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/donatur.js"></script>

<div ng-controller="donaturCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Data Donatur <span class="table-project-n"><?php echo $masjidName; ?></span></h1>
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
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEditDonatur" ng-show="btnShow" ng-click="getInfoDonatur(noid)"><i class="fa fa-lock" aria-hidden="true"></i> Edit</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDeleteDonatur" ng-show="btnShow"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                                                <button type="button" class="btn btn-secondary" ng-click="getDonatur(newPageNumber)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAddDonatur"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select ng-model="itemsPerPage" ng-change="getDonatur(newPageNumber)" class="form-control dt-tb">
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
                                                <input ng-model="filter.txt_search" class="form-control" ng-enter="getDonatur(1)" placeholder="Search">
                                                <input type="hidden" class="masjid" value="<?php echo $masjidId; ?>">
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
                                            <th data-field="name">NIK</th>
                                            <th data-field="email">Nama</th>
                                            <th data-field="action">Latitude</th>
                                            <th data-field="action">Longitude</th>
                                            <th data-field="price">Alamat</th>
                                        </tr>
                                    </thead>
                                    <tbody ng-if="donatur.length > 0">
                                        <!-- <tr dir-paginate="x in members|itemsPerPage:50"> -->
                                        <tr dir-paginate="x in donatur  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" ng-click="enableButton(x.id, $index)">
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ x.nik }}</td>
                                            <td>{{ x.nama }}</td>
                                            <td>{{ x.lat }}</td>
                                            <td>{{ x.longi }}</td>
                                            <td>{{ x.alamat }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div align="right">
                                    <dir-pagination-controls
                                       max-size="8"
                                       direction-links="true"
                                       boundary-links="true"
                                       on-page-change="getDonatur(newPageNumber)" >
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


 <!-- ########################################## MODAL DELETE DONATUR ################################################ -->


    <div id="modalDeleteDonatur" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Data Donatur</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            <label for="confirm">Apakah anda yakin ingin menghapus data donatur atas nama {{ nama }} ?</label>
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="deleteDonatur()">Process</a>
                </div>
            </div>
        </div>
    </div>



 <!-- ########################################## MODAL ADD DONATUR ################################################ -->

    <div id="modalAddDonatur" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Donatur</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="nik">NIK</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.nik" type="text" class="form-control" placeholder="Nomor Induk Kependudukan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="nama">Nama</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.nama" type="text" class="form-control" placeholder="Input Nama Lengkap"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="alamat">Alamat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.alamat" type="text" class="form-control" placeholder="Input Alamat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="latitude">Latitude</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.latitude" type="text" class="form-control" placeholder="Input Latitude"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="longitude">Longitude</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.longitude" type="text" class="form-control" placeholder="Input Longitude"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="insertDonatur()">Process</a>
                </div>
            </div>
        </div>
    </div>



 <!-- ########################################## MODAL EDIT DONATUR ################################################ -->

    <div id="modalEditDonatur" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Donatur</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="nik">NIK</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.donatur_nik" type="text" class="form-control" placeholder="Nomor Induk Kependudukan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="nama">Nama</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.donatur_nama" type="text" class="form-control" placeholder="Input Nama Lengkap"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="alamat">Alamat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.donatur_alamat" type="text" class="form-control" placeholder="Input Alamat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="latitude">Latitude</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.donatur_lat" type="text" class="form-control" placeholder="Input Latitude"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="longitude">Longitude</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.donatur_long" type="text" class="form-control" placeholder="Input Longitude"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="updateDonatur()">Process</a>
                </div>
            </div>
        </div>
    </div>




</div>
