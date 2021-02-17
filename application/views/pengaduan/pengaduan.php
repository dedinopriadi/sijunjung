<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pengaduan.js"></script>

<div ng-controller="pengaduanCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="mailbox-area mg-b-15">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="hpanel responsive-mg-b-30">
                                    <div class="panel-body">

                                        <ul class="mailbox-list">

                                            <li>
                                                <a href="javascript:void(0)" ng-click="getMessage(0)">
                                                        <span class="pull-right"></span>
                                                        <i class="fa fa-eye"></i> Belum Dibaca
                                                    </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" ng-click="getMessage(1)">
                                                        <span class="pull-right"></span>
                                                        <i class="fa fa-envelope-open-o"></i> Sudah Dibaca
                                                    </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" ng-click="getMessage('')">
                                                        <span class="pull-right"></span>
                                                        <i class="fa fa-envelope"></i> Semua Pengaduan
                                                    </a>
                                            </li>
                                            
                                        </ul>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 col-md-9 col-sm-9 col-xs-12" ng-show="dataListMessage" ng-cloak>
                                <div class="hpanel">
                                    <div class="panel-heading hbuilt mailbox-hd">
                                        <div class="text-center p-xs font-normal">
                                            <div class="input-group">
                                                <input ng-model="filter.txt_search" ng-enter="getPengaduan(1)" type="text" class="form-control input-sm" placeholder="Cari Pesan Pengaduan..."> <span class="input-group-btn active-hook"> 
                                                <button type="button" ng-click="getPengaduan(newPageNumber)" class="btn btn-sm btn-default">Search
                                                    </button> </span></div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6 col-md-6 col-sm-6 col-xs-8">
                                                <div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15">
                                                    <!-- <div class="col-md-4"> -->
                                                        <!-- <select ng-model="itemsPerPage" ng-change="getPengaduan(newPageNumber)" class="form-control dt-tb">
                                                            <option value="10">10</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                            <option value="250">250</option>
                                                            <option value="500">500</option>
                                                        </select> -->
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-md-6 col-sm-6 col-xs-4 mailbox-pagination">
                                                <div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15">
                                                    <button class="btn btn-default btn-sm" ng-click="getPengaduan(newPageNumber)"><i class="fa fa-refresh"></i> Refresh</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive ib-tb">
                                            <table class="table table-hover table-mailbox" data-pagination="true">
                                                <tbody ng-if="pengaduan.length > 0">
                                                    <tr dir-paginate="x in pengaduan  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" ng-click="viewMessage(x.id)">
                                                        <td ng-if="x.view == 0" class="active">
                                                            <div class="checkbox checkbox-single checkbox-success">
                                                                <input type="checkbox" checked>
                                                                <label></label>
                                                            </div>
                                                        </td>
                                                            <td ng-if="x.view != 0">
                                                                <div class="checkbox checkbox-single checkbox-success">
                                                                    <input type="checkbox" checked>
                                                                    <label></label>
                                                                </div>
                                                            </td>
                                                        <td ng-if="x.view == 0" class="active">{{ x.nama }}</td>
                                                            <td ng-if="x.view != 0">{{ x.nama }}</td>
                                                        <td ng-if="x.view == 0" class="active"><b>{{ x.judul }}</b></td>
                                                            <td ng-if="x.view != 0"><b>{{ x.judul }}</b></td>
                                                        <td ng-if="x.view == 0" class="active text-right mail-date">{{ x.waktu }}</td>
                                                            <td ng-if="x.view != 0" class="text-right mail-date">{{ x.waktu }}</td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                            <div align="right">
                                                <dir-pagination-controls
                                                   max-size="8"
                                                   direction-links="true"
                                                   boundary-links="true"
                                                   on-page-change="getPengaduan(newPageNumber)" >
                                                </dir-pagination-controls>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer ib-ml-ft">
                                        <i class="fa fa-eye"> </i> {{ getMyUnread() }} belum dibaca
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9 col-md-9 col-sm-9 col-xs-12" id="printableArea" ng-show="dataViewMessage" ng-cloak>
                                <div class="hpanel email-compose mailbox-view">
                                    <div class="panel-heading hbuilt">

                                        <div class="p-xs h4">
                                            <small class="pull-right view-hd-ml">
                                                    {{ edit.pengaduan_waktu }}
                                                </small> Detail Pengaduan

                                        </div>
                                    </div>
                                    <div class="border-top border-left border-right bg-light">
                                        <div class="p-m custom-address-mailbox">

                                            <div>
                                                <span class="font-extra-bold">Subjek : </span> {{ edit.pengaduan_judul}}
                                            </div>
                                            <div>
                                                <span class="font-extra-bold">Email : </span>
                                                {{ edit.pengaduan_email}}
                                            </div>
                                            <div>
                                                <span class="font-extra-bold">Nama : </span> {{ edit.pengaduan_nama }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body panel-csm">
                                        <div>

                                            <p>{{ edit.pengaduan_isi }}</p>

                                        </div>
                                    </div>

                                    

                                    <div class="panel-footer text-right ft-pn">
                                        <div class="btn-group active-hook">
                                            <button class="btn btn-default" onclick="printDiv('printableArea')"><i class="fa fa-print"></i> Print</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </div>

<!-- ========================================================= MODAL SECTION =========================================================== -->




</div>
