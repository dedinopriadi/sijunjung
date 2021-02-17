<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Dhuafa extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mdhuafa');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'Jamaah Masjid : Dhuafa';

    	$this->loadViews("dhuafa/dhuafa", $this->global, NULL, NULL);
    }

    public function dhuafaListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);
        $masjid = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mdhuafa->getDhuafaCount(strtoupper($search), $masjid);
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $dhuafas = $this->Mdhuafa->getDataDhuafa($start, $num, strtoupper($search), $masjid);

        foreach($dhuafas->result_array() as $row){
            $dhuafa[] = ['id' => $row['dhuafa_id'], 'nik' => $row['dhuafa_nik'], 'nama' => $row['dhuafa_nama'], 'usia' => $row['dhuafa_usia'], 'lat' => $row['dhuafa_lat'], 'longi' => $row['dhuafa_long'], 'alamat' => $row['dhuafa_alamat']];
        }

        $response = [];
            if (!empty($dhuafa)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['don'] = $dhuafa;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Dhuafa Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoDhuafa()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mdhuafa->getInfoDhuafa($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Dhuafa"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addDhuafa()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $masjid   = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);
        $nik      = (isset($postdata['datax']['nik']) ? $postdata['datax']['nik'] : NULL);
        $nama     = (isset($postdata['datax']['nama']) ? $postdata['datax']['nama'] : NULL);
        $usia     = (isset($postdata['datax']['usia']) ? $postdata['datax']['usia'] : NULL);
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
            $data   = array('dhuafa_nik' => $nik, 'dhuafa_nama' => $nama, 'dhuafa_lat' => $lati, 'dhuafa_long' => $longi, 'dhuafa_alamat' => $alamat, 'dhuafa_usia' => $usia, 'masjid_id' => $masjid);
            $insert = $this->Mdhuafa->addDhuafa($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Dhuafa Berhasil Ditambahkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Dhuafa"]]));
            }

        }
    }


    public function updateDhuafa()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $nik      = (isset($postdata['datax']['dhuafa_nik']) ? $postdata['datax']['dhuafa_nik'] : NULL);
        $nama     = (isset($postdata['datax']['dhuafa_nama']) ? $postdata['datax']['dhuafa_nama'] : NULL);
        $usia     = (isset($postdata['datax']['dhuafa_usia']) ? $postdata['datax']['dhuafa_usia'] : NULL);
        $alamat   = (isset($postdata['datax']['dhuafa_alamat']) ? $postdata['datax']['dhuafa_alamat'] : NULL);
        $lati     = (isset($postdata['datax']['dhuafa_lat']) ? $postdata['datax']['dhuafa_lat'] : NULL);
        $longi    = (isset($postdata['datax']['dhuafa_long']) ? $postdata['datax']['dhuafa_long'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Dhuafa Kosong"]]));
        }else{

            $data   = array('dhuafa_nik' => $nik, 'dhuafa_nama' => $nama, 'dhuafa_lat' => $lati, 'dhuafa_long' => $longi, 'dhuafa_alamat' => $alamat, 'dhuafa_usia' => $usia);
            $update = $this->Mdhuafa->updateDhuafa($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Dhuafa Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Dhuafa"]]));
            }

        }
    }

    public function deleteDhuafa()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('dhuafa_data_stat' => '0');
            $update = $this->Mdhuafa->updateDhuafa($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Dhuafa Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Dhuafa"]]));
            }

        }
    }

}