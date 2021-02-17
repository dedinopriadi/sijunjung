<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Jamaah extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mjamaah');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'Jamaah Masjid : Jamaah';

    	$this->loadViews("jamaah/jamaah", $this->global, NULL, NULL);
    }

    public function jamaahListing()
    {
    	$postdata = json_decode(file_get_contents('php://input'), TRUE);
    	$num  = (isset($postdata['num']) ? $postdata['num'] : 50);
    	$page = (isset($postdata['page']) ? $postdata['page'] : 1);
    	$masjid = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);

    	// $account = (isset($postdata['filter']['sl_account']) ? $postdata['filter']['sl_account'] : NULL);
        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

    	$count = $this->Mjamaah->getJamaahCount(strtoupper($search), $masjid);
    	$total = (int) $count;

		$total_pages = (($total - 1) / $num) + 1;
		$total_pages =  intval($total_pages);
		$page = intval($page);

		if(empty($page) or $page < 0) $page = 1;
		if($page > $total_pages) $page = $total_pages;

		$start = $page * $num - $num;

		$members = $this->Mjamaah->getDataJamaah($start, $num, strtoupper($search), $masjid);

		foreach($members->result_array() as $row){
			$member[] = ['nik' => $row['jamaah_nik'], 'nama' => $row['jamaah_nama'], 'hp' => $row['jamaah_hp'], 'email' => $row['jamaah_email'], 'alamat' => $row['jamaah_alamat']];
		}

		$response = [];
			if (!empty($member)) {	
				$response['page'] = $page;	

				$response['total_count'] = $total;
				$response['jam'] = $member;		
			}
			else {
				$response['status'] = 'empty';
				$response['error'] = 1;	
			}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoJamaah()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $nik = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mjamaah->getInfoJamaah($nik);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Jamaah"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function updateJamaah()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $nik      = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $nama     = (isset($postdata['datax']['jamaah_nama']) ? $postdata['datax']['jamaah_nama'] : NULL);
        $nohp     = (isset($postdata['datax']['jamaah_hp']) ? $postdata['datax']['jamaah_hp'] : NULL);
        $email    = (isset($postdata['datax']['jamaah_email']) ? $postdata['datax']['jamaah_email'] : NULL);
        $alamat   = (isset($postdata['datax']['jamaah_alamat']) ? $postdata['datax']['jamaah_alamat'] : NULL);

        if(empty($nik)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi NIK Kosong"]]));
        }else{

            $data   = array('jamaah_nama' => $nama, 'jamaah_hp' => $nohp, 'jamaah_email' => $email, 'jamaah_alamat' => $alamat);
            $update = $this->Mjamaah->updateJamaah($nik, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Jamaah Berhasil Diubah"]]));
            }else{
            	http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Data Jamaah"]]));
            }

        }
    }


    public function deleteJamaah()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $nik      = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($nik)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi NIK Kosong"]]));
        }else{

            $data   = array('jamaah_data_stat' => '0');
            $update = $this->Mjamaah->updateJamaah($nik, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Jamaah Berhasil Dihapus"]]));
            }else{
            	http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Jamaah"]]));
            }

        }
    }

}