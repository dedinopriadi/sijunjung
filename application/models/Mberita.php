<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mberita extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }



    function getBeritaCount($search)
    {
        $this->db->select('COUNT(berita_id) AS total');
        $this->db->from('v_berita');
        if($search != NULL){
            $likeCriteria = "(berita_judul  LIKE '%".$search."%'
                            OR  name  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataBerita($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('v_berita');
        if($search != NULL){
            $likeCriteria = "(berita_judul  LIKE '%".$search."%'
                            OR  name  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('berita_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function getInfoBerita($id)
    {
        $this->db->select('*');
        $this->db->from('berita');
        $this->db->where('berita_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteBerita($id)
    {
        $this->db->where('berita_id', $id);
        $del = $this->db->delete('berita');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function addNewBerita($data)
    {
        $this->db->insert('berita', $data);
        
        return $this->db->affected_rows();
    }


    function saveBerita($data, $id)
    {
        $this->db->where('berita_id', $id);
        $this->db->update('berita', $data);
        
        return TRUE;
    }


}