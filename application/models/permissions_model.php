<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class permissions_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save($data, $id = null)
    {
        $array = array(
            'module_id' => $data['module_id'],
            'name' => $data['name'],
            'prefix' => $data['prefix'],
            'show_view' => $data['show_view'],
            'show_add' => $data['show_add'],
            'show_edit' => $data['show_edit'],
            'show_delete' => $data['show_delete']
        );
        if (!isset($id)) {
            $this->db->insert('permission', $array);
            $id = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('permission', $array);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
