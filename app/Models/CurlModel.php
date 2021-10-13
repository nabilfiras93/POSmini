<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\GlobalModel;


class CurlModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->apikey = $_ENV['MY_API_KEY'];
        $this->m_global = new GlobalModel();
    }
    
    public function _post($url, $header, $body)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function _send_($url, $data, $headers, $method = "POST", $jsonEncode = true, $build_query = false, $timeout = 30)
    {
        if ($jsonEncode) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        }
        if ($build_query) {
            $data = http_build_query($data);
        }

        $curlHandle = curl_init($url);

        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        if ($timeout > 0) {
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, $timeout);
        }
        $exec = curl_exec($curlHandle);
        $respon = json_decode($exec);

        if (curl_error($curlHandle) ) {
            throw new \Exception(curl_error($curlHandle));
        } else if (curl_error($curlHandle) ) {
            return false;
        }
        curl_close($curlHandle);

        return $respon;
    }

    public function _sendIdn_($url, $data, $headers, $method = "POST", $jsonEncode = true, $build_query = false, $timeout = 30)
    {
        if ($jsonEncode) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        }
        if ($build_query) {
            $data = http_build_query($data);
        }

        $curlHandle = curl_init($url);

        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        if ($timeout > 0) {
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, $timeout);
        }
        $exec = curl_exec($curlHandle);
        $respon = json_decode($exec);

        if (curl_error($curlHandle) ) {
            throw new \Exception(curl_error($curlHandle));
        } else if (in_array($respon->status_code, [400,401,403,404,422,500,502,503,504]) ) {
            throw new \Exception($respon->status.' '.$respon->status_desc);
        }
        curl_close($curlHandle);

        return $respon;
    }

    public function _get($url, $header = null)
    {
        if ($header) {
            $opts = array(
                'http' => array(
                    'method' => "GET",
                    'header' => $header
                )
            );
        } else {
            $opts = array(
                'http' => array(
                    'method' => "GET"
                )
            );
        }
        $context = stream_context_create($opts);
        $data = file_get_contents($url, false, $context);
        return $data;
    }

    public function _put($url, $header, $body)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function _delete($params)
    {
    }

    public function card_id($param)
    {
        $where = ['type' => $param['type']];
        $q = $this->m_global->get_row('*', 'api', $where, '', '', false);
        if($q){
            $url = $q->uri;
            $header = ['Content-Type: application/json'];
            $body = [
                'card_id'   => $param['card_id'],
                'type'      => $param['type'],
                'apikey'    => $this->apikey
            ];
            $body = json_encode($body);
            $response = json_decode($this->_post($url, $header, $body), JSON_UNESCAPED_SLASHES);
            if (!$response) {
                return false;
                // throw new \Exception('Gagal mendapatkan data. Server tidak memberikan response yang benar.');
            } else if (!$response['status'] || $response['status'] != true) {
                throw new \Exception('Gagal mendapatkan data. ' . $response['message']);
            }
            return $response;
        } else {
            throw new \Exception('API Tidak Ditemukan');
        }
    }

    public function nis($param)
    {
        $where = ['type' => $param['type']];
        $q = $this->m_global->get_row('*', 'api', $where, '', '', false);
        if($q){
            $url = $q->uri;
            $header = ['Content-Type: application/json'];
            $body = [
                'nis'       => $param['nis'],
                'type'      => $param['type'],
                'apikey'    => $this->apikey
            ];
            $body = json_encode($body);
            $response = json_decode($this->_post($url, $header, $body), JSON_UNESCAPED_SLASHES);
            if (!$response) {
                throw new \Exception('Gagal mendapatkan data. Server tidak memberikan response yang benar.');
            } else if (!$response['status'] || $response['status'] != true) {
                throw new \Exception('Gagal mendapatkan data. ' . $response['message']);
            }
            return $response;
        } else {
            throw new \Exception('API Tidak Ditemukan');
        }
    }
}
