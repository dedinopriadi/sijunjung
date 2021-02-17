var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('peraturanCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.peraturan = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.form = [];
  $scope.files = [];

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 10; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getPeraturan = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "peraturanListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.peraturan = e.data.perat;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.peraturan = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getPeraturan();


    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.judul = $scope.peraturan[indexs].judul;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


  $scope.getInfoPeraturan = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoPeraturan",
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


    $scope.deletePeraturan = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deletePeraturan",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeletePeraturan').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getPeraturan();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeletePeraturan').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.insertPeraturan = function() {
      $scope.loadingModal = true;
      $scope.detail.file = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "addPeraturan",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("judulx", $scope.detail.judul);
              formData.append("filex", $scope.detail.file);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddPeraturan').modal('hide');
          toastr.success(e.data.success);
          $scope.getPeraturan();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updatePeraturan = function() {
      $scope.loadingModal = true;
      $scope.edit.peraturan_file = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "updatePeraturan",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("idx", $scope.noid); 
              formData.append("judulx", $scope.edit.peraturan_judul);
              formData.append("filex", $scope.edit.peraturan_file);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditPeraturan').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getPeraturan();
        }, function error(e) {
          console.log(e.data.error);
          //$('#modalEditDonatur').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.uploadedFile = function(element) {
        $scope.currentFile = element.files[0];
        var reader = new FileReader();
        reader.onload = function(event) {
          $scope.image_source = event.target.result
          $scope.$apply(function($scope) {
            $scope.files = element.files;
          });
        }
        reader.readAsDataURL(element.files[0]);
      }


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