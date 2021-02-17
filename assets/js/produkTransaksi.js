var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('jaringanCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.errors = [];
  $scope.trx = [];
  $scope.filter = {};
  $scope.years;
  $scope.month;
  $scope.day;
  $scope.noid = $(".noid").val();
  $scope.loketName;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 30; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getTrxData = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
      $http({
        method: 'POST',
        url: baseURL + "produkTrxListing",
        data :{noid: $scope.noid,
          num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.trx = e.data.trans;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        alert('Data error');
      });
    };

    $scope.getTrxData($scope.pageno);


    $scope.getLoketData = function(pageno) {
      $http({
        method: 'POST',
        url: baseURL + "getLoketData",
        data :{noid: $scope.noid}
      }).then(function succes(e) {
        $scope.loketName = e.data.detail;
      }, function error(e) {
        //alert('Data error');
      });
    };

    $scope.getTrxData($scope.pageno);
    $scope.getLoketData();

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

     $scope.loadDay = function() {
        $http({
          method: 'GET',
          url: baseURL + "getDay/" + $scope.filter.sl_year + "/" + $scope.filter.sl_month
        }).then(function success(e) {
          $scope.day = e.data;
        }, function error(e) {
          console.log(e.data,e.error);
        });
     };

     $scope.getYears();
  });