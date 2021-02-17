<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Jadwal extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mjadwal');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : Jadwal Microlearning';

        $this->loadViews("jadwal/jadwal", $this->global, NULL, NULL);
    }

    public function jadwalListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mjadwal->getJadwalCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $jadwals = $this->Mjadwal->getDataJadwal($start, $num, strtoupper($search));

        foreach($jadwals->result_array() as $row){
            $jadwal[] = ['id' => $row['jadwal_id'], 'materi' => $row['jadwal_materi'], 'tanggal' => $row['jadwal_tgl'], 'waktu' => $row['jadwal_waktu'], 'keterangan' => $row['jadwal_keterangan']];
        }

        $response = [];
            if (!empty($jadwal)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['jad'] = $jadwal;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Jadwal Microlearning Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoJadwal()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mjadwal->getInfoJadwal($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Jadwal Microlearning"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addJadwal()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $admin    = (isset($postdata['admin']) ? $postdata['admin'] : NULL);
        $materi   = (isset($postdata['datax']['materi']) ? $postdata['datax']['materi'] : NULL);
        $ket      = (isset($postdata['datax']['keterangan']) ? $postdata['datax']['keterangan'] : NULL);
        $tgl      = (isset($postdata['tgl']) ? $postdata['tgl'] : NULL);
        $waktu    = (isset($postdata['waktu']) ? $postdata['waktu'] : NULL);

        if(empty($materi) || empty($tgl) || empty($waktu)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('jadwal_materi' => $materi, 'jadwal_tgl' => $tgl, 'jadwal_waktu' => $waktu, 'jadwal_keterangan' => $ket);
            $insert = $this->Mjadwal->addJadwal($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Jadwal Pelatihan Berhasil Dibuat"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Membuat jadwal Pelatihan"]]));
            }

        }
    }


    public function updateJadwal()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $materi   = (isset($postdata['datax']['jadwal_materi']) ? $postdata['datax']['jadwal_materi'] : NULL);
        $ket      = (isset($postdata['datax']['jadwal_keterangan']) ? $postdata['datax']['jadwal_keterangan'] : NULL);
        $tgl      = (isset($postdata['datax']['jadwal_tgl']) ? $postdata['datax']['jadwal_tgl'] : NULL);
        $waktu    = (isset($postdata['datax']['jadwal_waktu']) ? $postdata['datax']['jadwal_waktu'] : NULL);
        
        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Jadwal Kosong"]]));
        }else{

            $data   = array('jadwal_materi' => $materi, 'jadwal_tgl' => $tgl, 'jadwal_waktu' => $waktu, 'jadwal_keterangan' => $ket);
            $update = $this->Mjadwal->updateJadwal($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Jadwal Pelatihan Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Jadwal Pelatihan"]]));
            }

        }
    }

    public function deleteJadwal()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mjadwal->deleteJadwal($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Jadwal Pelatihan Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Jadwal Pelatihan"]]));
            }

        }
    }

}