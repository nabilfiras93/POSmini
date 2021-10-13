<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Models\CurlModel;
use App\Models\GlobalModel;
use App\Models\SiakadModel;
use App\Models\ApiModel;

class Api extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->m_global = new GlobalModel();
        $this->m_siakad = new SiakadModel();
        $this->db = \Config\Database::connect();
        $this->db_siakad = \Config\Database::connect('siakad');

        $setting = $this->m_global->get_row('*', 'd_setting', [], '', '', false);
        if(is_null($setting->api_type) || empty($setting->api_type) || $setting->api_type=='dev'){
            $this->user = $_ENV['IDN_US_DEV'];
            $this->pass = $_ENV['IDN_PASS_DEV'];
        } else {
            $this->user = $_ENV['IDN_US_PROD'];
            $this->pass = $_ENV['IDN_PASS_PROD'];
        }
    }

    public function index(){
 
    }

    private function verify($ref=null){
        $key = hash('sha256', $this->pass);
        $val = $_ENV['IDN_BILLER_CODE'].':'.$ref;
        return hash_hmac('sha256', $val, $key);
    }

    private function apiKey(){
        $key = hash('sha256', $this->pass);
        $val = $_ENV['IDN_BILLER_CODE'];
        return hash_hmac('sha256', $val, $key);
    }

    // public function my_api(){
    //     $key = hash('sha256', $this->pass);
    //     $val = $_ENV['IDN_BILLER_CODE'];
    //     echo hash_hmac('sha256', $val, $key);
    // }

    // public function reff($ref=null){
    //     $key = hash('sha256', $this->pass);
    //     $val = $_ENV['IDN_BILLER_CODE'].':'.$ref;
    //     echo hash_hmac('sha256', $val, $key);
    // }

    public function payment() {
        $this->db->transBegin();
        try {
            $head   = $this->request->header('X-IDN-CS')->getValue();
            $param  = $this->request->getVar();
            $reff   = $param->ref_number;
            if($this->verify($reff) != $head){
                throw new \Exception('Unauthorized');
            }
            $get_reff = $this->m_global->get_row('*', 'idn_payment', ['ref_number'=>$reff], '', '', false);
            if($get_reff){
                throw new \Exception('Payment Failed, There is same reff number');
            }
            $data = [
                "merchant_name" =>  $param->merchant_name,
                "biller_name"   =>  $param->biller_name,
                "biller_code"   =>  $param->biller_code,
                "bill_name"     =>  $param->bill_name,
                "bill_key"      =>  $param->bill_key,
                "total_bill_amount" =>  $param->total_bill_amount,
                "bill_quantity" =>  $param->bill_quantity,
                "admin_fee"     =>  $param->admin_fee,
                "ref_number"    =>  $param->ref_number,
                "merchant_ref_number" =>  $param->merchant_ref_number,
                "paid_date"     =>  explode('.', str_replace('T', ' ', $param->paid_date))[0],
                "created_at"    =>  date('Y-m-d H:i:s'),
            ];
            foreach ($param->bill_component_list as $k => $val) {
                $up = [
                    'amount_paid'   => $val->amount_paid,
                    'amount_currency' => $val->amount_currency,
                    'notes'         => $val->notes,
                    'state'         => $val->state,
                    'ref_number'    => $val->ref_number
                ];
                $bill_id = $val->bill_component_id;
                $this->m_global->db_update('idn_invoice', $up, ['invoice_id' => $val->bill_component_id]);
            }
            $data['bill_id'] = $bill_id;
            $bill = $this->m_global->db_insert('idn_payment', $data);

            $get_inv = $this->m_global->get_row('*', 'idn_invoice', ['invoice_id'=>$bill_id], '', '', false);
            $data_siakad = $data;
            $data_siakad['tagihan_id'] = $get_inv->siakad_tagihan_id;
            $data_siakad['nama_tagihan'] = $get_inv->bill_component_name; 
            $data_siakad['jumlah_tagihan'] = $get_inv->amount; 
            $bayar = $this->m_siakad->db_insert('idn_pembayaran', $data_siakad);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => ['success' => 'Payment success']
            ];
            $this->db->transCommit();
            return $this->respondCreated($response);
        } catch (\Exception $th) {
            $this->db->transRollback();
            return $this->failUnauthorized($th->getMessage());
        }
    }

    public function bill() {
        ini_set('max_execution_time', -1);

        try {
            if(!$head = $this->request->header('X-IDN-CS')){
                throw new \Exception('Unauthorized');
            }

            if($this->apiKey() != $head->getValue()){
                throw new \Exception('Unauthorized');
            }

            $students = $this->m_global->get_result('bill_key_value', 'idn_student', [], '', '', false);
            if(!$students){ 
                throw new \Exception('Tidak menemukan data mahasiswa'); 
            }
            $nim = [];
            foreach ($students as $key => $val) {
                $nim[] = $val->bill_key_value;
            }

            $where_in = [
                'param' => 'mst_mhs.nim',
                'value' => $nim,
            ];
            // $where = ['mst_mhs.status_id' => $kode_siakad_mhs_aktif];
            $where = [];
            $join = [
                ['table' => 'keuangan_tagihan', 'on' => '(keuangan_tagihan.th_angkatan_id = mst_mhs.th_akademik_id AND keuangan_tagihan.prodi_id = mst_mhs.prodi_id AND keuangan_tagihan.kelas_id = mst_mhs.kelas_id)', 'type'=>'left'],
                ['table' => 'mst_th_akademik', 'on' => 'keuangan_tagihan.th_akademik_id = mst_th_akademik.id', 'type'=>'left'],
            ];
            $get_tagihan = $this->m_siakad->get_result_join('
                mst_mhs.nim, 
                keuangan_tagihan.nama as tagihan, 
                keuangan_tagihan.jumlah, 
                keuangan_tagihan.th_akademik_id, 
                keuangan_tagihan.kode,
                mst_th_akademik.kode as th_akademik_kode, 
                keuangan_tagihan.id', 'mst_mhs', $join, $where, '', '', false, $where_in);
            if(!$get_tagihan){
                throw new \Exception('Tidak menemukan data di Siakad');
            }

            $setting    = $this->m_global->get_row('*', 'd_setting', ['id' => 1], '', '', false);
            $batas_hari = ($setting->batas_hari_pembayaran=='' || is_null($setting->batas_hari_pembayaran)) ? 30 : $setting->batas_hari_pembayaran;
            $bank       = ($setting->bank_default_pembayaran=='' || is_null($setting->bank_default_pembayaran)) ? 'mandiri_syariah' : $setting->bank_default_pembayaran;
            $get_bank   = $this->m_global->get_row('*', 'idn_bank', ['type' => $bank], '', '', false);
            $my_data = [];
            foreach ($get_tagihan as $k => $val) {
                $tagihan_id     = $val->id;
                $get_bayar      = $this->m_siakad->get_row('SUM(jumlah) as dibayar', 'keuangan_pembayaran', ['tagihan_id'=>$tagihan_id, 'nim'=>$val->nim], '', 'nim', false);

                if($get_bayar){ //mencari data jika tagihan sudah dibayar melalui siakad
                    if((float)$get_bayar->dibayar >= (float)$val->jumlah){
                        continue;
                    } else {
                        $get_tagihan_idn = $this->m_global->get_row('amount', 'idn_invoice', ['siakad_tagihan_id'=>$tagihan_id, 'bill_key'=>$val->nim], '', '', false);
                        if($get_tagihan_idn){
                            continue;
                        }
                        $amount_tagihan = (float)$val->jumlah - (float)$get_bayar->dibayar;
                    }
                } else {
                    $get_tagihan_idn = $this->m_global->get_row('amount', 'idn_invoice', ['siakad_tagihan_id'=>$tagihan_id, 'bill_key'=>$val->nim], '', '', false);
                    if($get_tagihan_idn){
                        continue;
                    }
                    $amount_tagihan = (float)$val->jumlah;
                }

                $my_data[] = [
                    'student_id'   => $val->nim,
                    'bill_name'   => $val->nama_tagihan.' ('.$val->kode.')',
                    'amount_bill'   => $amount_tagihan,
                    'th_akademik_id'   => $val->th_akademik_id,
                    'th_akademik_code'   => $val->th_akademik_kode,
                    'bank_account_code' => $get_bank->kode_idn,
                    'bank_account_code' => $get_bank->kode_idn,
                    'due_date_days'    => $batas_hari,
                ];
            }



            //================================================================================================================
            // $param  = $this->request->getVar();
            // $nim    = @$param->nim ;
            // if($nim){
            //     $where_s  = ['bill_key' => $nim];
            // } else {
            //     $where_s  = [];
            // }

            // $get_student = $this->m_global->get_result('bill_key_value as nim, name as nama', 'idn_student', $where_s, '', '', false);
            // if(!$get_student){
            //     throw new \Exception('Data Mahasiswa tidak ditemukan');
            // }
            // foreach ($get_student as $key => $val) {
            //     $student[] = [
            //         'nim' => $val->nim,
            //         'nama' => $val->nama
            //     ];
            // }

            // $get_inv = $this->m_global->get_result('bill_key as nim, siakad_tagihan_id, bill_component_name as tagihan, amount as jumlah', 'idn_invoice', $where_s, '', '', false);
            // foreach ($get_inv as $key => $val) {
            //     $inv[] = [
            //         'nim' => $val->nim,
            //         'siakad_tagihan_id' => $val->siakad_tagihan_id,
            //         'tagihan' => $val->tagihan,
            //         'jumlah' => $val->jumlah,
            //     ];
            // }

            // $join = [
            //     ['table' => 'idn_invoice', 'on' => 'idn_invoice.invoice_id = idn_payment.bill_id', 'type'=>'left'],
            // ];
            // $get_pay = $this->m_global->get_result_join('idn_invoice.siakad_tagihan_id, idn_payment.bill_key as nim, idn_payment.bill_name as nama, merchant_name as merchant, idn_payment.ref_number, idn_payment.total_bill_amount as dibayar', 'idn_payment', $join, [], '', '', false);
            // foreach ($get_pay as $key => $val) {
            //     $pay[] = [
            //         'nim' => $val->nim,
            //         'nama' => $val->nama,
            //         'siakad_tagihan_id' => $val->siakad_tagihan_id,
            //         'dibayar' => $val->dibayar,
            //         'merchant' => $val->merchant,
            //         'ref_number' => $val->ref_number,
            //     ];
            // }

            // foreach ($inv as $k => $valu) {
            //     foreach ($pay as $l => $value) {
            //         if($valu['siakad_tagihan_id'] == $value['siakad_tagihan_id'] && $valu['nim'] == $value['nim']){
            //             $inv[$k]['pembayaran'][] = $value;
            //         } else {
            //             $inv[$k]['pembayaran'] = [];
            //         }
            //     }
            // }

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success'   => 'Get success',
                    'data'      => $my_data
                    // 'data'      => $inv
                ]
            ];
            return $this->respondCreated($response);
        } catch (\Exception $th) {
            return $this->failUnauthorized($th->getMessage());
        }
    }

}