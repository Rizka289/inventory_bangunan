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
   
}