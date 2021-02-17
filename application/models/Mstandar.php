<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mstandar extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }



    function getStandarCount($search)
    {
        $this->db->select('COUNT(standar_id) AS total');
        $this->db->from('standar_pelayanan');
        if($search != NULL){
            $likeCriteria = "(standar_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataStandar($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('standar_pelayanan');
        if($search != NULL){
            $likeCriteria = "(standar_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('standar_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function getInfoStandar($id)
    {
        $this->db->select('*');
        $this->db->from('standar_pelayanan');
        $this->db->where('standar_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteStandar($id)
    {
        $this->db->where('standar_id', $id);
        $del = $this->db->delete('standar_pelayanan');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function updateStandar($id, $data)
    {
        $this->db->where(array('standar_id' => $id));
        $up = $this->db->update('standar_pelayanan', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addStandar($data)
    {
        $this->db->insert('standar_pelayanan', $data);
        
        return $this->db->affected_rows();
    }


}