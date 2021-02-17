<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mspmk extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getSpmkCount($search)
    {
        $this->db->select('COUNT(spm_id) AS total');
        $this->db->from('v_spm');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  spm_no_surat  LIKE '%".$search."%'
                            OR  spm_tgl_terima  LIKE '%".$search."%'
                            OR  spm_perihal  LIKE '%".$search."%'
                            OR  spm_status  LIKE '%".$search."%'
                            OR  spm_no_persetujuan  LIKE '%".$search."%'
                            OR  satker_nama LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataSpmk($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('v_spm');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  spm_no_surat  LIKE '%".$search."%'
                            OR  spm_tgl_terima  LIKE '%".$search."%'
                            OR  spm_perihal  LIKE '%".$search."%'
                            OR  spm_status  LIKE '%".$search."%'
                            OR  spm_no_persetujuan  LIKE '%".$search."%'
                            OR  satker_nama LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('spm_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function addHistory($historySpmk)
    {
        $this->db->insert('history_spm', $historySpmk);

        return $this->db->affected_rows();
    }

    function updateStatus($spmInfo, $spmId)
    {
        $this->db->where('spm_id', $spmId);
        $this->db->update('spm', $spmInfo);
        
        return $this->db->affected_rows();
    }

    function addNewSpmk($spmInfo)
    {
        $this->db->insert('spm', $spmInfo);
        
        return $this->db->affected_rows();
    }

    function saveSpmk($spmInfo, $spmId)
    {
        $this->db->where('spm_id', $spmId);
        $this->db->update('spm', $spmInfo);
        
        return TRUE;
    }


    function getInfoSpmk($id)
    {
        $this->db->select('*');
        $this->db->from('v_spm');
        $this->db->where('spm_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteSpmk($id)
    {
        $this->db->where('spm_id', $id);
        $del = $this->db->delete('spm');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }



}