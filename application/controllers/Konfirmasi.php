<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Konfirmasi (KonfirmasiController)
 * Konfirmasi Class to control all Konfirmasi related operations.
 * @author : Dedi Nopriadi
 */
class Konfirmasi extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mkonfirmasi');
        $this->isLoggedIn();   
    }

    public function index()
    {
        $this->load->library('pagination');

        $this->global['pageTitle'] = 'KPPN Sijunjung : Konfirmasi Penerimaan';

        $this->loadViews("konfirmasi/konfirmasi", $this->global, NULL, NULL);
    }


    public function konfirmasiListing()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $num  = (isset($postdata['num']) ? $postdata['num'] : 50);
        $page = (isset($postdata['page']) ? $postdata['page'] : 1);

        $search  = (isset($postdata['filter']['txt_search']) ? $postdata['filter']['txt_search'] : NULL);

        $count = $this->Mkonfirmasi->getKonfirmasiCount(strtoupper($search));
        $total = (int) $count;

        $total_pages = (($total - 1) / $num) + 1;
        $total_pages =  intval($total_pages);
        $page = intval($page);

        if(empty($page) or $page < 0) $page = 1;
        if($page > $total_pages) $page = $total_pages;

        $start = $page * $num - $num;

        $konfirmasis = $this->Mkonfirmasi->getDataKonfirmasi($start, $num, strtoupper($search));

        foreach($konfirmasis->result_array() as $row){
            $konfirmasi[] = ['id' => $row['konfir_id'], 'kode' => $row['satker_kd'], 'no_surat' => $row['konfir_no_surat'], 'perihal' => $row['konfir_perihal'], 'tgl' => $row['konfir_tgl_terima'], 'status' => $row['konfir_status'], 'ket' => $row['konfir_keterangan'], 'alasan' => $row['konfir_alasan'], 'satker' => $row['satker_nama'], 'email' => $row['satker_email']];
        }

        $response = [];
            if (!empty($konfirmasi)) {  
                $response['page'] = $page;  

                $response['total_count'] = $total;
                $response['konfir'] = $konfirmasi;     
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Data Konfirmasi Penerimaan Kosong"; 
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function getInfoKonfirmasi()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id = (isset($postdata['id']) ? $postdata['id'] : NULL);

        $data = $this->Mkonfirmasi->getInfoKonfirmasi($id);

        $response = [];
            if (!empty($data)) {    
                $response['info'] = $data;   
                $response['success'] = "Data Referensi Ditemukan";   
            }
            else {
                $response['status'] = 'empty';
                $response['error'] = 1; 
                $response['errors'] = "Gagal Mengambil Data Konfirmasi Penerimaan"; 

            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

     function statusKonfirmasi()
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
                $historySpm = array('konfir_id'=>$id, 'history_status'=>'Diproses', 'history_time'=>$waktu);
                $spmInfo = array('konfir_status'=>'Diproses');

                $result = $this->Mkonfirmasi->updateStatus($spmInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Diproses"]]));
                    $this->Mkonfirmasi->addHistory($historySpm);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Diproses. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Diproses. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Status Konfirmasi Penerimaan Gagal diproses"]]));
                }
            } else if($status == 3) {
                $persetujuan = (isset($postdata['datax']['no_persetujuan']) ? $postdata['datax']['no_persetujuan'] : NULL);

                $historySpm = array('konfir_id'=>$id, 'history_status'=>'Selesai', 'history_time'=>$waktu);
                $spmInfo = array('konfir_status'=>'Selesai', 'konfir_keterangan'=>$persetujuan);

                $result = $this->Mkonfirmasi->updateStatus($spmInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Selesai"]]));
                    $this->Mkonfirmasi->addHistory($historySpm);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Selesai. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Selesai. Email Notifikasi tidak terkirim ke Satker"]]));
                    }
                }
                else
                {
                    http_response_code(400);
                    $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah status Konfirmasi Penerimaan"]]));
                }
            } else if($status == 4) {
                $alasan = (isset($postdata['datax']['alasan']) ? $postdata['datax']['alasan'] : NULL);

                $historySpm = array('konfir_id'=>$id, 'history_status'=>'Ditolak', 'history_time'=>$waktu);
                $spmInfo = array('konfir_status'=>'Ditolak', 'konfir_alasan'=>$alasan);

                $result = $this->Mkonfirmasi->updateStatus($spmInfo, $id);
                    
                if($result > 0)
                {
                    // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPM Koreksi Ditolak"]]));
                    $this->Mkonfirmasi->addHistory($historySpm);
                    $mail = $this->sendVerification($id);
                    if($mail = TRUE) {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Ditolak. Email Notifikasi terkirim ke Satker"]]));
                    } else {
                        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Ditolak. Email Notifikasi tidak terkirim ke Satker"]]));
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


    public function addKonfirmasi()
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
            $spmInfo = array('konfir_id'=>$id, 'satker_kd'=>$satker, 'konfir_no_surat'=>$no, 'konfir_perihal'=>$perihal, 'konfir_tgl_terima'=>$tgl_terima, 'konfir_keterangan'=>$ket);

            $historySpm = array('konfir_id'=>$id, 'history_status'=>'Diterima', 'history_time'=>$waktu);

            $result = $this->Mkonfirmasi->addNewKonfirmasi($spmInfo);
            
            if($result > 0)
            {
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Berhasil Ditambahkan"]]));
                $this->Mkonfirmasi->addHistory($historySpm);
            }
            else
            {
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menambahkan data Konfirmasi Penerimaan"]]));
            }

        }
    }


    public function updateKonfirmasi()
    {
        date_default_timezone_set('Asia/Jakarta');
        $waktu      = date('Y-m-d H:i:s');
        $tgl_terima = date('Y-m-d');

        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);
        $no       = (isset($postdata['datax']['konfir_no_surat']) ? $postdata['datax']['konfir_no_surat'] : NULL);
        $satker   = (isset($postdata['datax']['satker_kd']) ? $postdata['datax']['satker_kd'] : NULL);
        $perihal  = (isset($postdata['datax']['konfir_perihal']) ? $postdata['datax']['konfir_perihal'] : NULL);
        $ket      = (isset($postdata['datax']['konfir_keterangan']) ? $postdata['datax']['konfir_keterangan'] : NULL);
        $status   = (isset($postdata['datax']['konfir_status']) ? $postdata['datax']['konfir_status'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $spmInfo = array('konfir_no_surat'=>$no, 'konfir_perihal'=>$perihal, 'konfir_status'=>$status, 'konfir_keterangan'=>$ket);
            $historySpm = array('konfir_id'=>$id, 'history_status'=>$status, 'history_time'=>$waktu);
            
            $result = $this->Mkonfirmasi->saveKonfirmasi($spmInfo, $id);

            if($result == true)
            {
                // $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["SPK Koreksi berhasil diubah"]]));
                $this->Mkonfirmasi->addHistory($historySpm);
                $mail = $this->sendVerification($id);
                if($mail = TRUE) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Berhasil Diubah. Email Notifikasi terkirim ke Satker"]]));
                } else {
                    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Konfirmasi Penerimaan Berhasil Diubah. Email Notifikasi tidak terkirim ke Satker"]]));
                }
            }
            else
            {
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal mengubah data"]]));
            }
   
        }
    }

    public function deleteKonfirmasi()
    {
        $postdata = json_decode(file_get_contents('php://input'), TRUE);
        $id       = (isset($postdata['id']) ? $postdata['id'] : NULL);

        if(empty($id)){
            http_response_code(400);
            $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Referensi ID Kosong"]]));
        }else{

            $delete = $this->Mkonfirmasi->deleteKonfirmasi($id);

            if($delete == TRUE){
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => ["Data Konfirmasi Penerimaan Berhasil Dihapus"]]));
            }else{
                http_response_code(400);
                $this->output->set_content_type('application/json')->set_output(json_encode(['errors' => ["Gagal Menghapus Data Konfirmasi Penerimaan"]]));
            }

        }
    }


    function sendVerification($id){
        $konfirInfo = $this->Mkonfirmasi->getInfoKonfirmasi($id);
        $noper = "";

        if(!empty($konfirInfo)){
            if($konfirInfo->konfir_no_persetujuan != NULL) {
                $noper = " dengan No. Surat Persetujuan ".$konfirInfo->konfir_no_persetujuan;
            }

            $tok = $this->Mkonfirmasi->getToken($skppId);

            foreach($tok->result_array() as $row){
                $token[] = $row['syssatker_idfcm'];
            }

            if (!empty($token)) {  

                $data["judul"] = "Hi " .$konfirInfo->satker_nama;
                $data["pesan"] = "Konfirmasi Penerimaan dengan No. Surat " .$konfirInfo->konfir_no_surat. " telah ".$konfirInfo->konfir_status;
                $data["suara"] = "default";
                $data["activity"] = ".SpmActivity";

                $notif = sendNotif($token, $data);
                  
            }

            $data1["satker"] = $konfirInfo->satker_nama;
            $data1["surat"] = $konfirInfo->konfir_no_surat;
            $data1["email"] = $konfirInfo->satker_email;
            $data1["subject"] = "Konfirmasi Penerimaan ".$konfirInfo->konfir_status;
            $data1["open_message"] = " Konfirmasi Penerimaan dengan No. Surat ";
            $data1["message"] = " telah ".$konfirInfo->konfir_status;

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