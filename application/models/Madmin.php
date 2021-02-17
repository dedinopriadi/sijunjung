<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Madmin extends CI_Model
{

    // private $dbigt;

    public function __construct()
    {
        parent::__construct();
        /////////////////////////////
    }

    function getAdminCount($search)
    {
        $this->db->select('COUNT(userId) AS total');
        $this->db->from('v_admin');
        $this->db->where('isDeleted', '0');
        if($search != NULL){
        	$likeCriteria = "(email  LIKE '%".$search."%'
                            OR  name  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $result = $this->db->get();

        foreach($result->result_array() as $row){
            $total = $row['total'];
        }
        return $total;
    }

    function getDataAdmin($start, $num, $search)
    {
        $this->db->select('v.*, u.name AS created_name');
        $this->db->from('v_admin AS v');
        $this->db->join('sys_users AS u', 'v.createdBy = u.userId', 'left');
        $this->db->where('v.isDeleted', '0');
        if($search != NULL){
            $likeCriteria = "(v.email  LIKE '%".$search."%'
                            OR  v.name  LIKE '%".$search."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('v.userId', 'DESC');
        $this->db->limit($num, $start);

        $result = $this->db->get();
        return $result;
    }


    function updateAdmin($id, $data)
    {
        $this->db->where(array('userId' => $id));
        $up = $this->db->update('sys_users', $data);
        
        if($up){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function addAdmin($data)
    {
        $this->db->insert('sys_users', $data);
        
        return $this->db->affected_rows();
    }


    public function getRoles()
	{
		$this->db->select('*');
        $this->db->from('sys_roles');
        $this->db->where("roleId > 1");
		$this->db->order_by('roleId', 'ASC');
		return $this->db->get();
    }


    function getInfoAdmin($id)
    {
        $this->db->select('*');
        $this->db->from('sys_users');
        $this->db->where('userId', $id);
        $result = $this->db->get();

        return $result->row();
    }
    

}