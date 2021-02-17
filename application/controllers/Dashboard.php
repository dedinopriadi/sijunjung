<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Dashboard (DashboardController)
 * Dashboard Class to control all Dashboard related operations.
 * @author : Dedi Nopriadi
 */
class Dashboard extends BaseController
{

	/**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mdashboard');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    public function dashboardPengaduan()
    {

        $pengaduans = $this->Mdashboard->getDataPengaduan();

        foreach($pengaduans->result_array() as $row){
            $pengaduan[] = ['id' => $row['pengaduan_id'], 'nama' => $row['pengaduan_nama'], 'email' => $row['pengaduan_email'], 'judul' => $row['pengaduan_judul'], 'view' => $row['pengaduan_view'], 'waktu' => $row['pengaduan_waktu']];
        }

        $response = [];
            if (!empty($pengaduan)) {  
                $response['pengad'] = $pengaduan;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Pengaduan Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));

    }


    public function dashboardFeedback()
    {

        $feedbacks = $this->Mdashboard->getDataFeedback();

        foreach($feedbacks->result_array() as $row){
            $feedback[] = ['feedback' => $row['feedback'], 'jumlah' => $row['jumlah']];
        }

        $response = [];
            if (!empty($feedback)) {  
                $response['feedb'] = $feedback;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Pengaduan Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));

    }


    public function dashboardLastFeedback()
    {

        $feedbacks = $this->Mdashboard->getLastFeedback();

        foreach($feedbacks->result_array() as $row){
            $feedback[] = ['nama' => $row['nama'], 'satker' => $row['satker'], 'feedback' => $row['feedback']];
        }

        $response = [];
            if (!empty($feedback)) {  
                $response['lastfeed'] = $feedback;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Pengaduan Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));

    }

}