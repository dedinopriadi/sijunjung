<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * @author : Dedi Nopriadi
 */
class Saldodeposit extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Msaldodeposit');
        $this->isLoggedIn();   
    }

     public function index()
    {
    	$this->load->library('pagination');

    	$this->global['pageTitle'] = 'IGT : Mutasi Dana Investasi';

    	$this->loadViews("topup/deposit", $this->global, NULL, NULL);
    }

    public function topupDepoListing()
    {
    	$postdata = json_decode(file_get_contents('php://input'), TRUE);
    	$num  = (isset($postdata['num']) ? $postdata['num'] : 50);
    	$page = (isset($postdata['page']) ? $postdata['page'] : 1);

    	$filter = $postdata['filter'];
    	$year  = (isset($postdata['filter']['sl_year']) ? $postdata['filter']['sl_year'] : NULL);
        $month = (isset($postdata['filter']['sl_month']) ? $postdata['filter']['sl_month'] : NULL);
        $day   = (isset($postdata['filter']['sl_day']) ? $postdata['filter']['sl_day'] : NULL);
        $search   = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $noid  = $this->global['vendorId'];

    	$count = $this->Msaldodeposit->getTopupDepoCount($search, $year, $month, $day, $noid);
    	$total = (int) $count;

		$total_pages = (($total - 1) / $num) + 1;
		$total_pages =  intval($total_pages);
		$page = intval($page);

		if(empty($page) or $page < 0) $page = 1;
		if($page > $total_pages) $page = $total_pages;

		$start = $page * $num - $num;

		$topup = $this->Msaldodeposit->getTopupDepo($start, $num, $search, $year, $month, $day, $noid);

        $totalSaldo  = 0;
        $totalAmount = 0;

		foreach($topup->result_array() as $row){
			   			    
		    $topups[] = ['id' => $row['id'], 'noid' => $row['noid'], 'nama' => $row['nama'], 'saldo' => $this->numFormat($row['saldo']), 'waktu' => substr($row['waktu'], 0, 19), 'amount' => $this->numFormat($row['amount']), 'keterangan' => $row['keterangan'], 'bank' => $row['bank'], 'product' => $row['product'], 'admin' => $row['admin']];

            $totalSaldo  = $totalSaldo + $row['saldo'];
            $totalAmount = $totalAmount + $row['amount'];
		}

			$response = [];
			if (!empty($topups)) {	
				$response['page'] = $page;	

				$response['total_count'] = $total;
				$response['tupupdepo'] = $topups;

                $response['totalSaldo']  = $this->numFormat($totalSaldo);
                $response['totalAmount'] = $this->numFormat($totalAmount);
			}
			else {
				$response['status'] = 'empty';
				$response['error'] = 1;	
			}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($response));	
    }

}