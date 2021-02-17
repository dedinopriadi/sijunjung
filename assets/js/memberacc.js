var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('memberCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded  = false;
  $scope.errors  = [];
  $scope.members = [];
  $scope.filter  = {};
  $scope.edithpemail = {};
  $scope.detail = {};
  $scope.edit   = {};
  $scope.noid;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 50; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getMemberData = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "memberAccListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.members = e.data.mem;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        alert('Data error');
      });
    };

    $scope.getMemberData();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid = noid;
      $scope.detail.nama       = $scope.members[indexs].nama;
      $scope.detail.nohp_email = $scope.members[indexs].nohp_email;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };

    $scope.getNoHP = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getHpEmail",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edithpemail.nohp_email = e.data.nohp_email;
          $scope.loadingModal = false;
        }, function error(e) {
          console.log(e.data.error);
        });
    };

    $scope.getInfoMember = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoMemberAcc",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.loadingModal = false;
        }, function error(e) {
          console.log(e.data.error);
        });
    };

    $scope.updateNohpEmail = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateNohpEmail",
          data :{id: $scope.noid,
                  datax: $scope.edithpemail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditHPEmail').modal('hide');
          $scope.getMemberData();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalEditHPEmail').modal('hide');
        });
    };

    $scope.getAccount = function() {
        $http({
          method: 'POST',
          url: baseURL + "getAccount",
          data :{tipe: $scope.filter.sl_tipe}
        }).then(function success(e) {
          $scope.account = e.data;
        }, function error(e) {
          console.log(e.data.error);
        });
     };

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