<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = "Login";
$route['404_override'] = 'error';


/*********** USER DEFINED ROUTES *******************/

$route['panel'] = 'Login';
$route['loginMe'] = 'Login/loginMe';
$route['dashboard'] = 'user';
$route['dashboard/(:any)'] = 'user/$1';
$route['logout'] = 'User/logout';
$route['userListing'] = 'User/userListing';
$route['userListing/(:num)'] = "User/userListing/$1";
$route['addNew'] = "User/addNew";
$route['addNewUser'] = "User/addNewUser";
$route['editOld'] = "User/editOld";
$route['editOld/(:num)'] = "User/editOld/$1";
$route['editUser'] = "User/editUser";
$route['deleteUser'] = "User/deleteUser";
$route['profile'] = "User/profile";
$route['profile/(:any)'] = "User/profile/$1";
$route['profileUpdate'] = "User/profileUpdate";
$route['profileUpdate/(:any)'] = "User/profileUpdate/$1";

$route['loadChangePass'] = "User/loadChangePass";
$route['changePassword'] = "User/changePassword";
$route['changePassword/(:any)'] = "User/changePassword/$1";
$route['pageNotFound'] = "User/pageNotFound";
$route['checkEmailExists'] = "User/checkEmailExists";
$route['login-history'] = "User/loginHistoy";
$route['login-history/(:num)'] = "User/loginHistoy/$1";
$route['login-history/(:num)/(:num)'] = "User/loginHistoy/$1/$2";

$route['forgotPassword'] = "Login/forgotPassword";
$route['resetPasswordUser'] = "Login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "Login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "Login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "Login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "Login/createPasswordUser";

/*********** END USER DEFINED ROUTES *******************/



/************** DASHBOARD DEFINED ROUTES *******************/

$route['Dashboard'] = 'Dashboard';
$route['dashboardPengaduan'] = 'Dashboard/dashboardPengaduan';
$route['dashboardFeedback'] = 'Dashboard/dashboardFeedback';
$route['dashboardLastFeedback'] = 'Dashboard/dashboardLastFeedback';

/*********** END DASHBOARD DEFINED ROUTES *******************/



/*********** SATKER DEFINED ROUTES *******************/

$route['Satker'] = 'Satker';
$route['satkerListing'] = 'Satker/satkerListing';
$route['deleteSatker'] = 'Satker/deleteSatker';
$route['addSatker'] = 'Satker/addSatker';
$route['getInfoSatker'] = 'Satker/getInfoSatker';
$route['updateSatker'] = 'Satker/updateSatker';

/*********** END SATKER DEFINED ROUTES *******************/



/*********** KONSULTASI DEFINED ROUTES *******************/

$route['Konsultasi'] = 'Konsultasi';
$route['konsultasiListing'] = 'Konsultasi/konsultasiListing';
$route['deleteKonsultasi'] = 'Konsultasi/deleteKonsultasi';
$route['addKonsultasi'] = 'Konsultasi/addKonsultasi';
$route['getInfoKonsultasi'] = 'Konsultasi/getInfoKonsultasi';
$route['updateKonsultasi'] = 'Konsultasi/updateKonsultasi';

/*********** END KONSULTASI DEFINED ROUTES *******************/




/*********** STANDAR PELAYANAN DEFINED ROUTES *******************/

$route['Standar'] = 'Standar';
$route['standarListing'] = 'Standar/standarListing';
$route['deleteStandar'] = 'Standar/deleteStandar';
$route['addStandar'] = 'Standar/addStandar';
$route['getInfoStandar'] = 'Standar/getInfoStandar';
$route['updateStandar'] = 'Standar/updateStandar';

/*********** END STANDAR PELAYANAN DEFINED ROUTES *******************/


/*********** PERATURAN DEFINED ROUTES *******************/

$route['Peraturan'] = 'Peraturan';
$route['peraturanListing'] = 'Peraturan/peraturanListing';
$route['deletePeraturan'] = 'Peraturan/deletePeraturan';
$route['addPeraturan'] = 'Peraturan/addPeraturan';
$route['getInfoPeraturan'] = 'Peraturan/getInfoPeraturan';
$route['updatePeraturan'] = 'Peraturan/updatePeraturan';

/*********** END PERATURAN DEFINED ROUTES *******************/



/*********** FORMAT SURAT DEFINED ROUTES *******************/

$route['Format'] = 'Format';
$route['formatListing'] = 'Format/formatListing';
$route['deleteFormat'] = 'Format/deleteFormat';
$route['addFormat'] = 'Format/addFormat';
$route['getInfoFormat'] = 'Format/getInfoFormat';
$route['updateFormat'] = 'Format/updateFormat';

/*********** END FORMAT SURAT DEFINED ROUTES *******************/



/*********** BERITA DEFINED ROUTES *******************/

$route['Format'] = 'Format';
$route['formatListing'] = 'Format/formatListing';
$route['deleteFormat'] = 'Format/deleteFormat';
$route['addFormat'] = 'Format/addFormat';
$route['getInfoFormat'] = 'Format/getInfoFormat';
$route['updateFormat'] = 'Format/updateFormat';

/*********** END BERITA DEFINED ROUTES *******************/



/*********** BERITA DEFINED ROUTES *******************/

$route['Berita'] = 'Berita';
$route['beritaListing'] = 'Berita/beritaListing';
$route['deleteBerita'] = 'Berita/deleteBerita';
$route['addBerita'] = 'Berita/addBerita';
$route['getInfoBerita'] = 'Berita/getInfoBerita';
$route['updateBerita'] = 'Berita/updateBerita';
$route['uploadFileBerita'] = 'Berita/uploadFileBerita';

/*********** END BERITA DEFINED ROUTES *******************/



/***************** SKPP DEFINED ROUTES *******************/

$route['Skpp'] = 'Skpp';
$route['skppListing'] = 'Skpp/skppListing';
$route['deleteSkpp'] = 'Skpp/deleteSkpp';
$route['addSkpp'] = 'Skpp/addSkpp';
$route['getInfoSkpp'] = 'Skpp/getInfoSkpp';
$route['updateSkpp'] = 'Skpp/updateSkpp';
$route['statusSkpp'] = 'Skpp/statusSkpp';
$route['getSatker'] = 'Skpp/getSatker';
$route['updateSupplier'] = 'Skpp/updateSupplier';

/************* END SKPP DEFINED ROUTES *******************/



/***************** SPM KOREKSI DEFINED ROUTES *******************/

$route['Spmk'] = 'Spmk';
$route['spmkListing'] = 'Spmk/spmkListing';
$route['deleteSpmk'] = 'Spmk/deleteSpmk';
$route['addSpmk'] = 'Spmk/addSpmk';
$route['getInfoSpmk'] = 'Spmk/getInfoSpmk';
$route['updateSpmk'] = 'Spmk/updateSpmk';
$route['statusSpmk'] = 'Spmk/statusSpmk';

/************* END SPM KOREKSI DEFINED ROUTES *******************/



/***************** KONFIRMASI PENERIMAAN DEFINED ROUTES *******************/

$route['Konfirmasi'] = 'Konfirmasi';
$route['konfirmasiListing'] = 'Konfirmasi/konfirmasiListing';
$route['deleteKonfirmasi'] = 'Konfirmasi/deleteKonfirmasi';
$route['addKonfirmasi'] = 'Konfirmasi/addKonfirmasi';
$route['getInfoKonfirmasi'] = 'Konfirmasi/getInfoKonfirmasi';
$route['updateKonfirmasi'] = 'Konfirmasi/updateKonfirmasi';
$route['statusKonfirmasi'] = 'Konfirmasi/statusKonfirmasi';

/************* END KONFIRMASI PENERIMAAN DEFINED ROUTES *******************/



/***************** PROFIL KPPN DEFINED ROUTES *******************/

$route['Profil'] = 'Profil';
$route['getInfoProfil'] = 'Profil/getInfoProfil';
$route['updateProfil'] = 'Profil/updateProfil';
$route['saveProfil'] = 'Profil/saveProfil';
$route['getInfoIntegritas'] = 'Profil/getInfoIntegritas';
$route['updateIntegritas'] = 'Profil/updateIntegritas';
$route['getInfoKontak'] = 'Profil/getInfoKontak';
$route['updateKontak'] = 'Profil/updateKontak';

/************* END PROFIL KPPN DEFINED ROUTES *******************/



/*********** JADWAL DEFINED ROUTES *******************/

$route['Jadwal'] = 'Jadwal';
$route['jadwalListing'] = 'Jadwal/jadwalListing';
$route['deleteJadwal'] = 'Jadwal/deleteJadwal';
$route['addJadwal'] = 'Jadwal/addJadwal';
$route['getInfoJadwal'] = 'Jadwal/getInfoJadwal';
$route['updateJadwal'] = 'Jadwal/updateJadwal';
$route['getPeserta'] = 'Jadwal/getPeserta';

/*********** END JADWAL DEFINED ROUTES *******************/




/*********** PENGADUAN DEFINED ROUTES *******************/

$route['Pengaduan'] = 'Pengaduan';
$route['pengaduanListing'] = 'Pengaduan/pengaduanListing';
$route['getInfoPengaduan'] = 'Pengaduan/getInfoPengaduan';

/*********** END PENGADUAN DEFINED ROUTES *******************/




/*********** DHUAFA DEFINED ROUTES *******************/

$route['Dhuafa'] = 'Dhuafa';
$route['dhuafaListing'] = 'Dhuafa/dhuafaListing';
$route['deleteDhuafa'] = 'Dhuafa/deleteDhuafa';
$route['addDhuafa'] = 'Dhuafa/addDhuafa';
$route['getInfoDhuafa'] = 'Dhuafa/getInfoDhuafa';
$route['updateDhuafa'] = 'Dhuafa/updateDhuafa';

/*********** END DHUAFA DEFINED ROUTES *******************/



/*********** KAJIAN DEFINED ROUTES *******************/

$route['Kajian'] = 'Kajian';
$route['kajianListing'] = 'Kajian/kajianListing';
$route['deleteKajian'] = 'Kajian/deleteKajian';
$route['addKajian'] = 'Kajian/addKajian';
$route['getInfoKajian'] = 'Kajian/getInfoKajian';
$route['updateKajian'] = 'Kajian/updateKajian';

/*********** END KAJIAN DEFINED ROUTES *******************/



/*********** MASJID DEFINED ROUTES *******************/

$route['Masjid'] = 'Masjid';
$route['masjidListing'] = 'Masjid/masjidListing';
$route['deleteMasjid'] = 'Masjid/deleteMasjid';
$route['addMasjid'] = 'Masjid/addMasjid';
$route['getInfoMasjid'] = 'Masjid/getInfoMasjid';
$route['updateMasjid'] = 'Masjid/updateMasjid';

/*********** END MASJID DEFINED ROUTES *******************/




/***************** LINK DEFINED ROUTES *******************/

$route['Link'] = 'Link';
$route['getInfoLink'] = 'Link/getInfoLink';
$route['updateLink'] = 'Link/updateLink';

/**************** END LINK DEFINED ROUTES *******************/



/***************** ADMIN DEFINED ROUTES *******************/

$route['Admin'] = 'Admin';
$route['adminListing'] = 'Admin/adminListing';
$route['deleteAdmin'] = 'Admin/deleteAdmin';
$route['addAdmin'] = 'Admin/addAdmin';
$route['getInfoAdmin'] = 'Admin/getInfoAdmin';
$route['updateAdmin'] = 'Admin/updateAdmin';
$route['getRoles'] = 'Admin/getRoles';
$route['getMasjids'] = 'Admin/getMasjids';

/************* END ADMIN DEFINED ROUTES *******************/


/***************** USER SATKER DEFINED ROUTES *******************/

$route['Syssatker'] = 'Syssatker';
$route['syssatkerListing'] = 'Syssatker/syssatkerListing';
$route['deleteSyssatker'] = 'Syssatker/deleteSyssatker';
$route['addSyssatker'] = 'Syssatker/addSyssatker';
$route['getInfoSyssatker'] = 'Syssatker/getInfoSyssatker';
$route['updateSyssatker'] = 'Syssatker/updateSyssatker';

/************* END USER SATKER DEFINED ROUTES *******************/




/* End of file routes.php */
/* Location: ./application/config/routes.php */