<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Kegiatan extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mkegiatan');
        $this->load->library('upload');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'Jamaah Masjid : Informasi Kegiatan';

        $this->loadViews("kegiatan/kegiatan", $this->global, NULL, NULL);
    }

    public function kegiatanListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);
        $masjid = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mkegiatan->getKegiatanCount(strtoupper($search), $masjid);
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $kegiatans = $this->Mkegiatan->getDataKegiatan($start, $num, strtoupper($search), $masjid);

        foreach($kegiatans->result_array() as $row){
            $kegiatan[] = ['id' => $row['kegiatan_id'], 'nama' => $row['kegiatan_nama'], 'admin' => $row['name'], 'tanggal' => $row['kegiatan_tgl'], 'view' => $row['kegiatan_view']];
        }

        $response = [];
            if (!empty($kegiatan)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['kaj'] = $kegiatan;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Kajian Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function deleteKegiatan()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('kegiatan_data_stat' => '0');
            $update = $this->Mkegiatan->updateKegiatan($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kegiatan Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Kegiatan"]]));
            }

        }
    }


    public function getInfoKegiatan()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mkegiatan->getInfoKegiatan($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Kegiatan"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function addKegiatan()
    {
        $adminx = NULL;
        $masjid = NULL;
        $judul  = NULL;
        $isi    = NULL;
        $tgl    = NULL;

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $masjid   = json_decode($_POST['masjid']);
        $adminx   = json_decode($_POST['adminy']);
        $judul    = $_POST['judul'];
        $isi      = $_POST['isi'];
        $tgl      = $_POST['tgl'];

        if(empty($adminx)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi Admin Kosong"]]));
        }else if(empty($masjid)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Masjid Kosong"]]));
        }else if(empty($judul) || empty($tgl) || empty($isi)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong "]]));
        }else{

            $config['upload_path'] = './uploads/kegiatan/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['gambar']['name'])){
     
                if ($this->upload->do_upload('gambar')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/kegiatan/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/kegiatan/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $data   = array('kegiatan_nama' => $judul, 'admin_id' => $adminx, 'kegiatan_tgl' => $tgl, 'kegiatan_isi' => $isi, 'kegiatan_photo' => $img, 'masjid_id' => $masjid);

                    $insert = $this->Mkegiatan->addKegiatan($data);
                    if($insert > 0){
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kegiatan Berhasil Ditambahkan"]]));
                    }else{
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Kegiatan"]]));
                    }
                }                          
            } else{
                $data   = array('kegiatan_nama' => $judul, 'admin_id' => $adminx, 'kegiatan_tgl' => $tgl, 'kegiatan_isi' => $isi, 'masjid_id' => $masjid);

                $insert = $this->Mkegiatan->addKegiatan($data);
                if($insert > 0){
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kegiatan Berhasil Ditambahkan"]]));
                }else {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Kegiatan"]]));
                }
            }

        }
    }



    public function updateKegiatan()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id     = NULL;
        $judul  = NULL;
        $isi    = NULL;
        $tgl    = NULL;

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = $_POST['id'];
        $judul    = $_POST['judul'];
        $isi      = $_POST['isi'];
        $tgl      = $_POST['tgl'];

        
        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kajian Kosong"]]));
        }else{

            $config['upload_path'] = './uploads/kegiatan/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['kegiatan_photo']['name'])){
     
                if ($this->upload->do_upload('kegiatan_photo')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/kegiatan/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/kegiatan/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $data   = array('kegiatan_nama' => $judul, 'kegiatan_tgl' => $tgl, 'kegiatan_isi' => $isi, 'kegiatan_photo' => $img);

                    $update = $this->Mkegiatan->updateKegiatan($id, $data);
                    if($update == TRUE){
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kegiatan Berhasil Diubah"]]));
                    }else{
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Kegiatan"]]));
                    }
                }

            } else {
                
                $data   = array('kegiatan_nama' => $judul, 'kegiatan_tgl' => $tgl, 'kegiatan_isi' => $isi);

                $update = $this->Mkegiatan->updateKegiatan($id, $data);
                if($update == TRUE){
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Kegiatan Berhasil Diubah"]]));
                }else{
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Kegiatan"]]));
                }
            }

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        }
    }

}