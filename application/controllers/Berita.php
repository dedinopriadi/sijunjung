<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Berita extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mberita');
        $this->load->library('upload');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'KPPN Sijunjung : Berita dan Informasi';

    	$this->loadViews("berita/berita", $this->global, NULL, NULL);
    }


    public function beritaListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mberita->getBeritaCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $beritas = $this->Mberita->getDataBerita($start, $num, strtoupper($search));

        foreach($beritas->result_array() as $row){
            $berita[] = ['id' => $row['berita_id'], 'judul' => $row['berita_judul'], 'penulis' => $row['name'], 'tanggal' => $row['berita_waktu'], 'file' => $row['berita_file'], 'extension' => $row['berita_file_extension']];
        }

        $response = [];
            if (!empty($berita)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['ber'] = $berita;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Berita dan Informasi Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }



    public function getInfoBerita()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mberita->getInfoBerita($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Berita dan Informasi"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


     public function deleteBerita()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mberita->deleteBerita($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Berita dan Informasi Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Berita dan Informasi"]]));
            }

        }
    }



    function addBerita()
    {

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $user   = "";
        $judul  = "";
        $konten = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $user     = $_POST['userx'];
        $judul    = $_POST['judulx'];
        $konten   = $_POST['isix'];

        date_default_timezone_set('Asia/Jakarta');
        $wkt    = date('Y-m-d H:i:s');


        if(empty($user)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID User Kosong"]]));
        } else if(empty($judul)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Judul Belum Diisi"]]));
        } else {

            $config['upload_path'] = './uploads/images/berita/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['gambarx']['name'])){
     
                if ($this->upload->do_upload('gambarx')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/images/berita/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/images/berita/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $datax = array('user_id'=>$user, 'berita_judul'=>$judul, 'berita_isi'=>$konten, 'berita_waktu'=>$wkt, 'berita_gambar'=>$img);

                    $result = $this->Mberita->addNewBerita($datax);
                
                    if($result > 0) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Berita dan Informasi Berhasil Ditambahkan"]]));
                    } else {
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Berita dan Informasi"]]));
                    }
                }
                          
            }else{

                $datax = array('user_id'=>$user, 'berita_judul'=>$judul, 'berita_isi'=>$konten, 'berita_waktu'=>$wkt);

                $result = $this->Mberita->addNewBerita($datax);
            
                if($result > 0) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Berita dan Informasi Berhasil Ditambahkan"]]));
                } else {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Berita dan Informasi"]]));
                }
                
            }             
        }   
    }


    public function updateBerita()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id     = "";
        $judul  = "";
        $konten = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = $_POST['idx'];
        $judul    = $_POST['judulx'];
        $konten   = $_POST['isix'];


        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        } else if(empty($judul)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Judul Belum Diisi"]]));
        } else {

            $config['upload_path'] = './uploads/images/berita/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['gambarx']['name'])){
     
                if ($this->upload->do_upload('gambarx')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/images/berita/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/images/berita/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $datax = array('berita_judul'=>$judul, 'berita_isi'=>$konten, 'berita_gambar'=>$img);

                    $result = $this->Mberita->saveBerita($datax, $id);
                
                    if($result > 0) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Berita dan Informasi Berhasil Diubah"]]));
                    } else {
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Berita dan Informasi"]]));
                    }
                    
                }
                          
            }else{

                $datax = array('berita_judul'=>$judul, 'berita_isi'=>$konten);

                $result = $this->Mberita->saveBerita($datax, $id);
            
                if($result > 0) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Berita dan Informasi Berhasil Diubah"]]));
                } else {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Berita dan Informasi"]]));
                }
                
            } 
        } 
    }


    public function uploadFileBerita()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id    = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id    = $_POST['idx'];


        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        } else {

            $config['upload_path'] = './uploads/document/berita/'; 
            $config['allowed_types'] = 'pdf|doc|docx'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['filex']['name'])){
     
                if ($this->upload->do_upload('filex')){
                    $file = $this->upload->data();
     
                    $doc   = $file['file_name'];
                    $exten = pathinfo($_FILES["filex"]["name"], PATHINFO_EXTENSION);

                    $datax = array('berita_file'=>$doc, 'berita_file_extension'=>$exten);

                    $result = $this->Mberita->saveBerita($datax, $id);
                
                    if($result > 0) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["File Lampiran Berhasil Ditambahkan"]]));
                    } else {
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan File Lampiran"]]));
                    }
                    
                }
                          
            }
            ///////////////////////////////////////
        } 
    }


}