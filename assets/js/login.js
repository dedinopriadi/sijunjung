var app = angular.module('App', []);
app.controller('loginCtrl',function($scope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = true;
  $scope.loaded = true;
  $scope.errors = [];
  $scope.login  = {};

  $scope.checkLogin = function() {
    $scope.dataLoadedSuccessfully = false;
    $scope.loaded = false;
      $http({
          method: 'POST',
          url: baseURL + "loginMe",
          data :{datax: $scope.login}
        }).then(function success(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.success(e.data.success);
          window.location.href = baseURL + "Dashboard";   
        }, function error(e) {
            $scope.dataLoadedSuccessfully = true;
            $scope.loaded = true;
          toastr.error(e.data.errors);
        });
    };


  ///////////////////////////////////////////////////////////////////////////////////////////////////
});