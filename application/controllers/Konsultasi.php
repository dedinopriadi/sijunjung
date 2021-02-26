<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Konsultasi (KonsultasiController)
 * Konsultasi Class to control all Konsultasi related operations.
 * @author : Dedi Nopriadi
 */
class Konsultasi extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mkonsultasi');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : Konsultasi';

        $this->loadViews("konsultasi/konsultasi", $this->global, NULL, NULL);
    }


    public function konsultasiListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mkonsultasi->getKonsultasiCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $konsuls = $this->Mkonsultasi->getDataKonsultasi($start, $num, strtoupper($search));

        foreach($konsuls->result_array() as $row){
            $konsul[] = ['id' => $row['konsultan_id'], 'nama' => $row['konsultan_nama'], 'bagian' => $row['konsultan_bagian'], 'nohp' => $row['konsultan_hp'], 'email' => $row['konsultan_email'], 'ket' => $row['konsultan_keterangan']];
        }

        $response = [];
            if (!empty($konsul)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['konsul'] = $konsul;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Satker Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoKonsultasi()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mkonsultasi->getInfoKonsultasi($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Konsultan"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addKonsultasi()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $nama     = (isset($postdata['datax']['nama']) ? $postdata['datax']['nama'] : NULL);
        $bagian   = (isset($postdata['datax']['bagian']) ? $postdata['datax']['bagian'] : NULL);
        $nohp     = (isset($postdata['datax']['nohp']) ? $postdata['datax']['nohp'] : NULL);
        $email    = (isset($postdata['datax']['email']) ? $postdata['datax']['email'] : NULL);
        $ket      = (isset($postdata['datax']['ket']) ? $postdata['datax']['ket'] : NULL);

        if(empty($nama) || empty($bagian) || empty($email)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('konsultan_nama'=>$nama, 'konsultan_bagian'=>$bagian, 'konsultan_hp'=>$nohp, 'konsultan_email'=>$email, 'konsultan_keterangan'=>$ket);
            $insert = $this->Mkonsultasi->addKonsultasi($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Konsultan Berhasil Ditambahkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Konsultan"]]));
            }

        }
    }


    public function updateKonsultasi()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $nama     = (isset($postdata['datax']['konsultan_nama']) ? $postdata['datax']['konsultan_nama'] : NULL);
        $bagian   = (isset($postdata['datax']['konsultan_bagian']) ? $postdata['datax']['konsultan_bagian'] : NULL);
        $nohp     = (isset($postdata['datax']['konsultan_hp']) ? $postdata['datax']['konsultan_hp'] : NULL);
        $email    = (isset($postdata['datax']['konsultan_email']) ? $postdata['datax']['konsultan_email'] : NULL);
        $ket      = (isset($postdata['datax']['konsultan_keterangan']) ? $postdata['datax']['konsultan_keterangan'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Konsultan Kosong"]]));
        }else{

            $data   = array('konsultan_nama'=>$nama, 'konsultan_bagian'=>$bagian, 'konsultan_hp'=>$nohp, 'konsultan_email'=>$email, 'konsultan_keterangan'=>$ket);
            $update = $this->Mkonsultasi->updateKonsultasi($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Konsultan Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Konsultan"]]));
            }

        }
    }

    public function deleteKonsultasi()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mkonsultasi->deleteKonsultasi($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Konsultan Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Konsultan"]]));
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