<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mdashboard extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	
	function getDataPengaduan()
    {
        $this->db->select('*');
        $this->db->from('pengaduan');
        $this->db->where('pengaduan_view', '0');
        $this->db->order_by('pengaduan_id', 'DESC');
        $this->db->limit('5', '0');

        $result = $this->db->get();
        return $result;
    }


    function getDataFeedback()
    {
    	$sql = "CALL getFeedback";

        $result = $result = $this->db->query($sql);
        return $result;
    }


    function getLastFeedback()
    {
    	$sql = "CALL getLastFeedback";

        $result = $result = $this->db->query($sql);
        return $result;
    }


}