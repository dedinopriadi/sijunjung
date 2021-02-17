<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Kajian extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mkajian');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'Jamaah Masjid : Informasi Kajian';

        $this->loadViews("kajian/kajian", $this->global, NULL, NULL);
    }

    public function kajianListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);
        $masjid = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mkajian->getKajianCount(strtoupper($search), $masjid);
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $kajians = $this->Mkajian->getDataKajian($start, $num, strtoupper($search), $masjid);

        foreach($kajians->result_array() as $row){
            $kajian[] = ['id' => $row['kajian_id'], 'judul' => $row['kajian_judul'], 'lokasi' => $row['kajian_lokasi'], 'tanggal' => $row['kajian_tgl'], 'waktu' => $row['kajian_jam'], 'admin' => $row['name']];
        }

        $response = [];
            if (!empty($kajian)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['kaj'] = $kajian;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Kajian Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoKajian()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mkajian->getInfoKajian($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Kajian"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addKajian()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $masjid   = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);
        $admin    = (isset($postdata['admin']) ? $postdata['admin'] : NULL);
        $judul    = (isset($postdata['datax']['judul']) ? $postdata['datax']['judul'] : NULL);
        $lokasi   = (isset($postdata['datax']['lokasi']) ? $postdata['datax']['lokasi'] : NULL);
        $tgl      = (isset($postdata['tgl']) ? $postdata['tgl'] : NULL);
        $waktu    = (isset($postdata['waktu']) ? $postdata['waktu'] : NULL);

        if(empty($masjid)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Masjid Kosong"]]));
        }else if(empty($admin)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi Admin Kosong"]]));
        }else if(empty($judul) || empty($tgl) || empty($waktu)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('kajian_judul' => $judul, 'kajian_lokasi' => $lokasi, 'kajian_tgl' => $tgl, 'kajian_jam' => $waktu, 'admin_id' => $admin, 'masjid_id' => $masjid);
            $insert = $this->Mkajian->addKajian($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kajian Berhasil Ditambahkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Kajian"]]));
            }

        }
    }


    public function updateKajian()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $judul    = (isset($postdata['datax']['kajian_judul']) ? $postdata['datax']['kajian_judul'] : NULL);
        $lokasi   = (isset($postdata['datax']['kajian_lokasi']) ? $postdata['datax']['kajian_lokasi'] : NULL);
        $tgl      = (isset($postdata['datax']['kajian_tgl']) ? $postdata['datax']['kajian_tgl'] : NULL);
        $waktu    = (isset($postdata['datax']['kajian_jam']) ? $postdata['datax']['kajian_jam'] : NULL);
        
        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kajian Kosong"]]));
        }else{

            $data   = array('kajian_judul' => $judul, 'kajian_lokasi' => $lokasi, 'kajian_tgl' => $tgl, 'kajian_jam' => $waktu);
            $update = $this->Mkajian->updateKajian($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kajian Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Kajian"]]));
            }

        }
    }

    public function deleteKajian()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('kajian_data_stat' => '0');
            $update = $this->Mkajian->updateKajian($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kajian Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Kajian"]]));
            }

        }
    }

}