<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Rekaptrx extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mrekaptrx');
        $this->isLoggedIn();   
    }

    public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'IGT : Rekap Transaksi';

    	$this->loadViews("report/rekap/rekap", $this->global, NULL, NULL);
    }

    public function rekap()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'IGT : Rekap Transaksi';

        $this->load->view("report/rekap/rekap", $this->global);   
    }

    public function rekapTrxListing()
    {
    	$postdata = json_decode(file_get_contents('php://input'), TRUE);
            $num  = (isset($postdata['num']) ? $postdata['num'] : 32);
            $page = (isset($postdata['page']) ? $postdata['page'] : 1);

            $filter = $postdata['filter'];
            $year  = (isset($postdata['filter']['sl_year']) ? $postdata['filter']['sl_year'] : NULL);
            $month = (isset($postdata['filter']['sl_month']) ? $postdata['filter']['sl_month'] : NULL);

            $count = $this->Mrekaptrx->getRekapCount($year, $month);
            $total = (int) $count;

            $total_pages = (($total - 1) / $num) + 1;
            $total_pages =  intval($total_pages);
            $page = intval($page);

            if(empty($page) or $page < 0) $page = 1;
            if($page > $total_pages) $page = $total_pages;

            $start = $page * $num - $num;

            $rekap = $this->Mrekaptrx->getRekapData($start, $num, $year, $month);

            $totalLembar  = 0;
            $totalTagihan = 0;
            $totalAdmin = 0;
            $tagihan    = 0;

            foreach($rekap->result_array() as $row){

                $pekaps[] = ['tanggal' => $row['tanggal'], 'lembar' => $this->numFormat($row['lembar']), 'total_trx' => $this->numFormat($row['total_trx']), 'total_tagihan' => $this->numFormat($row['total_tagihan']), 'tagihan' => $this->numFormat($row['tagihan']), 'admin' => $this->numFormat($row['admin']), 'fm1' => $row['f1'], 'fm2' => $row['f2'], 'fm3' => $row['f3']];

                $totalLembar  = $totalLembar + $row['lembar'];
                $totalTagihan = $totalTagihan + $row['total_tagihan'];
                $totalAdmin = $totalAdmin + $row['admin'];
                $tagihan    = $tagihan + $row['tagihan'];

            }


            $response = [];
            if (!empty($pekaps)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['rekaps'] = $pekaps;     

                $response['totalLembar']  = $this->numFormat($totalLembar);
                $response['totalTagihan'] = $this->numFormat($totalTagihan);
                $response['totalAdmin'] = $this->numFormat($totalAdmin);
                $response['tagihan']    = $this->numFormat($tagihan);
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($response));    
        
    }

    public function rekapPrd($tgl)
    {

        $this->global['pageTitle'] = 'IGT : Rekap Transaksi';

        $this->data['tgl'] = $tgl; 

        $this->loadViews("report/rekap/rekapPrd", $this->global, $this->data, NULL);
    }

    public function rekapPrdListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
            $tgl  = (isset($postdata['tgl']) ? $postdata['tgl'] : NULL);
            $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
            $page = (isset($postdata['page']) ? $postdata['page'] : 1);

            $filter = $postdata['filter'];
            $year  = (isset($postdata['filter']['sl_year']) ? $postdata['filter']['sl_year'] : NULL);
            $month = (isset($postdata['filter']['sl_month']) ? $postdata['filter']['sl_month'] : NULL);
            $day   = (isset($postdata['filter']['sl_day']) ? $postdata['filter']['sl_day'] : NULL);

        if($tgl == NULL){
            http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Terjadi kesalahan, mohon kembali ke page sebelumnya"]]));
        }else{

            $count = $this->Mrekaptrx->getrekapPrdCount($tgl, $year, $month, $day);
            $total = (int) $count;

            $total_pages = (($total - 1) / $num) + 1;
            $total_pages =  intval($total_pages);
            $page = intval($page);

            if(empty($page) or $page < 0) $page = 1;
            if($page > $total_pages) $page = $total_pages;

            $start = $page * $num - $num;

            $rekap = $this->Mrekaptrx->getRekapPrdData($start, $num, $tgl, $year, $month, $day);

            foreach($rekap->result_array() as $row){

                    $pekaps[] = ['product' => $row['product_detail'], 'transaksi' => $row['product']." ".$row['product_detail'], 'lembar' => $this->numFormat($row['lembar']), 'total_tagihan' => $this->numFormat($row['total_tagihan']), 'tagihan' => $this->numFormat($row['tagihan']), 'admin' => $this->numFormat($row['admin']), 'fm1' => $row['fm1'], 'fm2' => $row['fm2'], 'fm3' => $row['fm3']];

                }

            $response = [];
            if (!empty($pekaps)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['rekap'] = $pekaps;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($response));    
        }
    }

    public function rekapTrxPrdListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
            $tgl  = (isset($postdata['tgl']) ? $postdata['tgl'] : NULL);
            $prd  = (isset($postdata['produk']) ? $postdata['produk'] : NULL);

            $filter = $postdata['filter'];
            $year  = (isset($postdata['filter']['sl_year']) ? $postdata['filter']['sl_year'] : NULL);
            $month = (isset($postdata['filter']['sl_month']) ? $postdata['filter']['sl_month'] : NULL);
            $day   = (isset($postdata['filter']['sl_day']) ? $postdata['filter']['sl_day'] : NULL);

        if($prd == NULL){
            http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Terjadi kesalahan, mohon kembali ke page sebelumnya"]]));
        }else{

            $rekap = $this->Mrekaptrx->getTrxRekapPrdData($prd, $tgl, $year, $month, $day);

            foreach($rekap->result_array() as $row){

                    $pekaps[] = ['product' => $row['product_detail'], 'transaksi' => $row['product']." ".$row['product_detail'], 'idpel_name' => $row['idpel_name'], 'lembar' => $this->numFormat($row['lembar']), 'total_tagihan' => $this->numFormat($row['total_tagihan']), 'tagihan' => $this->numFormat($row['tagihan']), 'admin' => $this->numFormat($row['admin']), 'fm1' => $row['fm1'], 'fm2' => $row['fm2'], 'fm3' => $row['fm3']];

                }

            $response = [];
            if (!empty($pekaps)) {  
                $response['trx_rekap'] = $pekaps;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($response));    
        }
    }

}