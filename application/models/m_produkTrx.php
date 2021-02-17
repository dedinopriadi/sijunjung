<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class m_produkTrx extends CI_Model
{

    private $dbigt;

    public function __construct()
    {
        parent::__construct();
        $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getTrxCount($noid, $year, $month, $day)
    {

    	$where = "AND extract(month from log_data_trx.waktu)=extract(month from CURRENT_TIMESTAMP) AND extract(year from log_data_trx.waktu)=extract(year from CURRENT_TIMESTAMP)";
        if($year != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year";
        }
        if($month != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month";
        }
        if($day != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day";
        }

        $sql = "SELECT COUNT(noid) AS total FROM log_data_trx WHERE noid LIKE '$noid%' $where AND stat=1 AND log_data_trx.detail->>'response_code'='0000' AND log_data_trx.detail->>'product_detail'!='SALDO' GROUP BY log_data_trx.detail->>'product_detail'";

        $result = $this->dbigt->query($sql);

        return $result->num_rows();
    }


    function getDetailTrxCount($noid, $product, $year, $month, $day)
    {

        $where = "AND extract(month from log_data_trx.waktu)=extract(month from CURRENT_TIMESTAMP) AND extract(year from log_data_trx.waktu)=extract(year from CURRENT_TIMESTAMP)";
        if($year != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year";
        }
        if($month != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month";
        }
        if($day != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day";
        }

        $sql = "SELECT COUNT(noid) AS total FROM log_data_trx WHERE noid='$noid' AND detail->>'product_detail'='$product' $where AND stat=1 AND log_data_trx.detail->>'response_code'='0000'";

        $result = $this->dbigt->query($sql);

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;

    }


    function getTrxData($start, $num, $noid, $year, $month, $day)
    {

        // $year  = (isset($filter['filter']['sl_year']) ? $filter['filter']['sl_year'] : NULL);
        // $month = (isset($filter['filter']['sl_month']) ? $filter['filter']['sl_month'] : NULL);
        // $day   = (isset($filter['filter']['sl_day']) ? $filter['filter']['sl_day'] : NULL);

        $where = "AND extract(month from log_data_trx.waktu)=extract(month from CURRENT_TIMESTAMP) AND extract(year from log_data_trx.waktu)=extract(year from CURRENT_TIMESTAMP)";
        if($year != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year";
        }
        if($month != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month";
        }
        if($day != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day";
        }

        $sql = "SELECT log_data_trx.noid,
        		log_data_trx.detail->>'product' AS product, 
				log_data_trx.detail->>'product_detail' AS product_detail, 
				SUM(CAST(log_data_trx.detail->>'lembar' AS INT)) AS lembar,
				SUM(CAST(log_data_trx.detail->>'total_tagihan' AS INT)) AS total_tagihan,
				SUM(CAST(log_data_trx.detail->>'tagihan' AS INT)) AS tagihan,
				SUM(CAST(log_data_trx.detail->>'admin_bank' AS INT)) AS admin,
				SUM(CAST(log_data_trx.jfee->>'fm3' AS INT)) AS fee
        FROM log_data_trx
        WHERE noid LIKE '$noid%' $where AND stat=1 AND log_data_trx.detail->>'response_code'='0000' AND log_data_trx.detail->>'product_detail'!='SALDO'
        GROUP BY log_data_trx.detail->>'product', log_data_trx.detail->>'product_detail', log_data_trx.noid
        ORDER BY product ASC
		LIMIT $num OFFSET $start";

        $result = $this->dbigt->query($sql);
        return $result;
    }


    function getDetailTrxData($start, $num, $noid, $product, $year, $month, $day)
    {

        $where = "AND extract(month from log_data_trx.waktu)=extract(month from CURRENT_TIMESTAMP) AND extract(year from log_data_trx.waktu)=extract(year from CURRENT_TIMESTAMP)";
        if($year != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year";
        }
        if($month != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month";
        }
        if($day != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day";
        }

        $sql = "SELECT log_data_trx.noid,
                log_data_trx.detail->>'product' AS product, 
                log_data_trx.detail->>'product_detail' AS product_detail,
                log_data_trx.detail->>'idpel' AS idpel,
                log_data_trx.detail->>'idpel_name' AS idpel_name,
                log_data_trx.detail->>'lembar' AS lembar,
                log_data_trx.detail->>'tagihan' AS tagihan,
                log_data_trx.detail->>'total_tagihan' AS total_tagihan,
                log_data_trx.detail->>'admin_bank' AS admin_bank,
                log_data_trx.jfee->>'fm3' AS fm3
        FROM log_data_trx
        WHERE noid = '$noid' AND detail->>'product_detail'='$product' $where AND stat=1 AND log_data_trx.detail->>'response_code'='0000'
        LIMIT $num OFFSET $start";

        $result = $this->dbigt->query($sql);
        return $result;

    }


    function getLoketData($noid)
    {
    	$this->dbigt->select('nama');
        $this->dbigt->from('tbl_member_account');
        $this->dbigt->where('noid', $noid);

        $result = $this->dbigt->get();

        foreach($result->result_array() as $row){
            $nama = $row['nama'];
        }
        return $nama;
    }

}