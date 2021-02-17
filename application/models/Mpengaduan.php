<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mpengaduan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        /////////////////////////////
    }


    function getPengaduanCount($search, $con)
    {
        $this->db->select('COUNT(pengaduan_id) AS total');
        $this->db->from('pengaduan');
        if($con != ""){
        	$this->db->where('pengaduan_view', $con);
        }
        if($search != NULL){
            $likeCriteria = "(pengaduan_nama  LIKE '%".$search."%'
                            OR  pengaduan_email  LIKE '%".$search."%'
                            OR  pengaduan_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataPengaduan($start, $num, $search, $con)
    {
        $this->db->select('*');
        $this->db->from('pengaduan');
        if($con != ""){
        	$this->db->where('pengaduan_view', $con);
        }
        if($search != NULL){
            $likeCriteria = "(pengaduan_nama  LIKE '%".$search."%'
                            OR  pengaduan_email  LIKE '%".$search."%'
                            OR  pengaduan_judul  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        //$this->dbigt->where("(tipe = 'M1' OR tipe = 'M2' OR tipe = 'M3')");
        $this->db->order_by('pengaduan_id', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function getInfoPengaduan($id)
    {
        $this->db->select('*');
        $this->db->from('pengaduan');
        $this->db->where('pengaduan_id', $id);
        $result = $this->db->get();

        return $result->row();
    }


    function updatePengaduan($id, $data)
    {
        $this->db->where(array('pengaduan_id' => $id));
        $up = $this->db->update('pengaduan', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }


}