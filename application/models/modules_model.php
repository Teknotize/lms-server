<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class modules_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save($data, $id = null)
    {
        $array = array(
            'name' => $data['name'],
            'prefix' => $data['prefix'],
            'system' => 0,
            'sorted' => 28,
            'in_module' => 0,
        );
        if (!isset($id)) {
            $this->db->insert('permission_modules', $array);
            $id = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('permission_modules', $array);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
