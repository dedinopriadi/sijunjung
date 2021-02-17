<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Standar extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mstandar');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'KPPN Sijunjung : Standar Pelayanan';

    	$this->loadViews("standar/standar", $this->global, NULL, NULL);
    }


    public function standarListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mstandar->getStandarCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $standars = $this->Mstandar->getDataStandar($start, $num, strtoupper($search));

        foreach($standars->result_array() as $row){
            $standar[] = ['id' => $row['standar_id'], 'judul' => $row['standar_judul']];
        }

        $response = [];
            if (!empty($standar)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['stand'] = $standar;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Standar Pelayanan Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }



    public function getInfoStandar()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mstandar->getInfoStandar($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Standar Pelayanan"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


     public function deleteStandar()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mstandar->deleteStandar($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Standar Pelayanan Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Standar Pelayanan"]]));
            }

        }
    }


    public function addStandar()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $judul    = (isset($postdata['datax']['judul']) ? $postdata['datax']['judul'] : NULL);
        $konten   = (isset($postdata['konten']) ? $postdata['konten'] : NULL);

        if(empty($judul) || empty($konten)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('standar_judul'=>$judul, 'standar_isi'=>$konten);
            $insert = $this->Mstandar->addStandar($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Standar Pelayanan Berhasil Ditambahkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Standar Pelayanan"]]));
            }

        }
    }


    public function updateStandar()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $judul    = (isset($postdata['datax']['standar_judul']) ? $postdata['datax']['standar_judul'] : NULL);
        $konten   = (isset($postdata['konten']) ? $postdata['konten'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Satker Kosong"]]));
        }else{

            $data   = array('standar_judul'=>$judul, 'standar_isi'=>$konten);
            $update = $this->Mstandar->updateStandar($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Standar Pelayanan Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Standar Pelayanan"]]));
            }

        }
    }


}