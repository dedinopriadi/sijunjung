<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dashboard.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/modals.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dirPaginate.js"></script>

 <div ng-controller="dashboardCtrl"> 
<input type="hidden" class="role" value="<?php echo $role; ?>">
 <div class="loading" ng-show="!loaded">Loading&#8230;</div>
  <div class="breadcome-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- ////////////////////////////////////////////////////// -->

                <?php
                if($role == ROLE_COMPLAINT)
                {
                ?>
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12" ng-show="viewDataPengaduan" ng-cloak>
                        <div class="single-review-st-item res-mg-t-30 table-mg-t-pro-n">
                            <div class="single-review-st-hd">
                                <h2>List 5 Pengaduan Terbaru</h2>
                            </div>
                            <div ng-if="pengaduan.length > 0">
                                <div ng-repeat="x in pengaduan" class="single-review-st-text">
                                    <div class="notification-icon">
                                        <i class="educate-icon educate-checked edu-checked-pro admin-check-pro" aria-hidden="true"></i>
                                    </div>
                                    <div class="review-ctn-hf">
                                        <h3>{{ x.nama }}</h3>
                                        <p>{{ x.judul }}</p>
                                    </div>
                                    <div class="review-item-rating">
                                        {{ x.waktu }}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="message-view">
                                <a href="<?php echo base_url(); ?>Pengaduan">Lihat Semua Pengaduan</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

                <?php
                if($role == ROLE_MANAGER || $role == ROLE_EMPLOYEE || $role == ROLE_ADMIN)
                {
                ?>
                <div class="row" ng-show="viewDataAdmin" ng-cloak>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"  >
                        <div class="hpanel shadow-inner responsive-mg-b-30 res-tablet-mg-t-30 dk-res-t-pro-30">
                            <div class="panel-body">
                                <div class="table-responsive wd-tb-cr">
                                    <h1>Feedback</h1>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Respon</th>
                                                <th style="text-align: right;">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody ng-if="feedback.length > 0">
                                            <tr ng-repeat="x in feedback">
                                                <td ng-if="x.feedback == 0">
                                                    <span class="text-warning font-bold">Senang</span>
                                                </td>
                                                <td ng-if="x.feedback == 1">
                                                    <span class="text-warning font-bold">Biasa Saja</span>
                                                </td>
                                                <td ng-if="x.feedback == 2">
                                                    <span class="text-warning font-bold">Sedih</span>
                                                </td>
                                                <td style="text-align: right;">{{ x.jumlah }}</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: right;">
                                                    <span><b>Total</b></span>
                                                </td>
                                                <td style="text-align: right;"><b>{{ getJumlahFeedback() }}</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1 style="margin-left: 15px;">3 Feedback Terakhir</h1>
                    <div ng-if="lastFeedback.length > 0">
                        <div ng-repeat="x in lastFeedback" class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div ng-if="x.feedback == 0" class="hpanel shadow-inner hbgyellow bg-3 responsive-mg-b-30 res-tablet-mg-t-30 dk-res-t-pro-30">
                                <div class="panel-body">
                                    <div class="text-center content-bg-pro">
                                        <h3>{{ x.nama }}</h3>
                                        <p class="text-big font-light">
                                            {{ x.satker }}
                                        </p>
                                        <small  ng-if="x.feedback == 0">
                                            Memberikan Feedback Senang terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                        <small  ng-if="x.feedback == 1">
                                            Memberikan Feedback Biasa Saja terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                        <small  ng-if="x.feedback == 2">
                                            Memberikan Feedback Sedih terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="x.feedback == 1" class="hpanel shadow-inner hbgblue bg-2 responsive-mg-b-30">
                                <div class="panel-body">
                                    <div class="text-center content-bg-pro">
                                        <h3>{{ x.nama }}</h3>
                                        <p class="text-big font-light">
                                            {{ x.satker }}
                                        </p>
                                        <small  ng-if="x.feedback == 0">
                                            Memberikan Feedback Senang terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                        <small  ng-if="x.feedback == 1">
                                            Memberikan Feedback Biasa Saja terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                        <small  ng-if="x.feedback == 2">
                                            Memberikan Feedback Sedih terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="x.feedback == 2" class="hpanel shadow-inner hbgred bg-4 res-tablet-mg-t-30 dk-res-t-pro-30">
                                <div class="panel-body">
                                    <div class="text-center content-bg-pro">
                                        <h3>{{ x.nama }}</h3>
                                        <p class="text-big font-light">
                                            {{ x.satker }}
                                        </p>
                                        <small  ng-if="x.feedback == 0">
                                            Memberikan Feedback Senang terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                        <small  ng-if="x.feedback == 1">
                                            Memberikan Feedback Biasa Saja terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                        <small  ng-if="x.feedback == 2">
                                            Memberikan Feedback Sedih terhadap aplikasi KPPN Sijunjung Mobile
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>

                <!-- /////////////////////////////////////////////////////// -->
            </div>
        </div>
    </div>
  </div>

  <div class="analytics-sparkle-area">
    <div class="container-fluid">
        <!--  -->
    </div>
  </div>

    


</div>