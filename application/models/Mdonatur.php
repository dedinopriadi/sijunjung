<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdonatur extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getDonaturCount($search, $masjid)
    {
        $this->db->select('COUNT(donatur_id) AS total');
        $this->db->from('m_donatur');
        $this->db->where('donatur_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
        	$likeCriteria = "(donatur_nik  LIKE '%".$search."%'
                            OR  donatur_nama  LIKE '%".$search."%'
                            OR  donatur_alamat  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataDonatur($start, $num, $search, $masjid)
    {
        $this->db->select('*');
        $this->db->from('m_donatur');
        $this->db->where('donatur_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
            $likeCriteria = "(donatur_nik  LIKE '%".$search."%'
                            OR  donatur_nama  LIKE '%".$search."%'
                            OR  donatur_alamat  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('donatur_nama', 'ASC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateDonatur($id, $data)
    {
        $this->db->where(array('donatur_id' => $id));
        $up = $this->db->update('m_donatur', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addDonatur($data)
    {
        $this->db->insert('m_donatur', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoDonatur($id)
    {
        $this->db->select('*');
        $this->db->from('m_donatur');
        $this->db->where('donatur_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

}