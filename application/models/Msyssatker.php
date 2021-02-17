<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Msyssatker extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        /////////////////////////////
    }

    function getSyssatkerCount($search)
    {
        $this->db->select('COUNT(syssatker_id) AS total');
        $this->db->from('v_syssatker');
        $this->db->where('syssatker_data_stat', '1');
        if($search != NULL){
        	$likeCriteria = "(syssatker_nama  LIKE '%".$search."%'
                            OR  syssatker_email  LIKE '%".$search."%'
                            OR  name  LIKE '%".$search."%'
                            OR  satker_nama  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataSyssatker($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('v_syssatker');
        $this->db->where('syssatker_data_stat', '1');
        if($search != NULL){
            $likeCriteria = "(syssatker_nama  LIKE '%".$search."%'
                            OR  syssatker_email  LIKE '%".$search."%'
                            OR  name  LIKE '%".$search."%'
                            OR  satker_nama  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('syssatker_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateSyssatker($id, $data)
    {
        $this->db->where(array('syssatker_id' => $id));
        $up = $this->db->update('sys_satker', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function addSyssatker($data)
    {
        $this->db->insert('sys_satker', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoSyssatker($id)
    {
        $this->db->select('*');
        $this->db->from('sys_satker');
        $this->db->where('syssatker_id', $id);
        $result = $this->db->get();

        return $result->row();
    }
    

}