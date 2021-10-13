<?php namespace App\Models;

use CodeIgniter\Model;

class MyModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function get_laporan($param, $session)
    {
        $start = $param['start_date'] ?? date('Y-m-d');
        $end_ = $param['end_date'] ?? date('Y-m-d');
        $end = date('Y-m-d',strtotime($end_ . "+1 days"));

        $builder = $this->db->table('d_sale');
        $builder->select('created_at, d_sale.nis, buyer_name, d_user.name as kasir, grand_total');
        if(in_array($session->group_id, ['4'])){
            if($param['store_id'] != 'all'){
                $builder->where('d_sale.store_id', $param['store_id']);
            }
        } elseif(in_array($session->group_id, ['6'])){
            if($param['kasir_id'] != 'all'){
                $builder->where('d_sale.created_by', $param['kasir_id']);
            }
            $builder->where('d_sale.store_id', $session->store_id);
        } else {
            $builder->where('d_sale.created_by', $session->id);
        }

        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $end);
        $builder->join('d_user', 'd_sale.created_by = d_user.id', 'left');
        $q = $builder->get();

        if($q){
            return $q->getResult();
        }
        return false;
    }

    public function get_laporan_akun($param, $session)
    {
        $start = $param['start_date'] ?? date('Y-m-d');
        $end_ = $param['end_date'] ?? date('Y-m-d');
        $end = date('Y-m-d',strtotime($end_ . "+1 days"));

        $builder = $this->db->table('d_akun_transaction');
        $builder->select("d_akun_transaction.created_at, 
            d_topup_transaction.invoice_no, 
            d_topup_transaction.nis, 
            d_topup_transaction.name as buyer_name, 
            d_akun_transaction.total as grand_total, 
            CONCAT('(', d_akun_transaction.bulan, ' Bulan) (',d_akun_transaction.nis,'-',d_akun_transaction.name,')', d_topup_transaction.note) note, 
            d_user.name as kasir");
        if(!is_null($session->group_akun_id)){
            $builder->where('d_akun_transaction.akun_id', $session->group_akun_id);
        }

        $builder->where('d_akun_transaction.created_at >=', $start);
        $builder->where('d_akun_transaction.created_at <=', $end);
        $builder->join('d_akun', 'd_akun.id = d_akun_transaction.akun_id', 'left');
        $builder->join('d_user', 'd_user.id = d_akun_transaction.created_by', 'left');
        $builder->join('d_topup_transaction', 'd_topup_transaction.uuid = d_akun_transaction.topup_uuid', 'left');
        $q = $builder->get();

        if($q){
            return $q->getResult();
        }
        return false;
    }

    public function get_klaim($param, $session)
    {
        
        $start = $param['start_date'] ?? date('Y-m-d');
        $end_ = $param['end_date'] ?? date('Y-m-d');
        $end = date('Y-m-d',strtotime($end_ . "+1 days"));

        $builder = $this->db->table('d_sale');
        $builder->select('d_sale.id, grand_total, status_klaim');
        if(in_array($session->group_id, ['4'])){
            if($param['store_id'] != 'all'){
                $builder->where('d_sale.store_id', $param['store_id']);
            }
        } elseif(in_array($session->group_id, ['6'])){
            if($param['kasir_id'] != 'all'){
                $builder->where('d_sale.created_by', $param['kasir_id']);
            }
            $builder->where('d_sale.store_id', $session->store_id);
        } else {
            $builder->where('d_sale.created_by', $session->id);
        }

        $builder->where('d_sale.status_klaim', null);
        $builder->where('d_sale.created_at >=', $start);
        $builder->where('d_sale.created_at <=', $end);
        $builder->join('d_user', 'd_sale.created_by = d_user.id', 'left');
        $q = $builder->get();

        if($q){
            return $q->getResult();
        }
        return false;
    }

    public function get_klaim_akun($param, $session)
    {
        $start = $param['start_date'] ?? date('Y-m-d');
        $end_ = $param['end_date'] ?? date('Y-m-d');
        $end = date('Y-m-d',strtotime($end_ . "+1 days"));

        $builder = $this->db->table('d_akun_transaction');
        $builder->select('d_akun_transaction.id, d_akun_transaction.total, klaim');
        if(in_array($session->group_id, ['4'])){
            if($param['akun_id'] != 'all'){
                $builder->where('d_akun_transaction.akun_id', $param['akun_id']);
            }
        }
        $builder->where('d_akun_transaction.klaim', null);
        $builder->where('created_at >=', $start);
        $builder->where('created_at <=', $end);
        $q = $builder->get();
        if($q){
            return $q->getResult();
        }
        return false;
    }

    public function get_reff($type=null)
    {
        $now = date('Y-m-d');
        
        if($type=='topup'){
            $pref = 'TOP-';
        } elseif ($type=='sale') {
            $pref = 'SALE-';
        } elseif ($type=='tabungan') {
            $pref = 'TAB-';
        } elseif ($type=='klaim') {
            $pref = 'KL-';
        } elseif ($type=='belanja') {
            $pref = 'BLJ-';
        } elseif ($type=='efulus') {
            $pref = 'EF-';
        } elseif ($type=='keuangan') {
            $pref = 'KEU-';
        }

        $builder = $this->db->table('m_invoice');
        $builder->select('*');
        $builder->where('date', $now);
        $builder->where('type', $type);
        $q = $builder->get();

        if($q->resultID->num_rows > 0){
            if($pref == 'KL-'){
                $sql = "SELECT LPAD(num,2,'0') as `num` FROM `m_invoice` WHERE `date` = ? AND type = ? ";
            } else {
                $sql = "SELECT LPAD(num,7,'0') as `num` FROM `m_invoice` WHERE `date` = ? AND type = ? ";
            }
            $query = $this->db->query($sql,[$now, $type]);
            $row = $query->getRow();
            $data = $q->getRow();
            $inv = @$data->prefix.date('ymd').@$row->num;
        } else {
            $num = '1';
            $ins = [
                'date'       => $now,
                'prefix'     => $pref,
                'num'        => $num,
                'type'       => $type
            ];
            $builder = $this->db->table('m_invoice')->insert($ins);

            if($pref == 'KL-'){
                $sql = "SELECT LPAD(num,2,'0') as `num` FROM `m_invoice` WHERE `date` = ? AND type = ? ";
            } else {
                $sql = "SELECT LPAD(num,7,'0') as `num` FROM `m_invoice` WHERE `date` = ? AND type = ? ";
            }
            $query = $this->db->query($sql,[$now, $type]);
            $row = $query->getRow();
            $inv = $pref.date('ymd').@$row->num;
        }
        return $inv;
    }

    public function update_reff($type=null)
    {
        if($type==null){
            $type = 'efulus';
        }
        $now = date('Y-m-d');
        $builder = $this->db->table('m_invoice');
        $builder->select('*');
        $builder->where('date', $now);
        $builder->where('type', $type);
        $q = $builder->get();

        if($q->resultID->num_rows > 0){
            $where = ['date'=>$now, 'type'=>$type];
            $up = [
                'num'        => @$q->getRow()->num + 1,
            ];
            $builder = $this->db->table('m_invoice')->update($up, $where);
            if($builder){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_message_notif($type=null)
    {
        $builder = $this->db->table('d_notifikasi');
        $builder->select('*');
        $builder->where('type', $type);
        $notif = $builder->get();
        if(!$notif){
            return false;
        }
        return $notif;
    }

}
