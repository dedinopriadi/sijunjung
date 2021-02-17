<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mlink extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getInfoLink($masjid)
    {
        $this->db->select('*');
        $this->db->from('m_link');
        $this->db->where('masjid_id', $masjid);
        $result = $this->db->get();

        return $result->row();
    }

    function generateLink($masjid)
    {
    	date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');

    	$data   = array('link_alamat' => "https://www.youtube.com/user/alidrisiyyah", 'masjid_id' => $masjid, 'link_last_update' => $date);
        $this->db->insert('m_link', $data);
        
        return $this->db->affected_rows();
    }


    function updateLink($masjid, $data)
    {
        $this->db->where(array('masjid_id' => $masjid));
        $up = $this->db->update('m_link', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}