var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('rekapCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.errors = [];
  $scope.rekap = [];
  $scope.filter = {};
  $scope.years;
  $scope.month;

  $scope.sum_total_trx;
  $scope.sum_total_tag;
  $scope.sum_tag;
  $scope.sum_admin;
  $scope.sum_f1;
  $scope.sum_f2;
  $scope.sum_f3;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 32; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getRekapData = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
      $http({
        method: 'POST',
        url: baseURL + "rekapTrxListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.rekap = e.data.rekaps;
        $scope.total_count = e.data.total_count;

        $scope.totalLembar  = e.data.totalLembar;
        $scope.totalTagihan = e.data.totalTagihan;
        $scope.totalAdmin = e.data.totalAdmin;
        $scope.tagihan    = e.data.tagihan;

        $scope.dataLoadedSuccessfully = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        alert('Data error');
      });
    };

    $scope.getRekapData($scope.pageno);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $scope.getYears = function() {
        $http({
          method: 'GET',
          url: baseURL + "getYear"
        }).then(function success(e) {
          $scope.years = e.data;
        }, function error(e) {
          console.log(e.data,e.error);
        });
     };

     $scope.loadMonth = function() {
        $http({
          method: 'GET',
          url: baseURL + "getMonth/" + $scope.filter.sl_year
        }).then(function success(e) {
          $scope.month = e.data;
        }, function error(e) {
          console.log(e.data,e.error);
        });
     };

     $scope.getYears();

  });
