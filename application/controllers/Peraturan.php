<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Peraturan extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mperaturan');
        $this->load->library('upload');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'KPPN Sijunjung : Informasi Peraturan';

    	$this->loadViews("peraturan/peraturan", $this->global, NULL, NULL);
    }


    public function peraturanListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mperaturan->getPeraturanCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $peraturans = $this->Mperaturan->getDataPeraturan($start, $num, strtoupper($search));

        foreach($peraturans->result_array() as $row){
            $peraturan[] = ['id' => $row['peraturan_id'], 'judul' => $row['peraturan_judul'], 'file' => $row['peraturan_file'], 'extension' => $row['peraturan_extension']];
        }

        $response = [];
            if (!empty($peraturan)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['perat'] = $peraturan;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Standar Pelayanan Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }



    public function getInfoPeraturan()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mperaturan->getInfoPeraturan($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Informasi Peraturan"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


     public function deletePeraturan()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mperaturan->deletePeraturan($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Peraturan Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Peraturan"]]));
            }

        }
    }



    function addPeraturan()
    {

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $judul = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $judul    = $_POST['judulx'];


        if(empty($judul)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Judul Belum Diisi"]]));
        } else {

            $config['upload_path'] = './uploads/document/'; 
            $config['allowed_types'] = 'pdf|doc|docx'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['filex']['name'])){
     
                if ($this->upload->do_upload('filex')){
                    $file = $this->upload->data();
     
                    $doc   = $file['file_name'];
                    $exten = pathinfo($_FILES["filex"]["name"], PATHINFO_EXTENSION);

                    $peraturanInfo = array('peraturan_judul'=>$judul, 'peraturan_file'=>$doc, 'peraturan_extension'=>$exten);

                    $result = $this->Mperaturan->addNewPeraturan($peraturanInfo);
                
                    if($result > 0) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Peraturan Berhasil Ditambahkan"]]));
                    } else {
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Peraturan"]]));
                    }
                }
                          
            }else{

                $peraturanInfo = array('peraturan_judul'=>$judul);

                $result = $this->Mperaturan->addNewPeraturan($peraturanInfo);
            
                if($result > 0) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Peraturan Berhasil Ditambahkan"]]));
                } else {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Peraturan"]]));
                }
                
            }             
        }   
    }


    public function updatePeraturan()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id    = "";
        $judul = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id    = $_POST['idx'];
        $judul = $_POST['judulx'];


        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        } else if(empty($judul)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Judul Belum Diisi"]]));
        } else {

            $config['upload_path'] = './uploads/document/'; 
            $config['allowed_types'] = 'pdf|doc|docx'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['filex']['name'])){
     
                if ($this->upload->do_upload('filex')){
                    $file = $this->upload->data();
     
                    $doc   = $file['file_name'];
                    $exten = pathinfo($_FILES["filex"]["name"], PATHINFO_EXTENSION);

                    $peraturanInfo = array('peraturan_judul'=>$judul, 'peraturan_file'=>$doc, 'peraturan_extension'=>$exten);

                    $result = $this->Mperaturan->savePeraturan($peraturanInfo, $id);
                
                    if($result > 0) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Peraturan Berhasil Ditambahkan"]]));
                    } else {
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Peraturan"]]));
                    }
                    
                }
                          
            }else{

                $peraturanInfo = array('peraturan_judul'=>$judul);

                $result = $this->Mperaturan->savePeraturan($peraturanInfo, $id);
            
                if($result > 0) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Peraturan Berhasil Ditambahkan"]]));
                } else {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Peraturan"]]));
                }
                
            } 
        } 
    }


}