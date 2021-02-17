<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mjamaah extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getJamaahCount($search, $masjid)
    {
        $this->db->select('COUNT(jamaah_nik) AS total');
        $this->db->from('v_jamaah');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
        	$likeCriteria = "(jamaah_nik  LIKE '%".$search."%'
                            OR  jamaah_nama  LIKE '%".$search."%'
                            OR  jamaah_hp  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('jamaah_data_stat', '1');
        //$this->db->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataJamaah($start, $num, $search, $masjid)
    {
        $this->db->select('*');
        $this->db->from('v_jamaah');
        if($masjid != NULL){
            $this->db->where('masjid_id', $masjid);
        }
        if($search != NULL){
        	$likeCriteria = "(jamaah_nik  LIKE '%".$search."%'
                            OR  jamaah_nama  LIKE '%".$search."%'
                            OR  jamaah_hp  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->where('jamaah_data_stat', '1');
        $this->db->order_by('jamaah_nama', 'ASC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }

    function getInfoJamaah($nik)
    {
        $this->db->select('*');
        $this->db->from('m_jamaah');
        $this->db->where('jamaah_nik', $nik);
        $result = $this->db->get();

        return $result->row();
    }

    function updateJamaah($nik, $data)
    {
        $this->db->where(array('jamaah_nik' => $nik));
        $up = $this->db->update('m_jamaah', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}