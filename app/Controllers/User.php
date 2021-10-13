<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Controllers\Auth;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\Codeigniter4Adapter;


class User extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->m_global = model('App\Models\GlobalModel');
        $this->db       = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
        $this->protect  = new Auth();
        $this->session  = session();
    }

    public function index(){
        $token  = $this->session->get('token');
        $access = $this->protect->myAccess($token);
        if(!$access || $access->data->group_id != '2'){
            return redirect()->to(base_url('/'));
        }
        $page_title = 'User';
        $outlet = $this->m_global->get_result('*', 'outlet');

        return view("user/index", compact('page_title', 'token', 'access', 'outlet'));
    }

    public function data()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        $access = $this->protect->myAccess($token);
        if(!$access){
            throw new \Exception('Acces denied');
        }

        $param      = $this->request->getPost();
        $dt = new Datatables(new Codeigniter4Adapter);
        $dt->query("Select user.id, user.name, username, outlet.name as outlet, user.outlet_id, user.image, d_group.name as grup 
            from user 
            LEFT JOIN outlet ON outlet.id = user.outlet_id
            LEFT JOIN d_group ON d_group.id = user.group_id
            WHERE user.group_id = '3' ");
        echo $dt->generate();
    }

    public function create()
    {
        $this->db->transBegin();
        try {
            $token = $this->request->getServer('HTTP_AUTHORIZATION');
            $access = $this->protect->myAccess($token);
            if(!$access){
                throw new \Exception('Acces denied');
            }
            if ($this->request->isAJAX()) {
                $rules = [
                        'name' => 'required',
                        'username' => 'required',
                        'outlet' => 'required',
                        'file' => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/gif,image/png]',
                ];
                $this->validation->setRules($rules,
                [   'name' => ['required' => 'name harus diisi'],
                    'username' => ['required' => 'username harus diisi'],
                    'file' => ['required' => 'file harus dipilih'],
                    'outlet' => ['required' => 'outlet harus dipilih'],
                ]);
                if(!$this->validation->withRequest($this->request)->run()){
                    throw new \Exception(strip_tags($this->validation->listErrors()));
                }

                helper('my');
                $default = '123456';
                $id_group_outlet = '3';
                $param = $this->request->getPost();
                $ins = [
                    'name'  => $param['name'],
                    'username'  => $param['username'],
                    'outlet_id'  => $param['outlet'],
                    'group_id'  => $id_group_outlet,
                    'password'  => my_hash($default),
                    'created_at'  => date('Y-m-d H:i:s'),
                ];

                if (!empty($this->request->getFile('file')->getClientMimeType())) {
                    $img = $this->request->getFile('file');
                    $imgName = $img->getRandomName();
                    $img->move(ROOTPATH . 'public/uploads', $imgName);
                    $ins['image'] = $imgName;
                }

                $insert = $this->m_global->db_insert('user', $ins);
                if(!$insert){
                    throw new \Exception('Gagal Insert');
                }
                 
                $output = [
                    'status'    => 200,
                    'message'   => 'Berhasil Insert',
                    "to"        => '/user'
                ];

                $this->db->transCommit();
                return $this->respond($output, 200);
            } else {
                throw new \Exception('Unauthorized Form');
            }
        } catch (\Exception $th) {
            $this->db->transRollback();
            $output = [
                'status' => 401,
                'message' => 'Failed. '.$th->getMessage(),
            ];
            return $this->respond($output, 200);
        }
    }

    public function update_user()
    {
        $this->db->transBegin();
        try {
            $token = $this->request->getServer('HTTP_AUTHORIZATION');
            $access = $this->protect->myAccess($token);
            if(!$access){
                throw new \Exception('Acces denied');
            }

            if ($this->request->isAJAX()) {
                $rules = [
                        'name' => 'required',
                        'username' => 'required',
                        'outlet' => 'required',
                ];
                $this->validation->setRules($rules,
                [   'name' => ['required' => 'name harus diisi'],
                    'username' => ['required' => 'username harus diisi'],
                    'outlet' => ['required' => 'outlet harus dipilih'],
                ]);
                if(!$this->validation->withRequest($this->request)->run()){
                    throw new \Exception(strip_tags($this->validation->listErrors()));
                }

                helper('my');
                $default = '123456';
                $param = $this->request->getPost();
                $up = [
                    'name'  => $param['name'],
                    'username'  => $param['username'],
                    'outlet_id'  => $param['outlet'],
                ];

                $where = ['id'=>$param['id']];
                $get = $this->m_global->get_row('*', 'user', $where);
                
                if (!empty($this->request->getFile('file')->getClientMimeType())) {
                    $img_path = ROOTPATH . 'public/uploads/'.$get->image;
                    if(file_exists($img_path)){
                        unlink($img_path);
                    }
                    $img = $this->request->getFile('file');
                    $imgName = $img->getRandomName();
                    $img->move(ROOTPATH . 'public/uploads', $imgName);
                    $up['image'] = $imgName;
                }

                $update = $this->m_global->db_update('user', $up, $where);
                if(!$update){
                    throw new \Exception('Gagal update');
                }

                $output = [
                    'status'    => 200,
                    'message'   => 'Berhasil update',
                    "to"        => '/user'
                ];

                $this->db->transCommit();
                return $this->respond($output, 200);
            } else {
                throw new \Exception('Unauthorized Form');
            }
        } catch (\Exception $th) {
            $this->db->transRollback();
            $output = [
                'status' => 401,
                'message' => 'Failed. '.$th->getMessage(),
            ];
            return $this->respond($output, 200);
        }
    }

    public function delete_user()
    {
        $this->db->transBegin();
        try {
            $token = $this->request->getServer('HTTP_AUTHORIZATION');
            $access = $this->protect->myAccess($token);
            if(!$access){
                throw new \Exception('Acces denied');
            }

            if ($this->request->isAJAX()) {
                $this->validation->setRules([
                    'id' => 'required',
                ],
                [   'id' => ['required' => 'Tidak menemukan id user'],
                ]);
                if(!$this->validation->withRequest($this->request)->run()){
                    throw new \Exception(strip_tags($this->validation->listErrors()));
                }

                $param = $this->request->getPost();
                $where = ['id'=>$param['id']];
                

                $get = $this->m_global->get_row('*', 'user', $where);
                $update = $this->m_global->db_delete('user', $where);
                if(!$update){
                    throw new \Exception('Gagal update');
                }

                $img_path = ROOTPATH . 'public/uploads/'.$get->image;
                if(file_exists($img_path)){
                    unlink($img_path);
                }

                $output = [
                    'status'    => 200,
                    'message'   => 'Berhasil Delete',
                    "to"        => '/user'
                ];

                $this->db->transCommit();
                return $this->respond($output, 200);
            } else {
                throw new \Exception('Unauthorized Form');
            }
        } catch (\Exception $th) {
            $this->db->transRollback();
            $output = [
                'status' => 401,
                'message' => 'Failed. '.$th->getMessage(),
            ];
            return $this->respond($output, 200);
        }
    }

}