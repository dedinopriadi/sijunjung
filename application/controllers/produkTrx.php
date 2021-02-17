<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class produkTrx extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reporting/m_produkTrx');
        $this->isLoggedIn();   
    }

    public function produkTrx($noid)
    {

        $this->global['pageTitle'] = 'IGT : Transaksi Produk';

        $this->data['noid'] = $noid; 

        $this->loadViews("report/jaringan/produkTransaksi", $this->global, $this->data, NULL);
    }

    public function produkTrxListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
            $noid = (isset($postdata['noid']) ? $postdata['noid'] : NULL);
            $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
            $page = (isset($postdata['page']) ? $postdata['page'] : 1);

            $filter = $postdata['filter'];
            $year  = (isset($postdata['filter']['sl_year']) ? $postdata['filter']['sl_year'] : NULL);
            $month = (isset($postdata['filter']['sl_month']) ? $postdata['filter']['sl_month'] : NULL);
            $day   = (isset($postdata['filter']['sl_day']) ? $postdata['filter']['sl_day'] : NULL);

        if($noid == NULL){
            http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Terjadi kesalahan, mohon kembali ke page sebelumnya"]]));
        }else{

            $count = $this->m_produkTrx->getTrxCount($noid, $year, $month, $day);
            $total = (int) $count;

            $total_pages = (($total - 1) / $num) + 1;
            $total_pages =  intval($total_pages);
            $page = intval($page);

            if(empty($page) or $page < 0) $page = 1;
            if($page > $total_pages) $page = $total_pages;

            $start = $page * $num - $num;

            $trx = $this->m_produkTrx->getTrxData($start, $num, $noid, $year, $month, $day);

            foreach($trx->result_array() as $row){

                    $trans[] = ['noid' => $row['noid'], 'product' => $row['product_detail'], 'transaksi' => $row['product']." ".$row['product_detail'], 'lembar' => $row['lembar'], 'total_tagihan' => $row['total_tagihan'], 'tagihan' => $row['tagihan'], 'admin' => $row['admin'], 'fee' => $row['fee']];

                }

            $response = [];
            if (!empty($trans)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['trans'] = $trans;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($response));    
        }
    }

    public function getLoketData()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
            $noid = (isset($postdata['noid']) ? $postdata['noid'] : NULL);

        $detail = $this->m_produkTrx->getLoketData($noid);

        $response = [];
        if (!empty($detail)) {   
            $response['detail'] = $detail;     
        }
        else {
            $response['status'] = 'empty';
            $response['error'] = 1; 
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));   
    }

    public function detailPodukTrx($noid, $product, $year = NULL, $month = NULL, $day = NULL)
    {
        $this->global['pageTitle'] = 'IGT : Detail Transaksi Produk';

         $this->data['noid']    = $noid;
         $this->data['product'] = $product;
         $this->data['year']    = $year;
         $this->data['month']   = $month;
         $this->data['day']     = $day;

        $this->loadViews("report/jaringan/detailProdukTrx", $this->global, $this->data, NULL);
    }

    public function detailPodukTrxListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
            $noid    = (isset($postdata['noid']) ? $postdata['noid'] : NULL);
            $product = (isset($postdata['product']) ? $postdata['product'] : NULL);
            $year    = (isset($postdata['year']) ? $postdata['year'] : NULL);
            $month   = (isset($postdata['month']) ? $postdata['month'] : NULL);
            $day     = (isset($postdata['day']) ? $postdata['day'] : NULL);
            $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
            $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        if($noid == NULL){
            http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Terjadi kesalahan, mohon kembali ke page sebelumnya"]]));
        }else{

            $count = $this->m_produkTrx->getDetailTrxCount($noid, $product, $year, $month, $day);
            $total = (int) $count;

            $total_pages = (($total - 1) / $num) + 1;
            $total_pages =  intval($total_pages);
            $page = intval($page);

            if(empty($page) or $page < 0) $page = 1;
            if($page > $total_pages) $page = $total_pages;

            $start = $page * $num - $num;

            $trx = $this->m_produkTrx->getDetailTrxData($start, $num, $noid, $product, $year, $month, $day);

            foreach($trx->result_array() as $row){

                    $trans[] = ['product' => $row['product_detail'], 'transaksi' => $row['product']." ".$row['product_detail'], 'idpel' => $row['idpel'], 'nama' => $row['idpel_name'], 'lembar' => $row['lembar'], 'total_tagihan' => $row['total_tagihan'], 'tagihan' => $row['tagihan'], 'admin' => $row['admin_bank'], 'fee' => $row['fm3']];

                }

            $response = [];
            if (!empty($trans)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['trans'] = $trans;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($response));    
        }
    }

}