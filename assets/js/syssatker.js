var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('syssatkerCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.syssatker = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.admin = $(".admin").val();
  $scope.ls_satker = [];

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;


      $scope.getSyssatker = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "syssatkerListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.syssatker = e.data.syssat;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.syssatker = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getSyssatker();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.nama = $scope.syssatker[indexs].nama;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.deleteSyssatker = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteSyssatker",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteSyssatker').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getSyssatker();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteSyssatker').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.addSyssatker = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addSyssatker",
          data :{admin: $scope.admin,
                 datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddSyssatker').modal('hide');
          toastr.success(e.data.success);
          $scope.getSyssatker();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
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


 $scope.getSatker();


 $scope.getInfoSyssatker = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoSyssatker",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.loadingModal = false;
          $scope.btnShow = false;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.error);
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };



  $scope.updateSyssatker = function() {
    $scope.loadingModal = true;
    $http({
        method: 'POST',
        url: baseURL + "updateSyssatker",
        data :{id: $scope.noid,
                admin: $scope.admin,
                datax: $scope.edit}
      }).then(function success(e) {
        $scope.loadingModal = false;
        $('#modalEditSyssatker').modal('hide');
        $scope.btnShow = false;
        toastr.success(e.data.success);
        $scope.getSyssatker();
      }, function error(e) {
        console.log(e.data.error);
        $scope.btnShow = false;
        toastr.error(e.data.errors);
      });
  };


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