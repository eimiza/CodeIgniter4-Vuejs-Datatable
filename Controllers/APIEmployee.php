<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Secure;

class APIEmployee extends ResourceController
{
    
    public function __construct(){
        $this->mod = model('App\Models\EmployeeModel');
        $this->sec = new Secure();
    }

    public function index()
    {
        //
    }

    public function listing(){
        helper('function'); //to use pagingOffSet and pagingTotalPage
  
        $page = $this->request->getPost('page');
        $filter['name'] = $this->request->getPost('search');

        $limit = 10;
        $data = $this->mod->datatables($limit, pagingOffset($page, $limit), $filter);
        $data = array_map(function ($arr){
            $arr['secure_id'] = $this->sec->enc_session($arr['id']);
            return $arr;
        },$data);

        $data_count =  $this->mod->datacount($filter);
        $page = (int)$page;
        $page_count = pagingTotalPage($data_count, $limit);

        $data = [
            "page" => $page,
            "per_page" => $limit,
            "total_page" => $page_count,
            "total_data" => $data_count,
            "data" => $data,
        ];

        return $this->respond($data);
    }

    public function insert_data(){
        $validation = \Config\Services::validation();
        $result = ['status' => 'success', 'errors'=> ''];
        if(!$this->validate([
            'name' => 'required'
        ])){
            //$errors = $validation->listErrors();
            if ($validation->hasError('name')) {$errors['name'] = $validation->getError('name');}
            $result = ['status' => 'error', 'errors'=> $errors];
            return $this->respond($result);
        }else{
            $data = [
                'name' => $this->request->getPost('name'),
            ];
            $this->mod->insert($data);
            return $this->respond($result);
        };

    }

    public function update_data($id){
        $validation = \Config\Services::validation();
        $result = ['status' => 'success', 'errors'=> ''];
        if(!$this->validate([
            'name' => 'required'
        ])){
            //$errors = $validation->listErrors();
            if ($validation->hasError('name')) {$errors['name'] = $validation->getError('name');}
            $result = ['status' => 'error', 'errors'=> $errors];
            return $this->respond($result);
        }else{
            $data = [
                'name' => $this->request->getPost('name'),
            ];
            $this->mod->update($id,$data);
            return $this->respond($result);
        };

    }

    public function delete_data($id){
        $this->mod->delete($id);
    }
}
