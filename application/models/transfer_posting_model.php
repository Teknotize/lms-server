<?php
defined('BASEPATH') or exit('No direct script access allowed');

class transfer_posting_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_data()
    {
        $query = $this->db->get('transfer_posting');
        return $query->result();
    }

    public function save($data, $id = null)
    {
        $array = array(
            "emp_id" => $data['emp_id'],
            "designation_id" => $data['designation_id'],
            "current_dep_id" => $data['current_dep_id'],
            "new_dep_id" => $data['new_dep_id'],
            "current_branch_id" => $data['current_branch_id'],
            "new_branch_id" => $data['new_branch_id'],
            "effective_from" => $data['effective_from'],
            "notes" => $data['notes'],
            "created_by" => get_loggedin_user_id() ? get_loggedin_user_id() : null,
            "action_by" => $data['action_by'] ? $data['action_by'] : null,
            "status" => $data['status'] ? $data['status'] : 'pending',
            "created_at" => (date("Y-m-d", time()) . " " . date("H:i:s", time())),
            "updated_at" => $data['updated_at'] ? $data['updated_at'] : (date("Y-m-d", time()) . " " . date("H:i:s", time()))
        );

        if (!isset($id)) {
            $this->db->insert('transfer_posting', $array);
            $id = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('transfer_posting', $array);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_transfer_request($id = null)
    {
        $this->db->select('
        tp.id,
        tp.designation_id,
        des.name AS designation,
        tp.new_dep_id,
        dep.name AS department,
        tp.new_branch_id,
        ins.name AS branch,
        tp.effective_from,
        tp.notes,
        tp.created_by,
        st.name AS created_by_name,
        tp.action_by,
        st1.name AS action_by_name,
        tp.status
    ');
        $this->db->from('transfer_posting tp');
        $this->db->join('staff_designation des', 'tp.designation_id = des.id', 'left');
        $this->db->join('staff_department dep', 'tp.new_dep_id = dep.id', 'left');
        $this->db->join('branch ins', 'tp.new_branch_id = ins.id', 'left');
        $this->db->join('staff st', 'tp.created_by = st.id', 'left');
        $this->db->join('staff st1', 'tp.action_by = st1.id', 'left');
        if ($id) {
            $this->db->where('tp.emp_id', $id);
        }
        $this->db->where('tp.deleted_at', null);
        $query = $this->db->get();
        return $query->result_array();
    }
}
