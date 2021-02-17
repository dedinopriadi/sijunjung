<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jaringan.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/rekap.js"></script>

<div ng-controller="rekapCtrl">
    <div class="loading" ng-show="!dataLoadedSuccessfully">Loading&#8230;</div>
    <div class="data-table-area mg-b-15" ng-show="dataLoadedSuccessfully" ng-cloak>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Rekap Transaksi <span class="table-project-n"></span></h1>
                            </div>
                        </div>
                        <br>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3">

                                            </div>
                                            <div class="col-md-5">
                                            </div>
                                            <div class="col-md-3">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style=" text-align: right;">
                                        <div class="row">
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-2">
                                            </div>
                                            <div class="col-md-3">
                                                <select ng-model="filter.sl_year" ng-change="loadMonth()" class="year form-control dt-tb">
                                                    <option ng-if="years.length > 0" ng-repeat="opt in years" value="{{opt.years}}">{{opt.years}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <select ng-model="filter.sl_month" ng-change="getRekapData(1)" class="month form-control dt-tb">
                                                    <option ng-repeat="opt in month" value="{{opt.id}}">{{opt.month}}</option>
                                                </select>
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
                                            <th data-field="id">No</th>
                                            <th data-field="name">Tanggal</th>
                                            <th data-field="email">Total Transaksi</th>
                                            <th data-field="phone">Total Tagihan</th>
                                            <th data-field="complete">Tagihan</th>
                                            <th data-field="task">Total Admin</th>
                                            <!-- <th data-field="date">Fee M1</th>
                                            <th data-field="price">Fee M2</th>
                                            <th data-field="action">Fee M3</th> -->
                                        </tr>
                                    </thead>
                                    <tbody ng-if="rekap.length > 0">
                                        <!-- <tr dir-paginate="x in members|itemsPerPage:50"> -->
                                        <tr dir-paginate="x in rekap  | orderBy:sortField:reverse | itemsPerPage:itemsPerPage" total-items="total_count" >
                                            <td>{{ $index + 1 }}</td>
                                            <td><a href="<?php echo base_url()."rekapPrd/{{ x.tanggal }}"; ?>" style="color: blue;">{{ x.tanggal }}</a></td>
                                            <td>{{ x.lembar }}</td>
                                            <td>{{ x.total_tagihan }}</td>
                                            <td>{{ x.tagihan }}</td>
                                            <td>{{ x.admin }}</td>
                                            <!-- <td>{{ x.f1 }}</td>
                                            <td>{{ x.f2 }}</td>
                                            <td>{{ x.f3 }}</td> -->
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="text-align: right;"><b>Total </b></td>
                                            <td><b>{{ totalLembar }}</b></td>
                                            <td><b>{{ totalTagihan }}</b></td>
                                            <td><b>{{ tagihan }}</b></td>
                                            <td><b>{{ totalAdmin }}</b></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                <div align="right">
                                    <dir-pagination-controls
                                       max-size="8"
                                       direction-links="true"
                                       boundary-links="true"
                                       on-page-change="getRekapData(newPageNumber)" >
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
