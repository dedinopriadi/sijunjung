<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mrekaptrx extends CI_Model
{

    private $dbigt;

    public function __construct()
    {
        parent::__construct();
        $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getRekapCount($year, $month)
    {
    	$where = "extract(month from log_data_trx.waktu)=extract(month from CURRENT_TIMESTAMP) AND extract(year from log_data_trx.waktu)=extract(year from CURRENT_TIMESTAMP)";
        if($year != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year";
        }
        if($month != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month";
        }

        $sql = "SELECT SUBSTR(CAST(log_data_trx.waktu AS VARCHAR(11)), 0, 11) FROM log_data_trx WHERE $where GROUP BY SUBSTR(CAST(log_data_trx.waktu AS VARCHAR(11)), 0, 11) ";

        $result = $this->dbigt->query($sql);

        return $result->num_rows();

    }

    function getRekapData($start, $num, $year, $month)
    {
        $where = "extract(month from log_data_trx.waktu)=extract(month from CURRENT_TIMESTAMP) AND extract(year from log_data_trx.waktu)=extract(year from CURRENT_TIMESTAMP) AND";
        if($year != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND";
        }
        if($month != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND";
        }

        $sql = "SELECT SUBSTR(CAST(log_data_trx.waktu AS VARCHAR(11)), 0, 11) AS tanggal,
                    SUM(CAST(log_data_trx.lembar AS INT)) AS lembar,
                    COUNT(waktu) AS total_trx,
                    SUM(CAST(log_data_trx.detail->>'amount' AS INT)) AS total_tagihan,
                    SUM(CAST(log_data_trx.detail->>'tagihan' AS INT)) AS tagihan,
                    SUM(CAST(log_data_trx.detail->>'admin_bank' AS INT)) AS admin,
                    SUM(CAST(log_data_trx.jfee->>'fm1' AS INT)) AS f1,
                    SUM(CAST(log_data_trx.jfee->>'fm2' AS INT)) AS f2,
                    SUM(CAST(log_data_trx.jfee->>'fm3' AS INT)) AS f3
                FROM log_data_trx
                WHERE $where stat=1 AND log_data_trx.response_code='0000' AND log_data_trx.detail->>'product_detail'!='SALDO'
                GROUP BY tanggal
                ORDER BY tanggal ASC
                LIMIT $num OFFSET $start";

        $result = $this->dbigt->query($sql);
        return $result;
    }

    public function getrekapPrdCount($tgl, $year, $month, $day)
    {
        $where = "SUBSTR(CAST(log_data_trx.waktu AS VARCHAR(11)), 0, 11)='$tgl' AND";
        if($year != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND";
        }
        if($month != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND";
        }
        if($day != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day AND";
        }

        $sql = "SELECT log_data_trx.detail->>'product_detail' AS product FROM log_data_trx WHERE $where log_data_trx.detail->>'product_detail'!='SALDO' GROUP BY log_data_trx.detail->>'product_detail'";

        $result = $this->dbigt->query($sql);

        return $result->num_rows();
    }

    public function getRekapPrdData($start, $num, $tgl, $year, $month, $day)
    {
        $where = "SUBSTR(CAST(log_data_trx.waktu AS VARCHAR(11)), 0, 11)='$tgl' AND";
        if($year != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND";
        }
        if($month != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND";
        }
        if($day != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day AND";
        }

        $sql = "SELECT log_data_trx.detail->>'product' AS product, 
                log_data_trx.detail->>'product_detail' AS product_detail, 
                SUM(CAST(log_data_trx.detail->>'lembar' AS INT)) AS lembar,
                SUM(CAST(log_data_trx.detail->>'total_tagihan' AS INT)) AS total_tagihan,
                SUM(CAST(log_data_trx.detail->>'tagihan' AS INT)) AS tagihan,
                SUM(CAST(log_data_trx.detail->>'admin_bank' AS INT)) AS admin,
                SUM(CAST(log_data_trx.jfee->>'fm1' AS INT)) AS fm1,
                SUM(CAST(log_data_trx.jfee->>'fm2' AS INT)) AS fm2,
                SUM(CAST(log_data_trx.jfee->>'fm3' AS INT)) AS fm3
        FROM log_data_trx
        WHERE $where stat=1 AND log_data_trx.detail->>'response_code'='0000' AND log_data_trx.detail->>'product_detail'!='SALDO'
        GROUP BY log_data_trx.detail->>'product', log_data_trx.detail->>'product_detail'
        ORDER BY product ASC
        LIMIT $num OFFSET $start";

        $result = $this->dbigt->query($sql);
        return $result;
    }


    function getTrxRekapPrdData($prd, $tgl, $year, $month, $day)
    {
        $where = "SUBSTR(CAST(log_data_trx.waktu AS VARCHAR(11)), 0, 11)='$tgl' AND";
        if($year != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND";
        }
        if($month != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND";
        }
        if($day != NULL){
            $where = "extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day AND";
        }

        $sql = "SELECT log_data_trx.detail->>'product' AS product, 
                log_data_trx.detail->>'idpel_name' AS idpel_name,
                log_data_trx.detail->>'product_detail' AS product_detail, 
                log_data_trx.detail->>'lembar' AS lembar,
                log_data_trx.detail->>'amount' AS total_tagihan,
                log_data_trx.detail->>'tagihan' AS tagihan,
                log_data_trx.detail->>'admin_bank' AS admin,
                log_data_trx.jfee->>'fm1' AS fm1,
                log_data_trx.jfee->>'fm2' AS fm2,
                log_data_trx.jfee->>'fm3' AS fm3
        FROM log_data_trx
        WHERE $where log_data_trx.detail->>'product_detail'='$prd' AND stat=1 AND log_data_trx.detail->>'response_code'='0000' AND log_data_trx.detail->>'product_detail'!='SALDO'
        ORDER BY product ASC";

        $result = $this->dbigt->query($sql);
        return $result;
    }
}