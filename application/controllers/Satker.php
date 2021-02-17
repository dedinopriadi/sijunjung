<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Satker (SatkerController)
 * Satker Class to control all Satker related operations.
 * @author : Dedi Nopriadi
 */
class Satker extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Msatker');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : Satuan Kerja';

        $this->loadViews("satker/satker", $this->global, NULL, NULL);
    }


    public function satkerListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Msatker->getSatkerCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $satkers = $this->Msatker->getDataSatker($start, $num, strtoupper($search));

        foreach($satkers->result_array() as $row){
            $satker[] = ['id' => $row['satker_id'], 'kode' => $row['satker_kd'], 'nama' => $row['satker_nama'], 'email' => $row['satker_email']];
        }

        $response = [];
            if (!empty($satker)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['sat'] = $satker;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Satker Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoSatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Msatker->getInfoSatker($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Satker"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addSatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $kode     = (isset($postdata['datax']['kode']) ? $postdata['datax']['kode'] : NULL);
        $nama     = (isset($postdata['datax']['nama']) ? $postdata['datax']['nama'] : NULL);
        $email    = (isset($postdata['datax']['email']) ? $postdata['datax']['email'] : NULL);

        if(empty($nama) || empty($kode) || empty($email)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('satker_kd'=>$kode, 'satker_nama'=>$nama, 'satker_email'=>$email);
            $insert = $this->Msatker->addSatker($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Satker Berhasil Ditambahkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Satker"]]));
            }

        }
    }


    public function updateSatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $kode     = (isset($postdata['datax']['satker_kd']) ? $postdata['datax']['satker_kd'] : NULL);
        $nama     = (isset($postdata['datax']['satker_nama']) ? $postdata['datax']['satker_nama'] : NULL);
        $email    = (isset($postdata['datax']['satker_email']) ? $postdata['datax']['satker_email'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Satker Kosong"]]));
        }else{

            $data   = array('satker_kd'=>$kode, 'satker_nama'=>$nama, 'satker_email'=>$email);
            $update = $this->Msatker->updateSatker($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Satker Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Satker"]]));
            }

        }
    }

    public function deleteSatker()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Msatker->deleteSatker($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Satker Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Satker"]]));
            }

        }
    }


    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'KPPN Sijunjung : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }


}