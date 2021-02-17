<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mformat extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }



    function getFormatCount($search)
    {
        $this->db->select('COUNT(format_id) AS total');
        $this->db->from('format');
        if($search != NULL){
            $likeCriteria = "(format_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataFormat($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('format');
        if($search != NULL){
            $likeCriteria = "(format_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('format_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function getInfoFormat($id)
    {
        $this->db->select('*');
        $this->db->from('format');
        $this->db->where('format_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteFormat($id)
    {
        $this->db->where('format_id', $id);
        $del = $this->db->delete('format');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function addNewFormat($formatInfo)
    {
        $this->db->insert('format', $formatInfo);
        
        return $this->db->affected_rows();
    }


    function saveFormat($formatInfo, $formatId)
    {
        $this->db->where('format_id', $formatId);
        $this->db->update('format', $formatInfo);
        
        return TRUE;
    }


}