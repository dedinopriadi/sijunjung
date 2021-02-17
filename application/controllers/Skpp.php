<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Skpp (SkppController)
 * Skpp Class to control all Skpp related operations.
 * @author : Dedi Nopriadi
 */
class Skpp extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mskpp');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : SKPP';

        $this->loadViews("skpp/skpp", $this->global, NULL, NULL);
    }


    public function skppListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mskpp->getSkppCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $skpps = $this->Mskpp->getDataSkpp($start, $num, strtoupper($search));

        foreach($skpps->result_array() as $row){
            $skpp[] = ['id' => $row['skpp_id'], 'kode' => $row['satker_kd'], 'no_surat' => $row['skpp_no_surat'], 'perihal' => $row['skpp_perihal'], 'tgl' => $row['skpp_tgl_terima'], 'status' => $row['skpp_status'], 'ket' => $row['skpp_keterangan'], 'alasan' => $row['skpp_alasan'], 'satker' => $row['satker_nama'], 'email' => $row['satker_email'], 'no_pengantar' => $row['skpp_no_pengantar'], 'supplier' => $row['skpp_supplier']];
        }

        $response = [];
            if (!empty($skpp)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['skpps'] = $skpp;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data SKPP Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoSkpp()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mskpp->getInfoSkpp($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data SKPP"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

     function statusSkpp()
    {

        date_default_timezone_set('Asia/Jakarta');
        $waktu      = date('Y-m-d H:i:s');

        $postdata = json_decode(file_get_contents('php://input'), TRUE);

        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $status   = (isset($postdata['status']) ? $postdata['status'] : NULL);

        if(empty($id)) {
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        } else if (empty($status)) {
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi Status Kosong"]]));
        } else {
            if($status == 2) {
                $historySkpp = array('skpp_id'=>$id, 'history_status'=>'Diproses', 'history_time'=>$waktu);
                $skppInfo = array('skpp_status'=>'Diproses');

                $result = $this->Mskpp->updateStatus($skppInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Diproses"]]));
                    $this->Mskpp->addHistory($historySkpp);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Diproses. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Diproses. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Status SKPP Gagal diproses"]]));
                }
            } else if($status == 3) {
                $keterangan = (isset($postdata['datax']['no_pengantar']) ? $postdata['datax']['no_pengantar'] : NULL);

                $historySkpp = array('skpp_id'=>$id, 'history_status'=>'Selesai', 'history_time'=>$waktu);
                $skppInfo = array('skpp_status'=>'Selesai', 'skpp_no_pengantar'=>$keterangan);

                $result = $this->Mskpp->updateStatus($skppInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Selesai"]]));
                    $this->Mskpp->addHistory($historySkpp);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Selesai. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Selesai. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah status SKPP"]]));
                }
            } else if($status == 4) {
                $alasan = (isset($postdata['datax']['alasan']) ? $postdata['datax']['alasan'] : NULL);

                $historySkpp = array('skpp_id'=>$id, 'history_status'=>'Ditolak', 'history_time'=>$waktu);
                $skppInfo = array('skpp_status'=>'Ditolak', 'skpp_alasan'=>$alasan);

                $result = $this->Mskpp->updateStatus($skppInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Ditolak"]]));
                    $this->Mskpp->addHistory($historySkpp);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Ditolak. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Ditolak. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah status SKPP"]]));
                }
            }


        }

    }


    public function addSkpp()
    {
        date_default_timezone_set('Asia/Jakarta');
        $waktu      = date('Y-m-d H:i:s');
        $tgl_terima = date('Y-m-d');
        $id         = date('YmdHis');

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $no       = (isset($postdata['datax']['no_surat']) ? $postdata['datax']['no_surat'] : NULL);
        $satker   = (isset($postdata['datax']['satker']) ? $postdata['datax']['satker'] : NULL);
        $perihal  = (isset($postdata['datax']['perihal']) ? $postdata['datax']['perihal'] : NULL);
        $ket      = (isset($postdata['datax']['keterangan']) ? $postdata['datax']['keterangan'] : NULL);

        if(empty($no) || empty($satker) || empty($perihal)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Kolom Isian Wajib Masih Kosong"]]));
        }else{
            $skppInfo = array('skpp_id'=>$id, 'satker_kd'=>$satker, 'skpp_no_surat'=>$no, 'skpp_perihal'=>$perihal, 'skpp_tgl_terima'=>$tgl_terima, 'skpp_keterangan'=>$ket);

            $historySkpp = array('skpp_id'=>$id, 'history_status'=>'Diterima', 'history_time'=>$waktu);

            $result = $this->Mskpp->addNewSkpp($skppInfo);
            
            if($result > 0)
            {
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP Berhasil Ditambahkan"]]));
                $this->Mskpp->addHistory($historySkpp);
            }
            else
            {
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan data SKPP"]]));
            }

        }
    }


    public function updateSkpp()
    {
        date_default_timezone_set('Asia/Jakarta');
        $waktu      = date('Y-m-d H:i:s');
        $tgl_terima = date('Y-m-d');

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $no       = (isset($postdata['datax']['skpp_no_surat']) ? $postdata['datax']['skpp_no_surat'] : NULL);
        $satker   = (isset($postdata['datax']['satker_kd']) ? $postdata['datax']['satker_kd'] : NULL);
        $perihal  = (isset($postdata['datax']['skpp_perihal']) ? $postdata['datax']['skpp_perihal'] : NULL);
        $ket      = (isset($postdata['datax']['skpp_keterangan']) ? $postdata['datax']['skpp_keterangan'] : NULL);
        $status   = (isset($postdata['datax']['skpp_status']) ? $postdata['datax']['skpp_status'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $skppInfo = array('skpp_no_surat'=>$no, 'skpp_perihal'=>$perihal, 'skpp_status'=>$status, 'skpp_keterangan'=>$ket);
            $historySkpp = array('skpp_id'=>$id, 'history_status'=>$status, 'history_time'=>$waktu);
            
            $result = $this->Mskpp->saveSkpp($skppInfo, $id);

            if($result == true)
            {
                $this->Mskpp->addHistory($historySkpp);
                $mail = $this->sendVerification($id);
                if($mail = TRUE) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP berhasil diubah. Email Notifikasi terkirim ke Satker"]]));
                } else {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SKPP berhasil diubah. Email Notifikasi tidak terkirim ke Satker"]]));
                }
                
                
                // if($status == "Selesai")
                // {
                    
                // }
            }
            else
            {
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah data"]]));
            }
   
        }
    }

    public function deleteSkpp()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mskpp->deleteSkpp($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data SKPP Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data SKPP"]]));
            }

        }
    }


    public function updateSupplier()
    {

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $status   = (isset($postdata['datax']['status']) ? $postdata['datax']['status'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $skppInfo = array('skpp_supplier'=>$status);
            
            $result = $this->Mskpp->saveSkpp($skppInfo, $id);

            if($result == true)
            {
                
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Status Supplier ".$status]]));
                
            }
            else
            {
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah data"]]));
            }
   
        }
    }


    public function getSatker()
    {
        $roles = $this->Mskpp->getSatker()->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($roles));
    }


    function sendVerification($skppId){
        $skppInfo = $this->Mskpp->getInfoSkpp($skppId);

        if(!empty($skppInfo)){
            $data1["satker"] = $skppInfo->satker_nama;
            $data1["surat"] = $skppInfo->skpp_no_surat;
            $data1["email"] = $skppInfo->satker_email;
            $data1["subject"] = "SKPP ".$skppInfo->skpp_status;
            $data1["open_message"] = " SKPP dengan No. Surat ";
            $data1["message"] = " telah ".$skppInfo->skpp_status;

            $sendStatus = verificationMail($data1);

            if($sendStatus){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }


    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'KPPN Sijunjung : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

}