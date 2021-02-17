<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model_igt extends CI_Model
{

    private $dbigt;

    public function __construct()
    {
        parent::__construct();
        $this->dbigt = $this->load->database('database_igt', TRUE);
    }
    
    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($alias, $password)
    {
        $this->dbigt->select('BaseTbl.id, BaseTbl.noid, BaseTbl.alias, BaseTbl.passw, BaseTbl.nama, BaseTbl.email, BaseTbl.ip');
        $this->dbigt->from('tbl_member_channel as BaseTbl');
        $this->dbigt->where('BaseTbl.alias', $alias);
        $this->dbigt->where('BaseTbl.status', 1);
        $query = $this->dbigt->get();
        
        $user = $query->row();
        
        if(!empty($user)){
            if($password == $user->passw){
                return $user;
            } else {
                $this->session->set_flashdata('error', 'Password yang dimasukan salah');
                return array();
            }
        } else {
            $this->session->set_flashdata('error', 'Email yang dimasukan salah');
            return array();
        }
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($alias)
    {
        $this->dbigt->select('id');
        $this->dbigt->where('alias', $alias);
        $this->dbigt->where('status', 0);
        $query = $this->dbigt->get('tbl_member_channel');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
        $result = $this->db->insert('tbl_reset_password', $data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $this->db->select('userId, email, name');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('email', $email);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // This function used to create new password by reset link
    function createPasswordUser($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', array('password'=>getHashedPassword($password)));
        $this->db->delete('tbl_reset_password', array('email'=>$email));
    }

    /**
     * This function used to save login information of user
     * @param array $loginInfo : This is users login information
     */
    function lastLogin($Id, $loginInfo)
    {
        $this->dbigt->trans_start();
        $this->dbigt->where('id', $Id);
        $this->dbigt->update('tbl_member_channel', $loginInfo);
        $this->dbigt->trans_complete();
    }

    /**
     * This function is used to get last login info by user id
     * @param number $userId : This is user id
     * @return number $result : This is query result
     */
    function lastLoginInfo($userId)
    {
        $this->db->select('BaseTbl.createdDtm');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('tbl_last_login as BaseTbl');

        return $query->row();
    }
}

?>