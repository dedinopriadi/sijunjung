var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('profilCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = true;
  $scope.btnShow = false;
  $scope.loaded = true;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.profil = {};
  $scope.integritas = {};
  $scope.detail = {};
  $scope.edit = {};
  $scope.noid;
  $scope.konten;
  $scope.konten2;
  $scope.form = [];
  $scope.files = [];

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getProfil = function() {
    $scope.dataLoadedSuccessfully = false;
      $scope.loaded = false;
      $http({
          method: 'POST',
          url: baseURL + "getInfoProfil",
          data :{}
        }).then(function success(e) {
          $scope.profil = e.data.info;
          $scope.konten = $scope.profil.profile;
          $('#txtProfil').summernote('code', $scope.konten);
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.errors);
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.error(e.data.errors);
        });
  };

  $scope.getProfil();


  $scope.getInfoIntegritas = function() {
    $scope.dataLoadedSuccessfully = false;
      $scope.loaded = false;
      $http({
          method: 'POST',
          url: baseURL + "getInfoIntegritas",
          data :{}
        }).then(function success(e) {
          $scope.integritas = e.data.info;
          $scope.konten2 = $scope.integritas.isi;
          $('#txtIntegritas').summernote('code', $scope.konten2);
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.errors);
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.error(e.data.errors);
        });
  };



    $scope.uploadImage = function() {
      $scope.dataLoadedSuccessfully = false;
      $scope.loaded = false;
      $scope.detail.gambar = $scope.files[0];

      $http({
          method: 'POST',
          url: baseURL + "updateProfil", 
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("profx", $("#txtProfil").summernote('code'));
              formData.append("alamatx", $scope.profil.alamat);
              formData.append("scox", $scope.profil.cso);
              formData.append("gambar", $scope.detail.gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.success(e.data.success);
        }, function error(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          console.log(e.data.errors);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateIntegritas = function() {
      // $scope.dataLoadedSuccessfully = false;
      // $scope.loaded = false;
      $scope.profil.gambar = $scope.files[0];

      $http({
          method: 'POST',
          url: baseURL + "updateIntegritas", 
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("integx", $("#txtIntegritas").summernote('code'));
              formData.append("gambar", $scope.profil.gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.success(e.data.success);
        }, function error(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          console.log(e.data.errors);
          toastr.error(e.data.errors);
        });
    };


    // $scope.testPicker = function() {
    //   $scope.edit.kegiatan_photo = $scope.files[0];
    //   console.log($scope.edit.kegiatan_photo);
    // };

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