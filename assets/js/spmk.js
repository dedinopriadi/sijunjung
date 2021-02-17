/**
 * @author Dedi Nopriadi
 */


var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('spmkCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.btnProses = false;
  $scope.btnSelesai = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.spmk = [];
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

    $scope.getSpmk = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "spmkListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.spmk = e.data.spmks;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.spmk = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getSpmk();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.spmk_satker = $scope.spmk[indexs].satker;
      $scope.spmk_status = $scope.spmk[indexs].status;
      $scope.spmk_no_surat = $scope.spmk[indexs].no_surat;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        $scope.btnProses = false;
        $scope.btnSelesai = false;
        break;
      case false:
        $scope.btnShow = true;
        if($scope.spmk_status == 'Diterima') {
          $scope.btnProses = true;
        } else if($scope.spmk_status == 'Diproses') {
          $scope.btnSelesai = true;
        }
        break;
      }
    };


    $scope.getInfoSpmk = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoSpmk",
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


    $scope.statusSpmk = function(idx, statusx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "statusSpmk",
          data :{id: idx, status: statusx, datax: $scope.selesai}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalProsesSpmk').modal('hide');
          $('#keteranganModal').modal('hide');
          $('#tolakModal').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
          $scope.getSpmk();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          $('#modalProsesSpmk').modal('hide');
          $('#keteranganModal').modal('hide');
          $('#tolakModal').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.deleteSpmk = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteSpmk",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteSpmk').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getSpmk();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteSpmk').modal('hide');
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

    $scope.insertSpmk = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addSpmk",
          data :{datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddSpmk').modal('hide');
          toastr.success(e.data.success);
          $scope.getSpmk();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateSpmk = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateSpmk",
          data :{id: $scope.noid,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditSpmk').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
          $scope.getSpmk();
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