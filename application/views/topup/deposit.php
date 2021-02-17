<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/topupDepo.js"></script>

<div ng-controller="depositCtrl">
    <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Mutasi Dana <span class="table-project-n">Investasi</span></h1>
                            </div>
                        </div>
                        <br>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <!-- <div class="row">
                                    <div class="col-md-12" style=" text-align: right;">
                                        <div class="row">
                                            <div class="col-md-12">

                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input ng-model="filter.txt_search" class="form-control" ng-enter="getTopupDepo(1)" placeholder="Search">
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-3">
                                                
                                            </div>
                                            <div class="col-md-2">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style=" text-align: right;">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select ng-model="filter.sl_day" class="day form-control dt-tb">
                                                    <option value="">Semua</option>
                                                    <option ng-repeat="opt in day" value="{{opt}}">{{opt}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <select ng-model="filter.sl_month" class="month form-control dt-tb">
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select ng-model="filter.sl_year" class="year form-control dt-tb">
                                                    <option ng-if="years.length > 0" ng-repeat="opt in years" value="{{opt.years}}">{{opt.years}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-default" ng-click="getTopupDepo(1)"></i> View</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px; margin-bottom: -50px;">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <select ng-model="itemsPerPage" ng-change="getTopupDepo(newPageNumber)" class="form-control dt-tb">
                                                    <option value="">Default</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                    <option value="250">250</option>
                                                    <option value="500">500</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">

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

                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-5">

                                            </div>
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
                                            <th data-field="id">ID</th>
                                            <th data-field="name">No.ID</th>
                                            <th data-field="date">Waktu</th>
                                            <th data-field="email">Loket</th>
                                            <th data-field="email">Admin</th>
                                            <th data-field="email">Tipe</th>
                                            <th data-field="price">Amount</th>
                                            <th data-field="action">Saldo</th>
                                            <th data-field="action">Bank</th>
                                            <th data-field="action">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody ng-if="topup.length > 0">
                                        <!-- <tr dir-paginate="x in members|itemsPerPage:50"> -->
                                        <tr dir-paginate="x in topup  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" ng-click="enableButton(x.id)">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ x.noid }}</td>
                                            <td>{{ x.waktu }}</td>
                                            <td>{{ x.nama }}</td>
                                            <td>{{ x.admin }}</td>
                                            <td>{{ x.product }}</td>
                                            <td>{{ x.amount }}</td>
                                            <td>{{ x.saldo }}</td>
                                            <td>{{ x.bank }}</td>
                                            <td>{{ x.keterangan }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="text-align: right;"><b>Total </b></td>
                                            <td><b>{{ totalAmount }}</b></td>
                                            <td><b>{{ totalSaldo }}</b></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div align="right">
                                    <dir-pagination-controls
                                       max-size="8"
                                       direction-links="true"
                                       boundary-links="true"
                                       on-page-change="getTopupDepo(newPageNumber)" >
                                    </dir-pagination-controls>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
