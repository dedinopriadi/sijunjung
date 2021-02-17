/**
 * @author Dedi Nopriadi
 */


var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('konfirmasiCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.btnProses = false;
  $scope.btnSelesai = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.konfirmasi = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.selesai = {};
  $scope.filter = {};
  $scope.ls_satker = [];
  $scope.noid;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getKonfirmasi = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "konfirmasiListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.konfirmasi = e.data.konfir;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.konfirmasi = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getKonfirmasi();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.konfir_satker = $scope.konfirmasi[indexs].satker;
      $scope.konfir_status = $scope.konfirmasi[indexs].status;
      $scope.konfir_no_surat = $scope.konfirmasi[indexs].no_surat;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        $scope.btnProses = false;
        $scope.btnSelesai = false;
        break;
      case false:
        $scope.btnShow = true;
        if($scope.konfir_status == 'Diterima') {
          $scope.btnProses = true;
        } else if($scope.konfir_status == 'Diproses') {
          $scope.btnSelesai = true;
        }
        break;
      }
    };


    $scope.getInfoKonfirmasi = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoKonfirmasi",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.loadingModal = false;
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.error);
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.statusKonfirmasi = function(idx, statusx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "statusKonfirmasi",
          data :{id: idx, status: statusx, datax: $scope.selesai}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalProsesKonfirmasi').modal('hide');
          $('#keteranganModal').modal('hide');
          $('#tolakModal').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
          $scope.getKonfirmasi();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          $('#modalProsesKonfirmasi').modal('hide');
          $('#keteranganModal').modal('hide');
          $('#tolakModal').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.deleteKonfirmasi = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteKonfirmasi",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteKonfirmasi').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getKonfirmasi();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteKonfirmasi').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.getSatker = function() {
      $http({
        method: 'GET',
        url: baseURL + "getSatker"
      }).then(function success(e) {
        $scope.ls_satker = e.data;
      }, function error(e) {
        console.log(e.data.error);
      });
   };

    $scope.insertKonfirmasi = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addKonfirmasi",
          data :{datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddKonfirmasi').modal('hide');
          toastr.success(e.data.success);
          $scope.getKonfirmasi();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateKonfirmasi = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateKonfirmasi",
          data :{id: $scope.noid,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditKonfirmasi').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
          $scope.getKonfirmasi();
        }, function error(e) {
          console.log(e.data.error);
          //$('#modalEditDonatur').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.getSatker();

///////////////////////////////////////////////////////////////////////////////////////////////////
});


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