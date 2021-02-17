<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Profil (ProfilController)
 * Profil Class to control all Profil related operations.
 * @author : Dedi Nopriadi
 */
class Profil extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mprofil');
        $this->load->library('upload');
        $this->isLoggedIn();   
    }


    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : Profil KPPN Sijunjung';

        $this->loadViews("profil/profil", $this->global, NULL, NULL);
    }

    public function getInfoProfil()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);

        $profil = $this->Mprofil->getInfoProfil();
        $alamat = $this->Mprofil->getInfoAlamat();
        $nocso  = $this->Mprofil->getInfoCso();

        $data  = ['alamat' => $alamat->profil_isi, 'cso' => $nocso->profil_isi, 'profile' => $profil->profil_isi];
        

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Profil KPPN Sijunjung";  
                $this->output->set_content_type('application/json')->set_output(json_encode($response)); 
            }
            else {
                
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Tidak Dapat Mengambil Profil"]]));
                

            }
    }


    public function getInfoIntegritas()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);

        $info = $this->Mprofil->getInfoIntegritas();

        $data = ['isi' => $info->profil_isi];
        

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Informasi Zona Integritas KPPN Sijunjung";  
                $this->output->set_content_type('application/json')->set_output(json_encode($response)); 
            }
            else {
                
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Tidak Dapat Mengambil Informasi Zona Integritas"]]));
                

            }
    }



    public function updateProfil()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $alamat = "";
        $cso    = "";
        $profil = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $alamat   = $_POST['alamatx'];
        $cso      = $_POST['scox'];
        $profil   = $_POST['profx'];

        // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Profil Berhasil Diubah"]]));

        if(empty($alamat)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Alamat Belum Diisi"]]));
        }else if(empty($cso)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["CSO Belum Diisi"]]));
        }else{


            $config['upload_path'] = './uploads/images/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);

            if(!empty($_FILES['gambar']['name'])){
     
                if ($this->upload->do_upload('gambar')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/images/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/images/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $alamat = array('profil_isi' => $alamat);
                    $cso    = array('profil_isi' => $cso);
                    $profil = array('profil_isi' => $profil, 'profil_gambar' => $img);

                    $this->Mprofil->updateProfil($alamat, '4');
                    $this->Mprofil->updateProfil($cso, '5');
                    $update = $this->Mprofil->updateProfil($profil, '1');
                    if($update > 0){
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Profil Berhasil Diubah"]]));
                    }else{
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Profil"]]));
                    }
                }                          
            } else{
                $alamat = array('profil_isi' => $alamat);
                $cso    = array('profil_isi' => $cso);
                $profil = array('profil_isi' => $profil);

                $this->Mprofil->updateProfil($alamat, '4');
                $this->Mprofil->updateProfil($cso, '5');
                $update = $this->Mprofil->updateProfil($profil, '1');
                if($update > 0){
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Profil Berhasil Diubah"]]));
                }else{
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Profil"]]));
                }
            }

        }
    }


    public function updateIntegritas()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $konten   = "";

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $konten   = $_POST['integx'];

        if(empty($konten)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Konten Belum Diisi"]]));
        }else{


            $config['upload_path'] = './uploads/images/'; 
            $config['allowed_types'] = '*'; 
            $config['encrypt_name'] = TRUE; 

            $this->upload->initialize($config);


            if(!empty($_FILES['gambar']['name'])){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["ISI AMAN"]]));
     
                if ($this->upload->do_upload('gambar')){
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library']='gd2';
                    $config['source_image']='./uploads/images/'.$gbr['file_name'];
                    $config['create_thumb']= FALSE;
                    $config['maintain_ratio']= FALSE;
                    $config['quality']= '50%';
                    $config['width']= 600;
                    $config['height']= 400;
                    $config['new_image']= './uploads/images/'.$gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
     
                    $img = $gbr['file_name'];

                    $integ  = array('profil_isi' => $konten, 'profil_gambar' => $img);

                    $update = $this->Mprofil->updateProfil($integ, '2');
                    if($update > 0){
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Informasi Zona Integritas Berhasil Diubah"]]));
                    }else{
                        http_response_code(400);
                        $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Informasi Zona Integritas"]]));
                    }
                }                          
            } else{
                // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["KOSONG AMAN"]]));

                $integ = array('profil_isi' => $konten);

                $update = $this->Mprofil->updateProfil($integ, '2');
                if($update > 0){
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Informasi Zona Integritas Berhasil Diubah"]]));
                }else{
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Informasi Zona Integritas"]]));
                }
            }

        }
    }


}