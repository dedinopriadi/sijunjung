<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msatker extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getSatkerCount($search)
    {
        $this->db->select('COUNT(satker_id) AS total');
        $this->db->from('satker');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  satker_nama  LIKE '%".$search."%'
                            OR  satker_email  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataSatker($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('satker');
        if($search != NULL){
            $likeCriteria = "(satker_kd  LIKE '%".$search."%'
                            OR  satker_nama  LIKE '%".$search."%'
                            OR  satker_email  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('satker_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateSatker($id, $data)
    {
        $this->db->where(array('satker_id' => $id));
        $up = $this->db->update('satker', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addSatker($data)
    {
        $this->db->insert('satker', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoSatker($id)
    {
        $this->db->select('*');
        $this->db->from('satker');
        $this->db->where('satker_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteSatker($id)
    {
        $this->db->where('satker_id', $id);
        $del = $this->db->delete('satker');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


}