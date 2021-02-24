<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mjadwal extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getJadwalCount($search)
    {
        $this->db->select('COUNT(jadwal_id) AS total');
        $this->db->from('jadwal');
        if($search != NULL){
            $likeCriteria = "(jadwal_materi  LIKE '%".$search."%'
                            OR  jadwal_tgl  LIKE '%".$search."%'
                            OR  jadwal_waktu  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataJadwal($start, $num, $search)
    {
        $this->db->select('*');
        $this->db->from('jadwal');
        if($search != NULL){
            $likeCriteria = "(jadwal_materi  LIKE '%".$search."%'
                            OR  jadwal_tgl  LIKE '%".$search."%'
                            OR  jadwal_waktu  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('jadwal_tgl', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateJadwal($id, $data)
    {
        $this->db->where(array('jadwal_id' => $id));
        $up = $this->db->update('jadwal', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addJadwal($data)
    {
        $this->db->insert('jadwal', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoJadwal($id)
    {
        $this->db->select('*');
        $this->db->from('jadwal');
        $this->db->where('jadwal_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

    function deleteJadwal($id)
    {
        $this->db->where('jadwal_id', $id);
        $del = $this->db->delete('jadwal');
        
        if($del){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function getPeserta($id)
    {
        $sql = "CALL getPeserta(".$id.")";

        $result = $result = $this->db->query($sql);
        return $result;
    }


}