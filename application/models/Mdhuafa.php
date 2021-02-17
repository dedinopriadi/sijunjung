<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdhuafa extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getDhuafaCount($search, $masjid)
    {
        $this->db->select('COUNT(dhuafa_id) AS total');
        $this->db->from('m_dhuafa');
        $this->db->where('dhuafa_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
        	$likeCriteria = "(dhuafa_nik  LIKE '%".$search."%'
                            OR  dhuafa_nama  LIKE '%".$search."%'
                            OR  dhuafa_alamat  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataDhuafa($start, $num, $search, $masjid)
    {
        $this->db->select('*');
        $this->db->from('m_dhuafa');
        $this->db->where('dhuafa_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
            $likeCriteria = "(dhuafa_nik  LIKE '%".$search."%'
                            OR  dhuafa_nama  LIKE '%".$search."%'
                            OR  dhuafa_alamat  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('dhuafa_nama', 'ASC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateDhuafa($id, $data)
    {
        $this->db->where(array('dhuafa_id' => $id));
        $up = $this->db->update('m_dhuafa', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addDhuafa($data)
    {
        $this->db->insert('m_dhuafa', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoDhuafa($id)
    {
        $this->db->select('*');
        $this->db->from('m_dhuafa');
        $this->db->where('dhuafa_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

}