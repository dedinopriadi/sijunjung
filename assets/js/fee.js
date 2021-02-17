var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('feeCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.errors = [];
  $scope.fee = [];
  $scope.filter = {};
  $scope.years;
  $scope.month;


    $scope.getRekapFee = function() {
       $scope.dataLoadedSuccessfully = false;
      $http({
        method: 'POST',
        url: baseURL + "rekapFeeListing",
        data :{filter: $scope.filter}
      }).then(function succes(e) {
        $scope.fee = e.data.fees;
        $scope.dataLoadedSuccessfully = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        alert('Data error');
      });
    };

    $scope.getRekapFee();

    $scope.getTotalTrx = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.fee.length; i++){
            $scope.ttrx = $scope.fee[i];
            if(parseInt($scope.ttrx.total_lembar) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.ttrx.total_lembar);
            } 
        }
        return $scope.total;
    }

    $scope.getTotalFm1 = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.fee.length; i++){
            $scope.tfm1 = $scope.fee[i];
            if(parseInt($scope.tfm1.fm1) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.tfm1.fm1);
            } 
        }
        return $scope.total;
    }

    $scope.getTotalFm2 = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.fee.length; i++){
            $scope.tfm1 = $scope.fee[i];
            if(parseInt($scope.tfm1.fm2) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.tfm1.fm2);
            } 
        }
        return $scope.total;
    }

    $scope.getTotalFm3 = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.fee.length; i++){
            $scope.tfm1 = $scope.fee[i];
            if(parseInt($scope.tfm1.fm3) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.tfm1.fm3);
            } 
        }
        return $scope.total;
    }


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
