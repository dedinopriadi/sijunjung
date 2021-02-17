var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('linkCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.loadingModal = false;
  $scope.loaded = false;
  $scope.errors = [];
  $scope.detail = {};
  $scope.noid;
  $scope.masjid = $(".masjid").val();


  $scope.getInfoLink = function() {
    $scope.dataLoadedSuccessfully = true;
    $scope.loaded = true;
      $('#modalEditLink').modal('show');
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoLink",
          data :{masjid: $scope.masjid}
        }).then(function success(e) {
          $scope.detail = e.data.info;
          $scope.loadingModal = false;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.errors);
          $scope.loadingModal = false;
          toastr.error(e.data.errors);
        });
  };

  $scope.getInfoLink();


  $scope.updateLink = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateLink",
          data :{masjid: $scope.masjid,
                  datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          toastr.success(e.data.success);
        }, function error(e) {
          $scope.loadingModal = false;
          toastr.error(e.data.errors);
        });
    };


  ///////////////////////////////////////////////////////////////////////////////////////////////////
});