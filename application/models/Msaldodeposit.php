<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msaldodeposit extends CI_Model
{

    private $dbigt;

    public function __construct()
    {
        parent::__construct();
        $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getTopupDepoCount($search, $year, $month, $day, $noid)
    {

    	$where = "";
        if($year != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year";
        }
        if($month != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month";
        }
        if($day != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day";
        }

        $like = "";
        if($search != NULL){
            $like = "AND (log_data_trx.noid  LIKE '%".$search."%'
		                    OR  tbl_member_account.nama  LIKE '%".$search."%'
		                    OR  log_data_trx.detail->>'product' LIKE '%".$search."%')";
        }

    	$sql = "SELECT COUNT(log_data_trx.id) AS total
				FROM tbl_member_account
				RIGHT JOIN log_data_trx ON tbl_member_account.noid = log_data_trx.noid
				WHERE log_data_trx.noid = '$noid' AND (log_data_trx.detail->>'product' = 'TIKET DEPOSIT' OR log_data_trx.detail->>'product' = 'TOPUP' OR log_data_trx.detail->>'product' = 'TARIK' OR log_data_trx.detail->>'product' = 'DISTRIBUSI_FEE') AND (CASE WHEN (log_data_trx.detail->>'product') = 'TARIK' THEN log_data_trx.amount < 0 ELSE log_data_trx.amount >= 0 END) AND tbl_member_account.plafon_saldo = 0 $where $like";

        $result = $this->dbigt->query($sql);

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getTopupDepo($start, $num, $search, $year, $month, $day, $noid)
    {
    	$where = "";
        if($year != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year";
        }
        if($month != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month";
        }
        if($day != NULL){
            $where = "AND extract(year from log_data_trx.waktu)=$year AND extract(month from log_data_trx.waktu)=$month AND extract(day from log_data_trx.waktu)=$day";
        }

        $like = "";
        if($search != NULL){
            $like = "AND (log_data_trx.noid  LIKE '%".$search."%'
		                    OR  tbl_member_account.nama  LIKE '%".$search."%'
		                    OR  log_data_trx.detail->>'product' LIKE '%".$search."%')";
        }

    	$sql = "SELECT log_data_trx.id, tbl_member_account.noid, tbl_member_account.nama, log_data_trx.saldo, log_data_trx.waktu, log_data_trx.amount, 			log_data_trx.detail->>'keterangan' AS keterangan, log_data_trx.detail->>'bank' AS bank, log_data_trx.detail->>'product' AS product,
    				log_data_trx.detail->>'idpel_name' AS admin
				FROM tbl_member_account
				RIGHT JOIN log_data_trx ON tbl_member_account.noid = log_data_trx.noid
				WHERE log_data_trx.noid = '$noid' AND (log_data_trx.detail->>'product' = 'TIKET DEPOSIT' OR log_data_trx.detail->>'product' = 'TOPUP' OR log_data_trx.detail->>'product' = 'TARIK' OR log_data_trx.detail->>'product' = 'DISTRIBUSI_FEE') AND (CASE WHEN (log_data_trx.detail->>'product') = 'TARIK' THEN log_data_trx.amount < 0 ELSE log_data_trx.amount >= 0 END) AND tbl_member_account.plafon_saldo = 0 $where $like
				ORDER BY log_data_trx.waktu DESC
                LIMIT $num OFFSET $start";

        $result = $this->dbigt->query($sql);

        return $result;
    }

}