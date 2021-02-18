<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/konfirmasi.js"></script>

<div ng-controller="konfirmasiCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Data Konfirmasi Penerimaan KPPN Sijunjung</h1>
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
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalProsesKonfirmasi" ng-show="btnProses"><i class="fa fa-history" aria-hidden="true"></i> Proses</button>
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalSelesaiKonfirmasi" ng-show="btnSelesai"><i class="fa fa-check-square-o" aria-hidden="true"></i> Selesai</button>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalTolakKonfirmasi" ng-show="btnSelesai"><i class="fa fa-close" aria-hidden="true"></i> Tolak</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEditKonfirmasi" ng-show="btnShow" ng-click="getInfoKonfirmasi(noid)"><i class="fa fa-lock" aria-hidden="true"></i> Edit</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDeleteKonfirmasi" ng-show="btnShow"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                                                <button type="button" class="btn btn-secondary" ng-click="getKonfirmasi(newPageNumber)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
                                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAddKonfirmasi"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select ng-model="itemsPerPage" ng-change="getKonfirmasi(newPageNumber)" class="form-control dt-tb">
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
                                                <input ng-model="filter.txt_search" class="form-control" ng-enter="getKonfirmasi(1)" placeholder="Search">
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
                                            <th data-field="id">No.Surat</th>
                                            <th data-field="nik">Satuan Kerja</th>
                                            <th data-field="nama">Tanggal Terima</th>
                                            <th data-field="nama">Perihal</th>
                                            <th data-field="nama">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody ng-if="konfirmasi.length > 0">
                                        <!-- <tr dir-paginate="x in members|itemsPerPage:50"> -->
                                        <tr dir-paginate="x in konfirmasi  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" ng-click="enableButton(x.id, $index)">
                                            <td>{{ x.no_surat }}</td>
                                            <td>{{ x.satker }}</td>
                                            <td>{{ x.tgl }}</td>
                                            <td>{{ x.perihal }}</td>
                                                <td ng-if="x.status == 'Diterima'" style="color: #4d0478;"><b>{{ x.status }}</b></td>
                                                <td ng-if="x.status == 'Diproses'" style="color: #043c78;"><b>{{ x.status }}</b></td>
                                                <td ng-if="x.status == 'Selesai'" style="color: #014a0a;"><b>{{ x.status }}</b></td>
                                                <td ng-if="x.status == 'Ditolak'" style="color: #80220a;"><b>{{ x.status }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div align="right">
                                    <dir-pagination-controls
                                       max-size="8"
                                       direction-links="true"
                                       boundary-links="true"
                                       on-page-change="getKonfirmasi(newPageNumber)" >
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


 <!-- ########################################## MODAL DELETE KONFIRMASI PENERIMAAN ################################################ -->


    <div id="modalDeleteKonfirmasi" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Konfirmasi Penerimaan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            Apakah anda yakin ingin menghapus data Konfirmasi Penerimaan dengan No. Surat <b>{{ konfir_no_surat }}</b> dari Satker <b>{{ konfir_satker }}</b> ?
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="deleteKonfirmasi()">Process</a>
                </div>
            </div>
        </div>
    </div>




     <!-- ########################################## MODAL PROSES KONFIRMASI PENERIMAAN ################################################ -->


    <div id="modalProsesKonfirmasi" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Proses Konfirmasi Penerimaan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            Proses Konfirmasi Penerimaan dengan No. Surat <b> {{ konfir_no_surat }} </b> ?
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="statusKonfirmasi(noid, 2)">Process</a>
                </div>
            </div>
        </div>
    </div>


    <!-- ########################################## MODAL SELESAI KONFIRMASI PENERIMAAN ################################################ -->


    <div id="modalSelesaiKonfirmasi" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Selesai Konfirmasi Penerimaan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            Konfirmasi Penerimaan dengan No. Surat <b>{{ konfir_no_surat }}</b> telah selesai diproses ?
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a data-toggle="modal" href="#keteranganModal" data-dismiss="modal">Process</a>
                </div>
            </div>
        </div>
    </div>

    <div id="keteranganModal" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Keterangan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="nik">Keterangan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="selesai.no_persetujuan" type="text" class="form-control" placeholder="Input Nomor Surat Persetujuan"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="statusKonfirmasi(noid, 3)">Process</a>
                </div>
            </div>
        </div>
    </div>



<!-- ########################################## MODAL TOLAK KONFIRMASI PENERIMAAN ################################################ -->


    <div id="modalTolakKonfirmasi" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Konfirmasi Penerimaan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12" style="text-align: center;">
                            Tolak Konfirmasi Penerimaan dengan No. Surat <b>{{ konfir_no_surat }}</b> ?
                        </div>                      
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a data-toggle="modal" href="#tolakModal" data-dismiss="modal">Process</a>
                </div>
            </div>
        </div>
    </div>

    <div id="tolakModal" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Konfirmasi Penerimaan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px; margin-top: -25px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="nik">Alasan Penolakan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="selesai.alasan" type="text" class="form-control" placeholder="Input Alasan Penolakan"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -50px;">
                    <a href="javascript:void(0)" ng-click="statusKonfirmasi(noid, 4)">Process</a>
                </div>
            </div>
        </div>
    </div>




 <!-- ########################################## MODAL ADD KONFIRMASI PENERIMAAN ################################################ -->

    <div id="modalAddKonfirmasi" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Konfirmasi Penerimaan Baru</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="satker">Satuan Kerja</label>
                        </div>
                        <div class="col-md-9">
                            <select ng-model="detail.satker" class="form-control">
                                <option ng-if="ls_satker.length > 0" ng-repeat="opt in ls_satker" value="{{opt.satker_kd}}">{{opt.satker_nama}}</option>
                            </select>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="no_surat">No. Surat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.no_surat" type="text" class="form-control" placeholder="Input Nomor Surat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="perihal">Perihal</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.perihal" type="text" class="form-control" placeholder="Input Perihal Surat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="detail.keterangan" type="text" class="form-control" placeholder="Input Keterangan"/>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="insertKonfirmasi()">Process</a>
                </div>
            </div>
        </div>
    </div>



 <!-- ########################################## MODAL EDIT KONFIRMASI PENERIMAAN ################################################ -->

    <div id="modalEditKonfirmasi" class="modal modal-edu-general fullwidth-popup-InformationproModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Konfirmasi Penerimaan</h5>
                </div>
                <div class="modal-body" style="margin-right: -50px; margin-left: -65px;">
                    <div ng-show="loadingModal">Loading... Please Wait...</div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="satker">Satuan Kerja</label>
                        </div>
                        <div class="col-md-9">
                            <select ng-model="edit.satker_kd" class="form-control">
                                <option ng-if="ls_satker.length > 0" ng-repeat="opt in ls_satker" value="{{opt.satker_kd}}">{{opt.satker_nama}}</option>
                            </select>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="no_surat">No. Surat</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.konfir_no_surat" type="text" class="form-control" placeholder="Input Nomor Surat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="perihal">Perihal</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.konfir_perihal" type="text" class="form-control" placeholder="Input Perihal Surat"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-9">
                            <input ng-model="edit.konfir_keterangan" type="text" class="form-control" placeholder="Input Keterangan"/>
                        </div>                        
                    </div>
                    <div class="row" ng-show="!loadingModal" style="margin-bottom: 10px;">
                        <div class="col-md-3" style="text-align: right;">
                            <label for="status">Status SKPP</label>
                        </div>
                        <div class="col-md-9">
                            <select ng-model="edit.konfir_status" class="form-control">
                                <option value="Diterima">Diterima</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer info-md" style="margin-top: -50px;">
                    <!-- <a data-dismiss="modal" href="#">Cancel</a> -->
                    <a href="javascript:void(0)" ng-click="updateKonfirmasi()">Process</a>
                </div>
            </div>
        </div>
    </div>




</div>
