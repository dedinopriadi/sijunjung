<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Admin extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Madmin');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'KPPN Sijunjung : Data Admin';

    	$this->loadViews("admin/admin", $this->global, NULL, NULL);
    }

    public function adminListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Madmin->getAdminCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $admins = $this->Madmin->getDataAdmin($start, $num, strtoupper($search));

        foreach($admins->result_array() as $row){
            $admin[] = ['id' => $row['userId'], 'nama' => $row['name'], 'email' => $row['email'], 'nohp' => $row['mobile'], 'role' => $row['role'], 'created' => $row['created_name']];
        }

        $response = [];
            if (!empty($admin)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['adm'] = $admin;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Admin Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function deleteAdmin()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('isDeleted' => '1');
            $update = $this->Madmin->updateAdmin($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Admin Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Admin"]]));
            }

        }
    }


    public function addAdmin()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $admin    = (isset($postdata['admin']) ? $postdata['admin'] : NULL);
        $nama     = (isset($postdata['datax']['nama']) ? $postdata['datax']['nama'] : NULL);
        $email    = (isset($postdata['datax']['email']) ? $postdata['datax']['email'] : NULL);
        $nohp     = (isset($postdata['datax']['nohp']) ? $postdata['datax']['nohp'] : NULL);
        $passwd   = (isset($postdata['datax']['password']) ? $postdata['datax']['password'] : NULL);
        $repasswd = (isset($postdata['datax']['konfir_password']) ? $postdata['datax']['konfir_password'] : NULL);
        $role     = (isset($postdata['datax']['role']) ? $postdata['datax']['role'] : NULL);
        if($masjid == NULL){
            $masjid     = (isset($postdata['datax']['masjid']) ? $postdata['datax']['masjid'] : NULL);
        }

        if($passwd != $repasswd){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Password tidak sama"]]));
        }else if(empty($nama) || empty($email) || empty($nohp) || empty($passwd) || empty($role)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $data   = array('email' => $email, 'password' => getHashedPassword($passwd), 'name' => $nama, 'mobile' => $nohp, 'roleId' => $role, 'createdBy' => $admin, 'createdDtm'=>date('Y-m-d H:i:s'), 'updatedBy' => $admin, 'updatedDtm'=>date('Y-m-d H:i:s'));
            $insert = $this->Madmin->addAdmin($data);

            if($insert > 0){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Admin Berhasil Ditambahkan"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan Data Admin"]]));
            }

        }
    }


    public function getRoles()
    {
        $roles = $this->Madmin->getRoles()->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($roles));
    }


    public function getInfoAdmin()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Madmin->getInfoAdmin($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Admin"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }



    public function updateAdmin()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $nama     = (isset($postdata['datax']['name']) ? $postdata['datax']['name'] : NULL);
        $email    = (isset($postdata['datax']['email']) ? $postdata['datax']['email'] : NULL);
        $nohp     = (isset($postdata['datax']['mobile']) ? $postdata['datax']['mobile'] : NULL);
        $role     = (isset($postdata['datax']['roleId']) ? $postdata['datax']['roleId'] : NULL);

        date_default_timezone_set('Asia/Jakarta');
        $wkt    = date('Y-m-d H:i:s');

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $data   = array('name'=>$nama, 'email'=>$email, 'mobile'=>$nohp, 'roleId'=>$role, 'updatedDtm'=>$wkt);
            $update = $this->Madmin->updateAdmin($id, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Admin Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Admin"]]));
            }

        }
    }


}