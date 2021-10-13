<?php namespace App\Models;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Model;

class DatatableModel extends Model
{
    // protected $table = "tbl_icd";
    // protected $column_order = array('id_icd','kode_icd','nama_icd');
    // protected $column_search = array('nama_icd','kode_icd');
    // protected $order = array('id_icd' => 'desc');
    protected $request;
    // protected $db;
    // protected $dt;

    public function __construct(RequestInterface $request)
    {
        parent::__construct();
        // $this->db = db_connect();
        $this->request = $request;
        // $this->dt = $this->db->table($this->table);
    }

    private function _get_datatables_query($table, $col_sch=array(), $col_ord=array(), $ord=array(), $where=array(), $lastQuery=false){
        $builder = $this->db->table($table);

        $i = 0;
        foreach ($col_sch as $item){
            if($this->request->getPost('search')['value']){ 
                if($i===0){
                    $builder->groupStart();
                    $builder->like($item, $this->request->getPost('search')['value']);
                }
                else{
                    $builder->orLike($item, $this->request->getPost('search')['value']);
                }
                if(count($col_sch) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }

        if(count($col_ord) > 0){
            $builder->orderBy($col_ord[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        }
        else if($this->request->getPost('order')){
            $builder->orderBy($this->request->getPost('order')['0']['column'], $this->request->getPost('order')['0']['dir']);
        } 
        else if(isset($ord)){
            $order = $ord;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        if(count($where) > 0){
            $builder->where($where);
        }

        if($lastQuery == true){
            var_dump ($builder->getCompiledSelect());
            die();
        }
    }
    function get_datatables($table, $col_sch=array(), $col_ord=array(), $ord=array(), $where=array(), $lastQuery=false){
        $builder = $this->db->table($table);

        $this->_get_datatables_query($table, $col_sch, $col_ord, $ord, $where, $lastQuery);
        if($this->request->getPost('length') != -1)
        $builder->limit($this->request->getPost('length'), $this->request->getPost('start'));

        if(count($where) > 0){
            $builder->where($where);
        }

        // if(count($col_ord) > 0){
        //     $builder->orderBy($col_ord[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        // }
        // else if($this->request->getPost('order')){
        //     $builder->orderBy($this->request->getPost('order')['0']['column'], $this->request->getPost('order')['0']['dir']);
        // } 
        // else if(isset($ord)){
        //     $order = $ord;
        //     $builder->orderBy(key($order), $order[key($order)]);
        // }
        
        $query = $builder->get();
        return $query->getResult();
    }
    function count_filtered($table, $col_sch=array(), $col_ord=array(), $ord=array(), $where=array()){
        $builder = $this->db->table($table);

        $this->_get_datatables_query($table, $col_sch, $col_ord, $ord, $where);
        if(count($where) > 0){
            $builder->where($where);
        }
        return $builder->countAllResults();
    }
    public function count_all($table, $where=array()){
        $tbl_storage = $this->db->table($table);
        if(count($where) > 0){
            $tbl_storage->where($where);
        }
        return $tbl_storage->countAllResults();
    }

}
