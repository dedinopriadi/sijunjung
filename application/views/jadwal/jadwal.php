<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jadwal.js"></script>

<div ng-controller="jadwalCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Jadwal Microlearning <span class="table-project-n">KPPN Sijunjung</span></h1>
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
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPeserta" ng-show="btnShow" ng-click="getInfoPeserta(noid)"><i class="fa fa-users" aria-hidden="true"></i> Peserta</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEditJadwal" ng-show="btnShow" ng-click="getInfoJadwal(noid)"><i class="fa fa-lock" aria-hidden="true"></i> Edit</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDeleteJadwal" ng-show="btnShow"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                                                <button type="button" class="btn btn-secondary" ng-click="getJadwal(newPageNumber)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAddJadwal"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select ng-model="itemsPerPage" ng-change="getJadwal(newPageNumber)" class="form-control dt-tb">
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
                                            <div class="col-md-2">
                                                <!-- <select ng-model="filter.sl_month" ng-change="getRekapData(1)" class="month form-control dt-tb">
                                                    <option ng-repeat="opt in month" value="{{opt.id}}">{{opt.month}}</option>
                                                </select> -->
                                            </div>
                                            <div class="col-md-5">
                                                <input ng-model="filter.txt_search" class="form-control" ng-enter="getJadwal(1)" placeholder="Search">
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
                                            <th data-field="nik">Materi Microlearning</th>
                                            <th data-field="nik">Tanggal Pelaksanaan</th>
                                            <th data-field="nama">Jam Pelaksanaan</th>
                                        </tr>
                                    </thead>
                                    <tbody ng-if="jadwal.length > 0">
                                        <!-- <tr dir-paginate="x in members|itemsPerPage:50"> -->
                                        <tr dir-paginate="x in jadwal  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" ng-click="enableButton(x.id, $index)">
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ x.materi }}</td>
                                            <td>{{ x.tanggal }}</td>
                                            <td>{{ x.waktu }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div align="right">
                                    <dir-pagination-controls
                                       max-size="8"
                                       direction-links="true"
                                       boundary-links="true"
                                       on-page-change="getJadwal(newPageNumber)" >
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


 <!-- ########################################## MODAL DELETE JADWAL ################################################ -->


    <div id="modalDeleteJadwal" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Jadwal Kegiatan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            Apakah anda yakin ingin menghapus data kegiatan <b>{{ materi }}</b> ?
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="deleteJadwal()">Process</a>
                </div>
            </div>
        </div>
    </div>



    <!-- ########################################## MODAL PESERTA PELATIHAN ################################################ -->

    <div id="modalPeserta" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Data Peserta Pelatihan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <table ng-show="!loadingModal" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Satker</th>
                                <th style="text-align: center;">Peserta</th>
                                <th style="text-align: right;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody ng-if="peserta.length > 0">
                            <tr ng-repeat="x in peserta">
                                <td style="text-align: left;">{{ x.satker }}</td>
                                <td style="text-align: center;">{{ x.jumlah }} Orang</td>
                                <td style="text-align: right;">{{ x.ket }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    
                </div>
            </div>
        </div>
    </div>




 <!-- ########################################## MODAL ADD JADWAL ################################################ -->

    <div id="modalAddJadwal" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Input Kegiatan Microlearning</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="materi">Materi Pelatihan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.materi" type="text" class="form-control" placeholder="Input Judul Materi"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="tanggal">Tanggal Pelatihan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.tanggal" type="text" class="pick form-control" data-date-format="yyyy-mm-dd" placeholder="Input Tanggal Pelaksanaan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="waktu">Waktu Pelatihan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.waktu" type="text" class="timepick form-control" placeholder="Input Waktu Pelaksanaan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.keterangan" type="text" class="form-control" placeholder="Input Keterangan (Opsional)"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="insertJadwal()">Process</a>
                </div>
            </div>
        </div>
    </div>



 <!-- ########################################## MODAL EDIT JADWAL ################################################ -->

    <div id="modalEditJadwal" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kegiatan Microlearning</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="materi">Materi Pelatihan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.jadwal_materi" type="text" class="form-control" placeholder="Input Materi Pelatihan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="tanggal">Tanggal Pelatihan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.jadwal_tgl" type="text" class="form-control" placeholder="Input Tanggal Pelaksanaan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="waktu">Waktu Pelaksanaan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.jadwal_waktu" type="text" class="form-control" placeholder="Input Waktu Pelaksanaan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="lokasi">Keterangan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.jadwal_keterangan" type="text" class="form-control" placeholder="Input Keterangan (Opsional)"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="updateJadwal()">Process</a>
                </div>
            </div>
        </div>
    </div>




</div>
