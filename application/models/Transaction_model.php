<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model
{
    function tnx_save($data){
        if($this->db->insert('td_transaction', $data)){
            return true;
        }else{
            return false;
        }
    }
}