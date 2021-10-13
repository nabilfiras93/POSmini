<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use \Firebase\JWT\JWT;

class Auth extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->m_global = model('App\Models\GlobalModel');
        $this->db 		= \Config\Database::connect();
        $this->validation = \Config\Services::validation();
		$this->session 	= session();

    }

    private function privateKey()
    {
        $privateKey = "
            MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
            vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
            5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
            AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
            bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
            Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
            cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
            5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
            ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
            k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
            qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
            eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
            B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=";
        return $privateKey;
    }

    public function myAccess($token=null)
    {
    	try {
	    	$secret_key = $this->privateKey();
	    	$decoded 	= JWT::decode($token, $secret_key, ['HS256']);
	    	if(!$decoded){
            	return false;
	    	} 
            
            return $decoded;
	    } catch (\Exception $th) {
            return false;
        }
    }

    public function index(){
 		$p['page_title'] = 'Login';
        return view("login", $p);
    }

    public function login()
    {
        try {
            $this->validation->setRules([
                'username' => 'required',
                'password' => 'required',
            ],
            [   'username' => ['required' => 'Username harus diisi'],
                'password' => ['required' => 'Password harus diisi']
            ]);
            if(!$this->validation->withRequest($this->request)->run()){
                throw new \Exception(strip_tags($this->validation->listErrors()));
            }

            $param = $this->request->getPost();
            $where = ['username' => $param['username'] ];
            $q = $this->m_global->get_row('*', 'user', $where);

            if(!$q){
                throw new \Exception('Tidak ditemukan data');
            }

        	helper('my');
            $verify = my_verify_hash($param['password'], $q->password);
            if($verify != 1){
                throw new \Exception('Pastikan Kombinasi Username dan Password Benar');
            }

            $secret_key = $this->privateKey();
            $issuer_claim = "POSMINI"; // this can be the servername. Example: https://domain.com
            $issuedat_claim = time(); // issued at
            $notbefore_claim = $issuedat_claim + 0; //not before in seconds
            $expire_claim = $issuedat_claim + 3600; // expire time in seconds
            $token = array(
                "iss" => $issuer_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => $q
            );
 
            $token = JWT::encode($token, $secret_key);
            $output = [
                'status' 	=> 200,
                'message' 	=> 'Berhasil login',
                "token" 	=> $token,
                "identity" 	=> $param['username'],
                "expireAt" 	=> $expire_claim,
                "to"		=> '/'
            ];
            $this->session->set('token',$token);

            return $this->respond($output, 200);
        } catch (\Exception $th) {
            $output = [
                'status' => 401,
                'message' => 'Login failed. '.$th->getMessage(),
            ];
            return $this->respond($output, 200);
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('/'));
    }

}