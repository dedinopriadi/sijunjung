<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mkonsultasi extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getKonsultasiCount($search)
    {
        $this->db->select('COUNT(konsultan_id) AS total');
        $this->db->from('konsultan');
        if($search != NULL){
            $likeCriteria = "(konsultan_nama  LIKE '%".$search."%'
                            OR  konsultan_bagian  LIKE '%".$search."%'
                            OR  konsultan_email  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataKonsultasi($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('konsultan');
        if($search != NULL){
            $likeCriteria = "(konsultan_nama  LIKE '%".$search."%'
                            OR  konsultan_bagian  LIKE '%".$search."%'
                            OR  konsultan_email  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('konsultan_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateKonsultasi($id, $data)
    {
        $this->db->where(array('konsultan_id' => $id));
        $up = $this->db->update('konsultan', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addKonsultasi($data)
    {
        $this->db->insert('konsultan', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoKonsultasi($id)
    {
        $this->db->select('*');
        $this->db->from('konsultan');
        $this->db->where('konsultan_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteKonsultasi($id)
    {
        $this->db->where('konsultan_id', $id);
        $del = $this->db->delete('konsultan');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


}