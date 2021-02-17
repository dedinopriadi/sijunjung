<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Masjid extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mmasjid');
        $this->load->library('upload');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'Jamaah Masjid : Masjid';

    	$this->loadViews("masjid/masjid", $this->global, NULL, NULL);
    }


     public function masjidListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mmasjid->getMasjidCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $masjids = $this->Mmasjid->getDataMasjid($start, $num, strtoupper($search));

        foreach($masjids->result_array() as $row){
            $masjid[] = ['id' => $row['masjid_id'], 'nama' => $row['masjid_nama'], 'lat' => $row['masjid_lat'], 'longi' => $row['masjid_long'], 'alamat' => $row['masjid_alamat'], 'user' => $row['name']];
        }

        $response = [];
            if (!empty($masjid)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['mas'] = $masjid;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Masjid Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoMasjid()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mmasjid->getInfoMasjid($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Masjid"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addMasjid()
    {
        $admin    = NULL;
        $nama     = NULL;
        $alamat   = NULL;
        $lat      = NULL;
        $longi    = NULL;

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $admin    = $_POST['admin'];
        $nama     = $_POST['nama'];
        $alamat   = $_POST['alamat'];
        $lat      = $_POST['lat'];
        $longi    = $_POST['longi'];

        if(empty($admin)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi Admin Kosong"]]));
        }else if(empty($nama) || empty($alamat) || empty($lat) || empty($longi)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{


            $config['upload_path'] = './uploads/masjid/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['gambar']['name'])){
     
                if ($this->upload->do_upload('gambar')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/masjid/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/masjid/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $data   = array('masjid_nama' => $nama, 'masjid_alamat' => $alamat, 'masjid_lat' => $lat, 'masjid_long' => $longi, 'masjid_user_create' => $admin, 'masjid_photo' => $img);
                    $insert = $this->Mmasjid->addMasjid($data);

                    if($insert > 0){
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Masjid Berhasil Ditambahkan"]]));
                    }else{
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Masjid"]]));
                    }
                }

            } else {
                
                $data   = array('masjid_nama' => $nama, 'masjid_alamat' => $alamat, 'masjid_lat' => $lat, 'masjid_long' => $longi, 'masjid_user_create' => $admin);
                $insert = $this->Mmasjid->addMasjid($data);

                if($insert > 0){
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Masjid Berhasil Ditambahkan"]]));
                }else{
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Masjid"]]));
                }
            }

        }
    }


    public function updateMasjid()
    {

        $id       = NULL;
        $nama     = NULL;
        $alamat   = NULL;
        $lat      = NULL;
        $longi    = NULL;

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = $_POST['id'];
        $nama     = $_POST['nama'];
        $alamat   = $_POST['alamat'];
        $lat      = $_POST['lat'];
        $longi    = $_POST['longi'];

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Masjid Kosong"]]));
        }else{

            $config['upload_path'] = './uploads/masjid/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['gambar']['name'])){
     
                if ($this->upload->do_upload('gambar')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/masjid/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/masjid/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $data   = array('masjid_nama' => $nama, 'masjid_alamat' => $alamat, 'masjid_lat' => $lat, 'masjid_long' => $longi, 'masjid_photo' => $img);
                    $update = $this->Mmasjid->updateMasjid($id, $data);

                    if($update == TRUE){
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Masjid Berhasil Diubah"]]));
                    }else{
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Masjid"]]));
                    }
                }

            } else {
                
                $data   = array('masjid_nama' => $nama, 'masjid_alamat' => $alamat, 'masjid_lat' => $lat, 'masjid_long' => $longi);
                $update = $this->Mmasjid->updateMasjid($id, $data);

                if($update == TRUE){
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Masjid Berhasil Diubah"]]));
                }else{
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Masjid"]]));
                }

            }

        }
    }

    public function deleteMasjid()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('masjid_data_stat' => '0');
            $update = $this->Mmasjid->updateMasjid($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Masjid Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Masjid"]]));
            }

        }
    }

}