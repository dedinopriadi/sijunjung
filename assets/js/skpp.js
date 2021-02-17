/**
 * @author Dedi Nopriadi
 */


var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('skppCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.btnProses = false;
  $scope.btnSelesai = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.skpp = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.selesai = {};
  $scope.supplier = {};
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

    $scope.getSkpp = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "skppListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.skpp = e.data.skpps;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.skpp = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getSkpp();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.skpp_satker = $scope.skpp[indexs].satker;
      $scope.skpp_status = $scope.skpp[indexs].status;
      $scope.skpp_no_surat = $scope.skpp[indexs].no_surat;
      $scope.supplier.status = $scope.skpp[indexs].supplier;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        $scope.btnProses = false;
        $scope.btnSelesai = false;
        break;
      case false:
        $scope.btnShow = true;
        if($scope.skpp_status == 'Diterima') {
          $scope.btnProses = true;
        } else if($scope.skpp_status == 'Diproses') {
          $scope.btnSelesai = true;
        }
        break;
      }
    };


    $scope.getInfoSkpp = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoSkpp",
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


    $scope.statusSkpp = function(idx, statusx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "statusSkpp",
          data :{id: idx, status: statusx, datax: $scope.selesai}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalProsesSkpp').modal('hide');
          $('#keteranganModal').modal('hide');
          $('#tolakModal').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
          $scope.getSkpp();
        }, function error(e) {
          console.log(e.data.error);
          $scope.loadingModal = false;
          $('#modalProsesSkpp').modal('hide');
          $('#keteranganModal').modal('hide');
          $('#tolakModal').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.deleteSkpp = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteSkpp",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteSkpp').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getSkpp();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteSkpp').modal('hide');
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

    $scope.insertSkpp = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addSkpp",
          data :{datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddSkpp').modal('hide');
          toastr.success(e.data.success);
          $scope.getSkpp();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateSkpp = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateSkpp",
          data :{id: $scope.noid,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditSkpp').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
          $scope.getSkpp();
        }, function error(e) {
          console.log(e.data.error);
          $scope.loadingModal = false;
          //$('#modalEditDonatur').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.updateSupplier = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateSupplier",
          data :{id: $scope.noid,
                  datax: $scope.supplier}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditSupplier').modal('hide');
          $scope.btnShow = false;
          $scope.btnProses = false;
          $scope.btnSelesai = false;
          toastr.success(e.data.success);
          $scope.getSkpp();
        }, function error(e) {
          console.log(e.data.error);
          $scope.loadingModal = false;
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