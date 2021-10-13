<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Controllers\Auth;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\Codeigniter4Adapter;


class Product extends ResourceController
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
        if(!$access){
            return redirect()->to(base_url('/auth'));
        }
        $page_title = 'Product';
        return view("product/index", compact('page_title', 'token', 'access'));
    }

    public function data()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');
        $access = $this->protect->myAccess($token);
        if(!$access){
            throw new \Exception('Acces denied');
        }
        $filter = '';
        if($access->data->group_id != '2'){
            $filter = "where product_price.outlet_id = {$access->data->outlet_id} ";
        }

        $param      = $this->request->getPost();
        $dt = new Datatables(new Codeigniter4Adapter);
        $dt->query("Select product.id, product.name, sku, image, product_price.price, product_price.outlet_id, outlet.name as outlet from product 
            left join product_price on product.id = product_price.product_id
            left join outlet on outlet.id = product_price.outlet_id
            {$filter} ");
        echo $dt->generate();
    }

    public function create()
    {
        $this->db->transBegin();
        try {
            $token = $this->request->getServer('HTTP_AUTHORIZATION');
            $access = $this->protect->myAccess($token);
            if(!$access || $access->data->group_id != '2'){
                throw new \Exception('Acces denied');
            }
            if ($this->request->isAJAX()) {
                $files = $this->request->getFiles();
                $rules = [
                        'name' => 'required',
                        'sku' => 'required',
                        'file' => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/gif,image/png]',

                ];

                $this->validation->setRules($rules,
                [   'name' => ['required' => 'name harus diisi'],
                    'sku' => ['required' => 'sku harus diisi'],
                    'file' => ['required' => 'file harus dipilih'],
                ]);
                if(!$this->validation->withRequest($this->request)->run()){
                    throw new \Exception(strip_tags($this->validation->listErrors()));
                }

                $param = $this->request->getPost();
                $ins = [
                    'name'  => $param['name'],
                    'sku'  => $param['sku'],
                ];

                if (!empty($this->request->getFile('file')->getClientMimeType())) {
                    $img = $this->request->getFile('file');
                    $imgName = $img->getRandomName();
                    $img->move(ROOTPATH . 'public/uploads', $imgName);
                    $ins['image'] = $imgName;
                }

                $insert = $this->m_global->db_insert('product', $ins);
                if(!$insert){
                    throw new \Exception('Gagal Insert');
                }
                
                $get_outlet = $this->m_global->get_result('*', 'outlet');
                foreach ($get_outlet as $key => $val) {
                    $up_detail['product_id'] = $insert;
                    $up_detail['outlet_id'] = $val->id;
                    $up_detail['price'] = $param['price'];
                    $up_detail['created_at'] = date('Y-m-d H:i:s');
                    $insert_detail = $this->m_global->db_insert('product_price', $up_detail);
                    if(!$insert_detail){
                        throw new \Exception('Gagal insert');
                    }
                }

                $output = [
                    'status'    => 200,
                    'message'   => 'Berhasil Insert',
                    "to"        => '/product'
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

    public function update_product()
    {
        $this->db->transBegin();
        try {
            $token = $this->request->getServer('HTTP_AUTHORIZATION');
            $access = $this->protect->myAccess($token);
            if(!$access){
                throw new \Exception('Acces denied');
            }

            if ($this->request->isAJAX()) {
                $param = $this->request->getPost();
                $rules = [
                        'name' => 'required',
                        'sku' => 'required',
                ];
                $this->validation->setRules($rules,
                [   'name' => ['required' => 'name harus diisi'],
                    'sku' => ['required' => 'sku harus diisi'],
                ]);
                if(!$this->validation->withRequest($this->request)->run()){
                    throw new \Exception(strip_tags($this->validation->listErrors()));
                }

                $where = ['id'=>$param['id']];
                $get = $this->m_global->get_row('*', 'product', $where);
                
                $up = [
                    'name'  => $param['name'],
                    'sku'  => $param['sku'],
                ];

                if($access->data->group_id != '2'){
                    if($param['price']){
                        $up_detail['price'] = $param['price'];
                        $where_detail = ['product_id'=>$param['id'] , 'outlet_id'=>$access->data->outlet_id];
                        $get_detail = $this->m_global->get_row('*', 'product_price', $where_detail);
                        if($get_detail){
                            $update = $this->m_global->db_update('product_price', $up_detail, $where_detail);
                            if(!$update){
                                throw new \Exception('Gagal update');
                            }
                        } else {
                            $up_detail['product_id'] = $param['id'];
                            $up_detail['outlet_id'] = $access->data->outlet_id;
                            $up_detail['created_at'] = date('Y-m-d H:i:s');
                            $insert = $this->m_global->db_insert('product_price', $up_detail);
                            if(!$insert){
                                throw new \Exception('Gagal insert');
                            }
                        }
                    }
                } else {
                    if (@$_FILES['file'] && !empty($this->request->getFile('file')->getClientMimeType())) {
                        $img_path = ROOTPATH . 'public/uploads/'.$get->image;
                        if(file_exists($img_path)){
                            unlink($img_path);
                        }
                        $img = $this->request->getFile('file');
                        $imgName = $img->getRandomName();
                        $img->move(ROOTPATH . 'public/uploads', $imgName);
                        $up['image'] = $imgName;
                    }

                    $update = $this->m_global->db_update('product', $up, $where);
                    if(!$update){
                        throw new \Exception('Gagal update');
                    }
                }

                $output = [
                    'status'    => 200,
                    'message'   => 'Berhasil update',
                    "to"        => '/product'
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

    public function delete_product()
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
                [   'id' => ['required' => 'Tidak menemukan id produk'],
                ]);
                if(!$this->validation->withRequest($this->request)->run()){
                    throw new \Exception(strip_tags($this->validation->listErrors()));
                }

                $param = $this->request->getPost();
                $where = ['id'=>$param['id']];
                

                $get = $this->m_global->get_row('*', 'product', $where);
                $update = $this->m_global->db_delete('product', $where);
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
                    "to"        => '/product'
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