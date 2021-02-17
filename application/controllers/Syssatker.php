<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Syssatker extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Msyssatker');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'KPPN Sijunjung : User Satker';

    	$this->loadViews("syssatker/syssatker", $this->global, NULL, NULL);
    }

    public function syssatkerListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Msyssatker->getSyssatkerCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $syssatkers = $this->Msyssatker->getDataSyssatker($start, $num, strtoupper($search));

        foreach($syssatkers->result_array() as $row){
            $syssatker[] = ['id' => $row['syssatker_id'], 'nama' => $row['syssatker_nama'], 'email' => $row['syssatker_email'], 'nohp' => $row['syssatker_nohp'], 'satker' => $row['satker_nama'], 'created' => $row['name']];
        }

        $response = [];
            if (!empty($syssatker)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['syssat'] = $syssatker;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Admin Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function deleteSyssatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('syssatker_data_stat' => '0');
            $update = $this->Msyssatker->updateSyssatker($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["User Satker Berhasil Dinonaktifkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menonaktifkan User Satker"]]));
            }

        }
    }


    public function addSyssatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $admin    = (isset($postdata['admin']) ? $postdata['admin'] : NULL);
        $nama     = (isset($postdata['datax']['nama']) ? $postdata['datax']['nama'] : NULL);
        $email    = (isset($postdata['datax']['email']) ? $postdata['datax']['email'] : NULL);
        $nohp     = (isset($postdata['datax']['nohp']) ? $postdata['datax']['nohp'] : NULL);
        $satker   = (isset($postdata['datax']['satker']) ? $postdata['datax']['satker'] : NULL);
        $passwd   = "P4ssw0rd!";

        if(empty($admin)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Admin Kosong"]]));
        }else if(empty($nama) || empty($email) || empty($satker)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('syssatker_nama' => $nama, 'syssatker_email' => $email, 'syssatker_password' => md5($passwd), 'syssatker_nohp' => $nohp, 'syssatker_create_by' => $admin, 'syssatker_create_date'=>date('Y-m-d H:i:s'), 'syssatker_update_by' => $admin, 'syssatker_update_date'=>date('Y-m-d H:i:s'), 'satker_id' => $satker);
            $insert = $this->Msyssatker->addSyssatker($data);

            if($insert > 0){
                $mail = $this->sendVerification($email, $nama, $passwd);
                if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["User Satker Berhasil Dibuat. Email Notifikasi terkirim ke user"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["User Sartker Berhasil Dibuat. Email Notifikasi tidak terkirim ke user"]]));
                    }
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Membuat User Satker"]]));
            }

        }
    }


    public function getInfoSyssatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Msyssatker->getInfoSyssatker($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data User"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }



    public function updateSyssatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $admin    = (isset($postdata['admin']) ? $postdata['admin'] : NULL);
        $nama     = (isset($postdata['datax']['syssatker_nama']) ? $postdata['datax']['syssatker_nama'] : NULL);
        $email    = (isset($postdata['datax']['syssatker_email']) ? $postdata['datax']['syssatker_email'] : NULL);
        $nohp     = (isset($postdata['datax']['syssatker_nohp']) ? $postdata['datax']['syssatker_nohp'] : NULL);
        $satker   = (isset($postdata['datax']['satker_id']) ? $postdata['datax']['satker_id'] : NULL);

        date_default_timezone_set('Asia/Jakarta');
        $wkt    = date('Y-m-d H:i:s');

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('syssatker_nama'=>$nama, 'syssatker_email'=>$email, 'syssatker_nohp'=>$nohp, 'syssatker_update_by'=>$admin, 'syssatker_update_date'=>$wkt, 'satker_id'=>$satker);
            $update = $this->Msyssatker->updateSyssatker($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data User Satker Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data User Satker"]]));
            }

        }
    }


    function sendVerification($email, $nama, $passwd){

        if(!empty($email)){
            $data1["satker"] = $nama;
            $data1["surat"] = "";
            $data1["email"] = $email;
            $data1["subject"] = "Informasi User KPPN Sijunjung Mobile ";
            $data1["open_message"] = " Email Anda telah didaftarkan untuk dapat mengakses KPPN Sijunjung Mobile. ";
            $data1["message"] = " Gunakan ".$email." sebagai username dengan password ".$passwd."<br>Tetap jaga kerahasiaan Akun Anda";

            $sendStatus = verificationMail($data1);

            if($sendStatus){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }


}