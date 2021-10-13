<?php namespace App\Models;

use CodeIgniter\Model;

class GlobalModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_result($select, $table, $where = array(), $order=null, $group=null, $lastQuery=false, $wherein = array())
    {
        $builder = $this->db->table($table);
        if($select){
            $builder->select($select);
        }
        if($where){
            $builder->where($where);
        }
        if($wherein){
            $builder->whereIn($wherein['param'],$wherein['value']);
        }
        if($order){
            $builder->orderBy($order);
        }
        if($group){
            $builder->groupBy($group);
        }
        if($lastQuery == true){
            var_dump ($builder->getCompiledSelect());
            die();
        }
        $q = $builder->get();

        if ($builder->countAll() > 0) {
            return $q->getResult();
        }
        return false;
    }

    public function get_row($select, $table, $where = array(), $order=null, $group=null, $lastQuery=false)
    {
        $builder = $this->db->table($table);
        if($select){
            $builder->select($select);
        }
        if($where){
            $builder->where($where);
        }
        if($order){
            $builder->orderBy($order);
        }
        if($group){
            $builder->groupBy($group);
        }
        if($lastQuery == true){
            var_dump ($builder->getCompiledSelect());
            die();
        }
        $q = $builder->get();

        if ($builder->countAll() > 0) {
            return $q->getRow();
        }
        return false;
    }

    public function get_row_join($select, $table, $join1, $join2=false, $where = array(), $order=null, $group=null, $lastQuery=false)
    {
        $builder = $this->db->table($table);
        if($select){
            $builder->select($select);
        }
        if($where){
            $builder->where($where);
        }
        if($order){
            $builder->orderBy($order);
        }
        if($group){
            $builder->groupBy($group);
        }
        if($join1){
            $builder->join($join1['table'], $join1['on'], $join1['type']);
        }
        if($join2){
            $builder->join($join2['table'], $join2['on'], $join2['type']);
        }
        if($lastQuery == true){
            var_dump ($builder->getCompiledSelect());
            die();
        }
        $q = $builder->get();

        if ($builder->countAll() > 0) {
            return $q->getRow();
        }
        return false;
    }

    public function get_result_join($select, $table, $join=false, $where = array(), $order=null, $group=null, $lastQuery=false, $wherein = array())
    {
        $builder = $this->db->table($table);
        if($select){
            $builder->select($select);
        }
        if($where){
            $builder->where($where);
        }
        if($wherein){
            $builder->whereIn($wherein['param'],$wherein['value']);
        }
        if($order){
            $builder->orderBy($order);
        }
        if($group){
            $builder->groupBy($group);
        }
        if($join){
            foreach ($join as $key => $val) {
                $builder->join($val['table'], $val['on'], $val['type']);
            }
        }
        if($lastQuery == true){
            var_dump ($builder->getCompiledSelect());
            die();
        }
        $q = $builder->get();

        if ($builder->countAll() > 0) {
            return $q->getResult();
        }
        return false;
    }

    public function db_insert($table, $data, $lastQuery=false)
    {
        $builder = $this->db->table($table)->insert($data);
        if($lastQuery == true){
            var_dump ($this->db->getLastQuery()->getQuery());
            die();
        }
        if($builder){
            return $this->db->insertID();
        }
        return false;
    }

    public function db_insert_batch($table, $data, $lastQuery=false)
    {
        $builder = $this->db->table($table)->insertBatch($data);
        if($lastQuery == true){
            var_dump ($this->db->getLastQuery()->getQuery());
            die();
        }
        if($builder){
            return $builder;
        }
        return false;
    }

    public function db_update($table, $data, $where, $lastQuery=false)
    {
        $builder = $this->db->table($table)->update($data, $where);
        if($lastQuery == true){
            var_dump ($this->db->getLastQuery()->getQuery());
            die();
        }
        if($builder){
            return true;
        }
        return false;
    }

    public function db_update_batch($table, $data, $where, $lastQuery=false)
    {
        $builder = $this->db->table($table)->updateBatch($data, $where);
        if($lastQuery == true){
            var_dump ($this->db->getLastQuery()->getQuery());
            die();
        }
        if($builder){
            return $builder;
        }
        return false;
    }

    public function db_delete($table, $where, $lastQuery=false)
    {
        $builder = $this->db->table($table)->delete($where);
        if($lastQuery == true){
            var_dump ($this->db->getLastQuery()->getQuery());
            die();
        }
        if($builder){
            return $builder;
        }
        return false;
    }

}
