var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('dashboardCtrl',function($scope, $rootScope, $http){
  var vm = this;
    
    $scope.errors = [];
    $scope.viewDataPengaduan = false;
    $scope.viewDataAdmin = false;
    $scope.loaded = false;
    $scope.members = [];
    $scope.pengaduan = [];
    $scope.feedback = [];
    $scope.lastFeedback = [];
    $scope.years;
    $scope.month;
    $scope.day;
    $scope.fee;
    $scope.products;
    $scope.singlePrd;
    $scope.role = $(".role").val();



  $scope.getPengaduans = function() {
       $scope.viewDataPengaduan = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "dashboardPengaduan",
        data :{}
      }).then(function succes(e) {
        $scope.pengaduan = e.data.pengad;
        $scope.viewDataPengaduan = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.viewDataPengaduan = true;
        $scope.pengaduan = {};
        $scope.loaded = true;
        toastr.warning("Gagal Mengambil Data Pengaduan");
      });
  };

  $scope.getFeedback = function() {
       $scope.viewDataAdmin = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "dashboardFeedback",
        data :{}
      }).then(function succes(e) {
        $scope.feedback = e.data.feedb;
        $scope.viewDataAdmin = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.viewDataAdmin = true;
        $scope.feedback = {};
        $scope.loaded = true;
        toastr.warning("Gagal Mengambil Data Dashboard");
      });
  };


  $scope.getLastFeedback = function() {
       $scope.viewDataAdmin = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "dashboardLastFeedback",
        data :{}
      }).then(function succes(e) {
        $scope.lastFeedback = e.data.lastfeed;
        $scope.viewDataAdmin = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.viewDataAdmin = true;
        $scope.lastFeedback = {};
        $scope.loaded = true;
        toastr.warning("Gagal Mengambil Data Dashboard");
      });
  };

  $scope.enableDashboard = function() {
    $scope.loaded = true;
      if($scope.role == 4) {
        $scope.getPengaduans();
      } else {
        $scope.getFeedback();
        $scope.getLastFeedback();
      }

    };

  $scope.enableDashboard();


  $scope.getJumlahFeedback = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.feedback.length; i++){
            $scope.feedb = $scope.feedback[i];
            if(parseInt($scope.feedb.jumlah) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.feedb.jumlah);
            } 
        }
        return $scope.total;
    }


    
    
//////////////////////////////////////////////////////////////////////////////

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