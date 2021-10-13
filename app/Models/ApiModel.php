<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\GlobalModel;
use App\Models\CurlModel;
use InfraDigital\ApiClient;
use InfraDigital\ApiClient\Utils\ClientUtil;
use InfraDigital\ApiClient\Constants\Constants;
use InfraDigital\ApiClient\Adapter;
use Http\Client\HttpClient;
use Http\Message;


class ApiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->m_global = new GlobalModel();
        $this->curl     = new CurlModel();
        $this->session  = session();
    }

    public function findApi($where, $required = true)
    {
        $setting = $this->m_global->get_row('*', 'd_setting', [], '', '', false);
        if(is_null($setting->api_type) || empty($setting->api_type) || $setting->api_type=='dev'){
            $where['status !='] = 'prod';
        } else {
            $where['status'] = 'prod';
        }
        $q = $this->m_global->get_row('*', 'api', $where, '', '', false);
        if (!$q) {
            if ($required) {
                throw new \Exception("Tidak ditemukan API");
            } else {
                return false;
            }
        }
        return $q;
    }

    public function idn_send($data, $type)
    {
        if(!$type){
            throw new \Exception("Belum memilih perintah API");
        }

        $setting = $this->m_global->get_row('*', 'd_setting', [], '', '', false);
        if(is_null($setting->api_type) || empty($setting->api_type) || $setting->api_type=='dev'){
            $user = $_ENV['IDN_US_DEV'];
            $pass = $_ENV['IDN_PASS_DEV'];
            $uri  = Constants::URI_DOMAIN_DEV;
            $idnClient = new ApiClient\Client($user, $pass);
            $idnClient->setDevMode(); // This will be tell the class to use development uri
        } else {
            $user = $_ENV['IDN_US_PROD'];
            $pass = $_ENV['IDN_PASS_PROD'];
            $uri  = Constants::URI_DOMAIN_PROD;
            $idnClient = new ApiClient\Client($user, $pass);
            $idnClient->unsetDevMode(); 
        }

        $api = $idnClient->studentApi();

        switch ($type) {
            case 'insert':
                $send_data = $api->createStudent($data[0], $data[1], $data[2], $data[3], $data[4])->getResponse();
                break;
            case 'insert_batch':
                $send_data = $api->createStudents($data)->getResponse();
                break;
            case 'update':
                $send_data = $api->updateStudent($data[0], $data[1], $data[2], $data[3], $data[4])->getResponse();
                break;
            case 'delete':
                $send_data = $api->deleteStudent($data)->getResponse();
                break;
            case 'get_custom':
                $send_data = $api->getStudents($data[0], $data[1])->getResponse();
                break;
            case 'invoice':
                $send_data = $api->addBillComponents($data)->getResponse();
                break;
            case 'get_allBill':
                $send_data = $api->getBills($data)->getResponse();
                break;
            case 'get_bill':
                $send_data = $api->getStudentBills($data[0], $data[1])->getResponse();
                break;
            case 'update_inv':
                $utils  = new ClientUtil();
                $path   = 'bill_component';
                $headers = array_merge(array('Content-Type: application/json'));
                $build_uri = Constants::URI_PROTOCOL.'://'.$user.':'.$utils->passwordHash($pass).'@'.$uri.'/'.$path.'';
                $send_data = (array)$this->curl->_sendIdn_($build_uri, $data, $headers, 'PUT');
                break;
            case 'delete_inv':
                $utils  = new ClientUtil();
                $path   = 'bill_component/delete';
                $headers = array_merge(array('Content-Type: application/json'));
                $build_uri = Constants::URI_PROTOCOL.'://'.$user.':'.$utils->passwordHash($pass).'@'.$uri.'/'.$path.'';
                $send_data = (array)$this->curl->_sendIdn_($build_uri, $data, $headers);
                break;
            
            default:
                return true;
                break;
        }

        if (!$send_data) {
            throw new \Exception("Tidak dapat membaca respon data");
        } else if(in_array($send_data['status_code'], [400,401,403,404,422,500,502,503,504])){
            throw new \Exception($send_data['status'].' '.$send_data['status_desc']);
        }
        
        if($type=='insert_batch' && $send_data['status_code'] == '201'){
            return ['status' => $send_data['status_code'], 'data' => $send_data['data']];
        } else {
            return $send_data['data'];
        }
    }

    public function wa_pingnotif($param)
    {
        $where = ['type' => $param['type']];
        $q = $this->m_global->get_row('*', 'api', $where, '', '', false);
        if($q){
            $send_data = [
                'message'      => $param['message'],
                'number_phone' => $param['phone'], 
            ];
            $field = str_replace("+", " ", http_build_query($send_data)) ;
            $apikey = $q->token;
            $header = [
                'key: '.$apikey,
                'Content-Type: application/x-www-form-urlencoded',
            ];

            $curl = curl_init();
            curl_setopt_array($curl, 
                [
                    CURLOPT_URL => $q->uri,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $field,
                    CURLOPT_HTTPHEADER => $header,
                ]
            );
            $exec = curl_exec($curl);
            $respon = json_decode($exec);

            if (curl_error($curl)) {
                throw new \Exception(curl_error($curl));
            } else if (curl_error($curl)) {
                return false;
            }
            curl_close($curl);

            return $respon;
        } else {
            throw new \Exception('API Tidak Ditemukan');
        }
    }

    public function wa_fonnte($param)
    {
        $where = ['type' => $param['type']];
        $q = $this->m_global->get_row('*', 'api', $where, '', '', false);
        if($q){
            $send_data = [
                'type'      => 'text',
                'text'      => $param['message'],
                'delay'     => '1',
                'delay_req' => '1',
                'schedule'  => '0',
                'phone'     => $param['phone'], 
            ];
            $field = str_replace("+", " ", http_build_query($send_data)) ;
            $apikey = $q->token;
            $header = [
                'Authorization: '.$apikey
            ];

            $curl = curl_init();
            curl_setopt_array($curl, 
                [
                    CURLOPT_URL => $q->uri,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $field,
                    CURLOPT_HTTPHEADER => $header,
                ]
            );
            $exec = curl_exec($curl);
            $respon = json_decode($exec);

            if (curl_error($curl)) {
                throw new \Exception(curl_error($curl));
            } else if (curl_error($curl)) {
                return false;
            }
            curl_close($curl);

            return $respon;
        } else {
            throw new \Exception('API Tidak Ditemukan');
        }
    }

    public function formatPhoneNumber($phone, $type='whatsapp')
    {
        $numbers_only = preg_replace("/[^\d]/", "", $phone);
        $res = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1$2$3", $numbers_only);
        $first = substr($res, 0, 1);
        if($first == '0'){
            $wa = '62'.substr($res,1);          $sms = $res;
        } else { 
            $wa = $res;                         $sms = '0'.substr($res,2);
        }
        
        if($type == 'whatsapp'){
            return $wa;
        } else {
            return $sms;
        }
    }

    public function sms($param)
    {
        $ins = [
            'DestinationNumber' => $this->formatPhoneNumber($param['phone'], 'sms'),
            'TextDecoded'       => $param['message'],
            'CreatorID'         => 'Gammu'
        ];
        $q_insert = $this->m_global->db_insert('outbox', $ins);
        if(!$q_insert){
            return false;
        }
        return true;
    }

    public function _notif($param)
    {
        if($param['vendor']=='pingnotif'){
            $send = $this->wa_pingnotif($param);
        } elseif($param['vendor']=='fonnte'){
            $send = $this->wa_fonnte($param);
        } elseif($param['vendor']=='sms'){
            $send = $this->sms($param);
            if(!$send){ return false; }
            return true;
        }
    }

}
