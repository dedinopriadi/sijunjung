<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mskpp extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getSkppCount($search)
    {
        $this->db->select('COUNT(skpp_id) AS total');
        $this->db->from('v_skpp');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  skpp_no_surat  LIKE '%".$search."%'
                            OR  skpp_tgl_terima  LIKE '%".$search."%'
                            OR  skpp_perihal  LIKE '%".$search."%'
                            OR  skpp_status  LIKE '%".$search."%'
                            OR  satker_nama LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataSkpp($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('v_skpp');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  skpp_no_surat  LIKE '%".$search."%'
                            OR  skpp_tgl_terima  LIKE '%".$search."%'
                            OR  skpp_perihal  LIKE '%".$search."%'
                            OR  skpp_status  LIKE '%".$search."%'
                            OR  satker_nama LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('skpp_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function addHistory($historySkpp)
    {
        $this->db->insert('history_skpp', $historySkpp);

        return $this->db->affected_rows();
    }

    function updateStatus($skppInfo, $skppId)
    {
        $this->db->where('skpp_id', $skppId);
        $this->db->update('skpp', $skppInfo);
        
        return $this->db->affected_rows();
    }

    function addNewSkpp($skppInfo)
    {
        $this->db->insert('skpp', $skppInfo);
        
        return $this->db->affected_rows();
    }

    function saveSkpp($skppInfo, $skppId)
    {
        $this->db->where('skpp_id', $skppId);
        $this->db->update('skpp', $skppInfo);
        
        return TRUE;
    }


    function getInfoSkpp($id)
    {
        $this->db->select('*');
        $this->db->from('v_skpp');
        $this->db->where('skpp_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteSkpp($id)
    {
        $this->db->where('skpp_id', $id);
        $del = $this->db->delete('skpp');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    public function getSatker()
    {
        $this->db->select('*');
        $this->db->from('satker');
        $this->db->order_by('satker_nama', 'ASC');
        return $this->db->get();
    }


    function getToken($id)
    {
        $sql = "CALL getTokenSkpp(".$id.")";

        $result = $result = $this->db->query($sql);
        return $result;
    }


}