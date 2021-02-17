<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Pengaduan extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mpengaduan');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'KPPN Sijunjung : Pengaduan';

    	$this->loadViews("pengaduan/pengaduan", $this->global, NULL, NULL);
    }

    public function myTest()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);
        $con  = (isset($postdata['conditi']) ? $postdata['conditi'] : "");

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mpengaduan->getPengaduanCount(strtoupper($search), $con);

        echo $count;
    }


    public function pengaduanListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);
        $con  = (isset($postdata['conditi']) ? $postdata['conditi'] : "");

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mpengaduan->getPengaduanCount(strtoupper($search), $con);
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $pengaduans = $this->Mpengaduan->getDataPengaduan($start, $num, strtoupper($search), $con);

        foreach($pengaduans->result_array() as $row){
            $pengaduan[] = ['id' => $row['pengaduan_id'], 'nama' => $row['pengaduan_nama'], 'email' => $row['pengaduan_email'], 'judul' => $row['pengaduan_judul'], 'view' => $row['pengaduan_view'], 'waktu' => $row['pengaduan_waktu']];
        }

        $response = [];
            if (!empty($pengaduan)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['pengadu'] = $pengaduan;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Pengaduan Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoPengaduan()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mpengaduan->getInfoPengaduan($id);

        $response = [];
            if (!empty($data)) {    
                $datax   = array('pengaduan_view' => '1');
                $update = $this->Mpengaduan->updatePengaduan($id, $datax);
                if($update == TRUE){
                    $response['info'] = $data;   
                    $response['success'] = "Data Referensi Ditemukan"; 
                }else{
                    $response['info'] = $data;   
                    $response['success'] = "Data Referensi Ditemukan";  
                } 
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Pengaduan"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

}