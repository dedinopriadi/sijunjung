<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mprofil extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    function getInfoProfil()
    {
        $this->db->select('*');
        $this->db->from('profil');
        $this->db->where('profil_id', '1');
        $result = $this->db->get();

        return $result->row();
    }


    function getInfoAlamat()
    {
        $this->db->select('*');
        $this->db->from('profil');
        $this->db->where('profil_id', '4');
        $result = $this->db->get();

        return $result->row();
    }


    function getInfoCso()
    {
        $this->db->select('*');
        $this->db->from('profil');
        $this->db->where('profil_id', '5');
        $result = $this->db->get();

        return $result->row();
    }


    function getInfoIntegritas()
    {
        $this->db->select('*');
        $this->db->from('profil');
        $this->db->where('profil_id', '2');
        $result = $this->db->get();

        return $result->row();
    }


    function updateProfil($profilInfo, $profilId)
    {
        $this->db->where('profil_id', $profilId);
        $up = $this->db->update('profil', $profilInfo);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    
}