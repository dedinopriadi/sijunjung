<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Format extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mformat');
        $this->load->library('upload');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'KPPN Sijunjung : Format Surat';

    	$this->loadViews("format/format", $this->global, NULL, NULL);
    }


    public function formatListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mformat->getFormatCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $formats = $this->Mformat->getDataFormat($start, $num, strtoupper($search));

        foreach($formats->result_array() as $row){
            $format[] = ['id' => $row['format_id'], 'judul' => $row['format_judul'], 'file' => $row['format_file'], 'extension' => $row['format_extension']];
        }

        $response = [];
            if (!empty($format)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['forma'] = $format;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Format Surat Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }



    public function getInfoFormat()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mformat->getInfoFormat($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Format Surat"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


     public function deleteFormat()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mformat->deleteFormat($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Format Surat Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Format Surat"]]));
            }

        }
    }



    function addFormat()
    {

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $judul = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $judul    = $_POST['judulx'];


        if(empty($judul)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Judul Belum Diisi"]]));
        } else {

            $config['upload_path'] = './uploads/document/surat/'; 
            $config['allowed_types'] = 'pdf|doc|docx'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['filex']['name'])){
     
                if ($this->upload->do_upload('filex')){
                    $file = $this->upload->data();
     
                    $doc   = $file['file_name'];
                    $exten = pathinfo($_FILES["filex"]["name"], PATHINFO_EXTENSION);

                    $formatInfo = array('format_judul'=>$judul, 'format_file'=>$doc, 'format_extension'=>$exten);

                    $result = $this->Mformat->addNewFormat($formatInfo);
                
                    if($result > 0) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Format Surat Berhasil Ditambahkan"]]));
                    } else {
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Format Surat"]]));
                    }
                }
                          
            }else{

                $formatInfo = array('format_judul'=>$judul);

                $result = $this->Mformat->addNewFormat($formatInfo);
            
                if($result > 0) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Format Surat Berhasil Ditambahkan"]]));
                } else {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Format Surat"]]));
                }
                
            }             
        }   
    }


    public function updateFormat()
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

            $config['upload_path'] = './uploads/document/surat/'; 
            $config['allowed_types'] = 'pdf|doc|docx'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['filex']['name'])){
     
                if ($this->upload->do_upload('filex')){
                    $file = $this->upload->data();
     
                    $doc   = $file['file_name'];
                    $exten = pathinfo($_FILES["filex"]["name"], PATHINFO_EXTENSION);

                    $formatInfo = array('format_judul'=>$judul, 'format_file'=>$doc, 'format_extension'=>$exten);

                    $result = $this->Mformat->saveFormat($formatInfo, $id);
                
                    if($result > 0) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Format Surat Berhasil Ditambahkan"]]));
                    } else {
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Format Surat"]]));
                    }
                    
                }
                          
            }else{

                $formatInfo = array('format_judul'=>$judul);

                $result = $this->Mformat->saveFormat($formatInfo, $id);
            
                if($result > 0) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Format Surat Berhasil Ditambahkan"]]));
                } else {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Format Surat"]]));
                }
                
            } 
        } 
    }


}