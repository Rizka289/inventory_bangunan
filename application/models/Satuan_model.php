<?php
defined('BASEPATH') or exit('No direct script accesss allowed');

class Satuan_model extends CI_Model
{
    public function getAll()
    {
        $this->db->order_by('id_uom', 'DESC');
        $query = $this->db->get('uom');
        return $query->result();
    }

    function add(){
        try {
            $this->db->insert('uom', ['uom' => $_POST['satuan']]);
        } catch (\Throwable $th) {
            return false;
        }
        return true;

    }

    function delete($ids){
        try {
            $this->db->where_in('id_uom', $ids)->delete('uom');
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }       

    function update($data){
        try {
            $this->db->where('id_uom', $data['id'])
                ->update('uom', ['uom' => $data['satuan']]);
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
   
}