var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('masjidCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.masjid = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.admin = $(".admin").val();
  $scope.files = [];

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getMasjid = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "masjidListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.masjid = e.data.mas;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.masjid = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getMasjid();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.nama = $scope.masjid[indexs].nama;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.getInfoMasjid = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoMasjid",
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


    $scope.deleteMasjid = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteMasjid",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteMasjid').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getMasjid();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteMasjid').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.insertMasjid = function() {
      $scope.loadingModal = true;
      $scope.detail.gambar = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "addMasjid",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData();
              formData.append("admin", $scope.admin); 
              formData.append("nama", $scope.detail.nama); 
              formData.append("alamat", $scope.detail.alamat);
              formData.append("lat", $scope.detail.latitude);
              formData.append("longi", $scope.detail.longitude);
              formData.append("gambar", $scope.detail.gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddMasjid').modal('hide');
          toastr.success(e.data.success);
          $scope.getMasjid();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateMasjid = function() {
      $scope.loadingModal = true;
      $scope.edit.masjid_photo = $scope.files[0];

      $http({
          method: 'POST',
          url: baseURL + "updateMasjid",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData();
              formData.append("id", $scope.noid); 
              formData.append("nama", $scope.edit.masjid_nama); 
              formData.append("alamat", $scope.edit.masjid_alamat);
              formData.append("lat", $scope.edit.masjid_lat);
              formData.append("longi", $scope.edit.masjid_long);
              formData.append("gambar", $scope.edit.masjid_photo);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditMasjid').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getMasjid();
        }, function error(e) {
          console.log(e.data.error);
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