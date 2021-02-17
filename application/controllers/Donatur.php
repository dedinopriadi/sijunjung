<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Donatur extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mdonatur');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'Jamaah Masjid : Donatur';

    	$this->loadViews("donatur/donatur", $this->global, NULL, NULL);
    }

    public function donaturListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);
        $masjid = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mdonatur->getDonaturCount(strtoupper($search), $masjid);
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $donaturs = $this->Mdonatur->getDataDonatur($start, $num, strtoupper($search), $masjid);

        foreach($donaturs->result_array() as $row){
            $donatur[] = ['id' => $row['donatur_id'], 'nik' => $row['donatur_nik'], 'nama' => $row['donatur_nama'], 'lat' => $row['donatur_lat'], 'longi' => $row['donatur_long'], 'alamat' => $row['donatur_alamat']];
        }

        $response = [];
            if (!empty($donatur)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['don'] = $donatur;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Donatur Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function deleteDonatur()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('donatur_data_stat' => '0');
            $update = $this->Mdonatur->updateDonatur($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Donatur Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Donatur"]]));
            }

        }
    }


    public function getInfoDonatur()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mdonatur->getInfoDonatur($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Donatur"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addDonatur()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $masjid   = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);
        $nik      = (isset($postdata['datax']['nik']) ? $postdata['datax']['nik'] : NULL);
        $nama     = (isset($postdata['datax']['nama']) ? $postdata['datax']['nama'] : NULL);
        $alamat   = (isset($postdata['datax']['alamat']) ? $postdata['datax']['alamat'] : NULL);
        $lati     = (isset($postdata['datax']['latitude']) ? $postdata['datax']['latitude'] : NULL);
        $longi    = (isset($postdata['datax']['longitude']) ? $postdata['datax']['longitude'] : NULL);

        if(empty($masjid)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Masjid Kosong"]]));
        }

        if(empty($nama) || empty($alamat)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('donatur_nik' => $nik, 'donatur_nama' => $nama, 'donatur_lat' => $lati, 'donatur_long' => $longi, 'donatur_alamat' => $alamat, 'masjid_id' => $masjid);
            $insert = $this->Mdonatur->addDonatur($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Donatur Berhasil Ditambahkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Donatur"]]));
            }

        }
    }


    public function updateDonatur()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $nik      = (isset($postdata['datax']['donatur_nik']) ? $postdata['datax']['donatur_nik'] : NULL);
        $nama     = (isset($postdata['datax']['donatur_nama']) ? $postdata['datax']['donatur_nama'] : NULL);
        $alamat   = (isset($postdata['datax']['donatur_alamat']) ? $postdata['datax']['donatur_alamat'] : NULL);
        $lati     = (isset($postdata['datax']['donatur_lat']) ? $postdata['datax']['donatur_lat'] : NULL);
        $longi    = (isset($postdata['datax']['donatur_long']) ? $postdata['datax']['donatur_long'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Donatur Kosong"]]));
        }else{

            $data   = array('donatur_nik' => $nik, 'donatur_nama' => $nama, 'donatur_lat' => $lati, 'donatur_long' => $longi, 'donatur_alamat' => $alamat);
            $update = $this->Mdonatur->updateDonatur($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Donatur Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Donatur"]]));
            }

        }
    }


}