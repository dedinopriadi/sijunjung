<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mkonfirmasi extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getKonfirmasiCount($search)
    {
        $this->db->select('COUNT(konfir_id) AS total');
        $this->db->from('v_konfirmasi');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  konfir_no_surat  LIKE '%".$search."%'
                            OR  konfir_tgl_terima  LIKE '%".$search."%'
                            OR  konfir_perihal  LIKE '%".$search."%'
                            OR  konfir_status  LIKE '%".$search."%'
                            OR  konfir_no_persetujuan  LIKE '%".$search."%'
                            OR  satker_nama LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataKonfirmasi($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('v_konfirmasi');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  konfir_no_surat  LIKE '%".$search."%'
                            OR  konfir_tgl_terima  LIKE '%".$search."%'
                            OR  konfir_perihal  LIKE '%".$search."%'
                            OR  konfir_status  LIKE '%".$search."%'
                            OR  konfir_no_persetujuan  LIKE '%".$search."%'
                            OR  satker_nama LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('konfir_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function addHistory($historySpmk)
    {
        $this->db->insert('history_konfirmasi', $historySpmk);

        return $this->db->affected_rows();
    }

    function updateStatus($spmInfo, $spmId)
    {
        $this->db->where('konfir_id', $spmId);
        $this->db->update('konfirmasi', $spmInfo);
        
        return $this->db->affected_rows();
    }

    function addNewKonfirmasi($spmInfo)
    {
        $this->db->insert('konfirmasi', $spmInfo);
        
        return $this->db->affected_rows();
    }

    function saveKonfirmasi($spmInfo, $spmId)
    {
        $this->db->where('konfir_id', $spmId);
        $this->db->update('konfirmasi', $spmInfo);
        
        return TRUE;
    }


    function getInfoKonfirmasi($id)
    {
        $this->db->select('*');
        $this->db->from('v_konfirmasi');
        $this->db->where('konfir_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteKonfirmasi($id)
    {
        $this->db->where('konfir_id', $id);
        $del = $this->db->delete('konfirmasi');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function getToken($id)
    {
        $sql = "CALL getTokenKonfir(".$id.")";

        $result = $result = $this->db->query($sql);
        return $result;
    }


}