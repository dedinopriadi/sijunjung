var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('pengaduanCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.dataViewMessage = false;
  $scope.dataListMessage = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.pengaduan = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.unread;
  $scope.condition = 0;
  $scope.admin = $(".admin").val();

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 10; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getPengaduan = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "pengaduanListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          conditi: $scope.condition,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.pengaduan = e.data.pengadu;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        $scope.dataListMessage = true;
        $scope.dataViewMessage = false;
        if($scope.condition != 1){
          $scope.getUnread();
        }
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.pengaduan = [];
        $scope.loaded = true;
        $scope.dataListMessage = true;
        $scope.dataViewMessage = false;
        toastr.warning("Referensi Kosong");
        if($scope.condition != 1){
          $scope.getUnread();
        }
      });
    };

    $scope.getPengaduan();


    $scope.getMessage = function(conditionx) {
      $scope.condition = conditionx;
      $scope.getPengaduan();
    }


    $scope.getUnread = function(){
      $scope.unread = 0;
        for(var i = 0; i < $scope.pengaduan.length; i++){
            $scope.tpengaduan = $scope.pengaduan[i];
            if(parseInt($scope.tpengaduan.view) == 0){
              $scope.unread = parseInt($scope.unread) + 1;
            } 
        }
        // return $scope.total;
    }

    $scope.getMyUnread = function(){
      $scope.total = 0;
        for(var i = 0; i < $scope.pengaduan.length; i++){
            $scope.tpengaduan = $scope.pengaduan[i];
            if(parseInt($scope.tpengaduan.view) == 0){
              $scope.total = parseInt($scope.total) + 1;
            } 
        }
        return $scope.total;
    }

    $scope.viewMessage = function(idx) {
      $scope.noid   = idx;
      $scope.loaded = false;
      $scope.dataListMessage = false;
      $http({
          method: 'POST',
          url: baseURL + "getInfoPengaduan",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.loaded = true;
          $scope.dataViewMessage = true;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.error);
          $scope.loaded = true;
          $scope.dataViewMessage = false;
          $scope.dataListMessage = true;
          toastr.error(e.data.errors);
        });
    };


    // $scope.getInfoJadwal = function(idx) {
    //   $scope.loadingModal = true;
    //   $http({
    //       method: 'POST',
    //       url: baseURL + "getInfoJadwal",
    //       data :{id: idx}
    //     }).then(function success(e) {
    //       $scope.edit = e.data.info;
    //       $scope.loadingModal = false;
    //       $scope.btnShow = false;
    //       toastr.success(e.data.success);
    //     }, function error(e) {
    //       console.log(e.data.error);
    //       $scope.btnShow = false;
    //       toastr.error(e.data.errors);
    //     });
    // };


    // $scope.deleteJadwal = function() {
    //   $scope.loadingModal = true;
    //   $http({
    //       method: 'POST',
    //       url: baseURL + "deleteJadwal",
    //       data :{id: $scope.noid}
    //     }).then(function success(e) {
    //       $scope.loadingModal = false;
    //       $('#modalDeleteJadwal').modal('hide');
    //       $scope.btnShow = false;
    //       toastr.success(e.data.success);
    //       $scope.getJadwal();
    //     }, function error(e) {
    //       console.log(e.data.error);
    //       $('#modalDeleteJadwal').modal('hide');
    //       $scope.btnShow = false;
    //       toastr.error(e.data.errors);
    //     });
    // };

    // $scope.insertJadwal = function() {
    //   $scope.loadingModal = true;
    //   $http({
    //       method: 'POST',
    //       url: baseURL + "addJadwal",
    //       data :{admin: $scope.admin,
    //              tgl: $(".pick").val(),
    //              waktu: $(".timepick").val(),
    //              datax: $scope.detail}
    //     }).then(function success(e) {
    //       $scope.loadingModal = false;
    //       $('#modalAddJadwal').modal('hide');
    //       toastr.success(e.data.success);
    //       $scope.getJadwal();
    //     }, function error(e) {
    //       $scope.loadingModal = false;
    //       console.log(e.data.error);
    //       toastr.error(e.data.errors);
    //     });
    // };


    // $scope.updateJadwal = function() {
    //   $scope.loadingModal = true;
    //   $http({
    //       method: 'POST',
    //       url: baseURL + "updateJadwal",
    //       data :{id: $scope.noid,
    //               datax: $scope.edit}
    //     }).then(function success(e) {
    //       $scope.loadingModal = false;
    //       $('#modalEditJadwal').modal('hide');
    //       $scope.btnShow = false;
    //       toastr.success(e.data.success);
    //       $scope.getJadwal();
    //     }, function error(e) {
    //       console.log(e.data.error);
    //       //$('#modalEditDonatur').modal('hide');
    //       $scope.btnShow = false;
    //       toastr.error(e.data.errors);
    //     });
    // };

    // $scope.testPicker = function() {
    //   toastr.error($(".pick").val());
    //   toastr.warning($(".timepick").val());
    // };

///////////////////////////////////////////////////////////////////////////////////////////////////
});

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}


angular.module('App').directive('ngEnter', function() {
  return function(scope, element, attrs) {
      element.bind("keydown keypress", function(event) {
          if(event.which === 13) {
              scope.$apply(function(){
                  scope.$eval(attrs.ngEnter, {'event': event});
              });

              event.preventDefault();
          }
      });
  };
});