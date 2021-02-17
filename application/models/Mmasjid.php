<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mmasjid extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        // $this->dbigt = $this->load->database('database_igt', TRUE);
    }


    function getMasjidCount($search)
    {
        $this->db->select('COUNT(masjid.masjid_id) AS total');
        $this->db->from('m_masjid AS masjid');
        $this->db->join('sys_users AS user', 'masjid.masjid_user_create = user.userId', 'left');
        $this->db->where('masjid.masjid_data_stat', '1');
        if($search != NULL){
        	$likeCriteria = "(masjid.masjid_nama  LIKE '%".$search."%'
                            OR  masjid.masjid_alamat  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataMasjid($start, $num, $search)
    {
        $this->db->select('masjid.masjid_id, masjid.masjid_nama, masjid.masjid_alamat, masjid.masjid_lat, masjid.masjid_long, user.name');
        $this->db->from('m_masjid AS masjid');
        $this->db->join('sys_users AS user', 'masjid.masjid_user_create = user.userId', 'left');
        $this->db->where('masjid.masjid_data_stat', '1');
        if($search != NULL){
            $likeCriteria = "(masjid.masjid_nama  LIKE '%".$search."%'
                            OR  masjid.masjid_alamat  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('masjid.masjid_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateMasjid($id, $data)
    {
        $this->db->where(array('masjid_id' => $id));
        $up = $this->db->update('m_masjid', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function addMasjid($data)
    {
        $this->db->insert('m_masjid', $data);
        
        return $this->db->affected_rows();
    }


    function getInfoMasjid($id)
    {
        $this->db->select('*');
        $this->db->from('m_masjid');
        $this->db->where('masjid_id', $id);
        $result = $this->db->get();

        return $result->row();
    }

}