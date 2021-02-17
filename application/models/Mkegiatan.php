<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mkegiatan extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getKegiatanCount($search, $masjid)
    {
        $this->db->select('COUNT(kegiatan_id) AS total');
        $this->db->from('v_kegiatan');
        $this->db->where('kegiatan_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
            $likeCriteria = "(kegiatan_nama  LIKE '%".$search."%'
                            OR  kegiatan_tgl  LIKE '%".$search."%'
                            OR  name  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataKegiatan($start, $num, $search, $masjid)
    {
        $this->db->select('*');
        $this->db->from('v_kegiatan');
        $this->db->where('kegiatan_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
            $likeCriteria = "(kegiatan_nama  LIKE '%".$search."%'
                            OR  kegiatan_tgl  LIKE '%".$search."%'
                            OR  name  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('kegiatan_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateKegiatan($id, $data)
    {
        $this->db->where(array('kegiatan_id' => $id));
        $up = $this->db->update('m_kegiatan', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function getInfoKegiatan($id)
    {
        $this->db->select('*');
        $this->db->from('m_kegiatan');
        $this->db->where('kegiatan_id', $id);
        $result = $this->db->get();

        return $result->row();
    }


    function addKegiatan($data)
    {
        $this->db->insert('m_kegiatan', $data);
        
        return $this->db->affected_rows();
    }


}