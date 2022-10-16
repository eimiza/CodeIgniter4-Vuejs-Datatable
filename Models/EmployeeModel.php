<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'employee';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function datatables($limit, $offset, $filter){
        $this->db = \Config\Database::connect();
        $db = $this->db->table('employee');
        if($filter['name']){$db->like('name', $filter['name']);};
        $db->where('deleted_at is null'); //if using soft delete
        $db->limit($limit, $offset);
        $data = $db->get();
        if($data){
            return $data->getResultArray();
        }else{
            return null;
        }
    }

    public function datacount($filter){
        $db = $this->db->table('employee');
        if($filter['name']){$db->like('name', $filter['name']);};
        $db->where('deleted_at is null'); //if using soft delete
        $count = $db->countAllResults();
        if($count){
            return $count;
        }else{
            return null;
        }
    }


}
