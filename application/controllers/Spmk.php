<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Spmk (SpmkController)
 * Spmk Class to control all Spmk related operations.
 * @author : Dedi Nopriadi
 */
class Spmk extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mspmk');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : SPM Koreksi';

        $this->loadViews("spmk/spmk", $this->global, NULL, NULL);
    }


    public function spmkListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mspmk->getSpmkCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $spmks = $this->Mspmk->getDataSpmk($start, $num, strtoupper($search));

        foreach($spmks->result_array() as $row){
            $spmk[] = ['id' => $row['spm_id'], 'kode' => $row['satker_kd'], 'no_surat' => $row['spm_no_surat'], 'perihal' => $row['spm_perihal'], 'tgl' => $row['spm_tgl_terima'], 'status' => $row['spm_status'], 'ket' => $row['spm_keterangan'], 'alasan' => $row['spm_alasan'], 'satker' => $row['satker_nama'], 'email' => $row['satker_email'], 'no_persetujuan' => $row['spm_no_persetujuan']];
        }

        $response = [];
            if (!empty($spmk)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['spmks'] = $spmk;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data SPM Koreksi Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoSpmk()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mspmk->getInfoSpmk($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data SPM Koreksi"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

     function statusSpmk()
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
                $historySpm = array('spm_id'=>$id, 'history_status'=>'Diproses', 'history_time'=>$waktu);
                $spmInfo = array('spm_status'=>'Diproses');

                $result = $this->Mspmk->updateStatus($spmInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Diproses"]]));
                    $this->Mspmk->addHistory($historySpm);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SMP Koreksi Diproses. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Diproses. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Status SPM Koreksi Gagal diproses"]]));
                }
            } else if($status == 3) {
                $persetujuan = (isset($postdata['datax']['no_persetujuan']) ? $postdata['datax']['no_persetujuan'] : NULL);

                $historySpm = array('spm_id'=>$id, 'history_status'=>'Selesai', 'history_time'=>$waktu);
                $spmInfo = array('spm_status'=>'Selesai', 'spm_no_persetujuan'=>$persetujuan);

                $result = $this->Mspmk->updateStatus($spmInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Selesai"]]));
                    $this->Mspmk->addHistory($historySpm);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Selesai. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Selesai. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah status SPM Koreksi"]]));
                }
            } else if($status == 4) {
                $alasan = (isset($postdata['datax']['alasan']) ? $postdata['datax']['alasan'] : NULL);

                $historySpm = array('spm_id'=>$id, 'history_status'=>'Ditolak', 'history_time'=>$waktu);
                $spmInfo = array('spm_status'=>'Ditolak', 'spm_alasan'=>$alasan);

                $result = $this->Mspmk->updateStatus($spmInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Ditolak"]]));
                    $this->Mspmk->addHistory($historySpm);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah status SPM Koreksi"]]));
                }
            }


        }

    }


    public function addSpmk()
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
            $spmInfo = array('spm_id'=>$id, 'satker_kd'=>$satker, 'spm_no_surat'=>$no, 'spm_perihal'=>$perihal, 'spm_tgl_terima'=>$tgl_terima, 'spm_keterangan'=>$ket);

            $historySpm = array('spm_id'=>$id, 'history_status'=>'Diterima', 'history_time'=>$waktu);

            $result = $this->Mspmk->addNewSpmk($spmInfo);
            
            if($result > 0)
            {
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Berhasil Ditambahkan"]]));
                $this->Mspmk->addHistory($historySpm);
            }
            else
            {
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan data SPM Koreksi"]]));
            }

        }
    }


    public function updateSpmk()
    {
        date_default_timezone_set('Asia/Jakarta');
        $waktu      = date('Y-m-d H:i:s');
        $tgl_terima = date('Y-m-d');

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $no       = (isset($postdata['datax']['spm_no_surat']) ? $postdata['datax']['spm_no_surat'] : NULL);
        $satker   = (isset($postdata['datax']['satker_kd']) ? $postdata['datax']['satker_kd'] : NULL);
        $perihal  = (isset($postdata['datax']['spm_perihal']) ? $postdata['datax']['spm_perihal'] : NULL);
        $ket      = (isset($postdata['datax']['spm_keterangan']) ? $postdata['datax']['spm_keterangan'] : NULL);
        $status   = (isset($postdata['datax']['spm_status']) ? $postdata['datax']['spm_status'] : NULL);
        $persetujuan = (isset($postdata['datax']['spm_no_persetujuan']) ? $postdata['datax']['spm_no_persetujuan'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $spmInfo = array('spm_no_surat'=>$no, 'spm_perihal'=>$perihal, 'spm_status'=>$status, 'spm_keterangan'=>$ket, 'spm_no_persetujuan'=>$persetujuan);
            $historySpm = array('spm_id'=>$id, 'history_status'=>$status, 'history_time'=>$waktu);
            
            $result = $this->Mspmk->saveSpmk($spmInfo, $id);

            if($result == true)
            {
                // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPK Koreksi berhasil diubah"]]));
                $this->Mspmk->addHistory($historySpm);
                $mail = $this->sendVerification($id);
                if($mail = TRUE) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Berhasil Diubah. Email Notifikasi terkirim ke Satker"]]));
                } else {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Berhasil Diubah. Email Notifikasi tidak terkirim ke Satker"]]));
                }
            }
            else
            {
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah data"]]));
            }
   
        }
    }

    public function deleteSpmk()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mspmk->deleteSpmk($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data SPM Koreksi Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data SPM Koreksi"]]));
            }

        }
    }


    function sendVerification($id){
        $spmkInfo = $this->Mspmk->getInfoSpmk($id);
        $noper = "";

        if(!empty($spmkInfo)){
            if($spmkInfo->spm_no_persetujuan != NULL) {
                $noper = " dengan No. Surat Persetujuan ".$spmkInfo->spm_no_persetujuan;
            }

            $data1["satker"] = $spmkInfo->satker_nama;
            $data1["surat"] = $spmkInfo->spm_no_surat;
            $data1["email"] = $spmkInfo->satker_email;
            $data1["subject"] = "SPM Koreksi ".$spmkInfo->spm_status;
            $data1["open_message"] = " SPM Koreksi dengan No. Surat ";
            $data1["message"] = " telah ".$spmkInfo->spm_status . $noper;

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