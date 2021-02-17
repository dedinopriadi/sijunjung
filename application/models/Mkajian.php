<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mkajian extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }

    function getKajianCount($search, $masjid)
    {
        $this->db->select('COUNT(kajian.kajian_id) AS total');
        $this->db->from('m_kajian AS kajian');
        $this->db->join('sys_users AS user', 'kajian.admin_id = user.userId', 'left');
        $this->db->where('kajian.kajian_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('kajian.masjid_id', $masjid);
        }
        if($search != NULL){
        	$likeCriteria = "(kajian.kajian_judul  LIKE '%".$search."%'
                            OR  kajian.kajian_tgl  LIKE '%".$search."%'
                            OR  kajian.kajian_lokasi  LIKE '%".$search."%'
                            OR  kajian.kajian_jam  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataKajian($start, $num, $search, $masjid)
    {
        $this->db->select('kajian.kajian_id, kajian.kajian_judul, kajian.kajian_lokasi, kajian.kajian_tgl, kajian.kajian_jam, user.name');
        $this->db->from('m_kajian AS kajian');
        $this->db->join('sys_users AS user', 'kajian.admin_id = user.userId', 'left');
        $this->db->where('kajian.kajian_data_stat', '1');
        if($masjid != NULL){
            $this->db->where('kajian.masjid_id', $masjid);
        }
        if($search != NULL){
            $likeCriteria = "(kajian.kajian_judul  LIKE '%".$search."%'
                            OR  kajian.kajian_tgl  LIKE '%".$search."%'
                            OR  kajian.kajian_lokasi  LIKE '%".$search."%'
                            OR  kajian.kajian_jam  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('kajian.kajian_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateKajian($id, $data)
    {
        $this->db->where(array('kajian_id' => $id));
        $up = $this->db->update('m_kajian', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addKajian($data)
    {
        $this->db->insert('m_kajian', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoKajian($id)
    {
        $this->db->select('*');
        $this->db->from('m_kajian');
        $this->db->where('kajian_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

}