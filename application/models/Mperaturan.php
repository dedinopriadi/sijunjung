<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mperaturan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }



    function getPeraturanCount($search)
    {
        $this->db->select('COUNT(peraturan_id) AS total');
        $this->db->from('peraturan');
        if($search != NULL){
            $likeCriteria = "(peraturan_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataPeraturan($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('peraturan');
        if($search != NULL){
            $likeCriteria = "(peraturan_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('peraturan_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function getInfoPeraturan($id)
    {
        $this->db->select('*');
        $this->db->from('peraturan');
        $this->db->where('peraturan_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deletePeraturan($id)
    {
        $this->db->where('peraturan_id', $id);
        $del = $this->db->delete('peraturan');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function addNewPeraturan($peraturanInfo)
    {
        $this->db->insert('peraturan', $peraturanInfo);
        
        return $this->db->affected_rows();
    }


    function savePeraturan($peraturanInfo, $peraturanId)
    {
        $this->db->where('peraturan_id', $peraturanId);
        $this->db->update('peraturan', $peraturanInfo);
        
        return TRUE;
    }


}