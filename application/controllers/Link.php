<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Link extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mlink');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'Jamaah Masjid : Link Kajian';

    	$this->loadViews("link/link", $this->global, NULL, NULL);
    }

    public function getInfoLink()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $masjid   = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);

        if($masjid != NULL) {

        $data = $this->Mlink->getInfoLink($masjid);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Link Ditemukan";  
                $this->output->set_content_type('application/json')->set_output(json_encode($response)); 
            }
            else {
                $generate = $this->Mlink->generateLink($masjid);

                if($generate > 0){
                    $response['info'] = "";   
                    $response['success'] = "Menggenerate Link Kajian Default";
                    $this->output->set_content_type('application/json')->set_output(json_encode($response)); 
                }else{
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Tidak Dapat Menggenerate Link Kajian Default"]]));
                }

            }

        }
        
    }


    public function updateLink()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $masjid   = (isset($postdata['masjid']) ? $postdata['masjid'] : NULL);
        $alamat   = (isset($postdata['datax']['link_alamat']) ? $postdata['datax']['link_alamat'] : NULL);

        if(empty($masjid)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Masjid Kosong"]]));
        }else{

            date_default_timezone_set('Asia/Jakarta');
            $date = date('Y-m-d');

            $data   = array('link_alamat' => $alamat, 'link_last_update' => $date);
            $update = $this->Mlink->updateLink($masjid, $data);

            if($update == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Link Kajian Berhasil Diubah"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Mengubah Link Kajian"]]));
            }

        }
    }



}