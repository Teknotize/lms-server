<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class staff_promotions_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function get_data($emp_id = null)
    {

        // RAW SQL

        // SELECT 
        // 		   sp.*, 
        //         emp.name AS name,
        //         emp.staff_id AS staff_id,
        //         dep.name AS department,
        //         ps.name AS scale,
        //         emp1.name AS created_by,
        //         emp2.name AS action_by

        // FROM
        // 		   staff_promotions sp
        //         LEFT JOIN `staff` emp ON emp.id=sp.emp_id 
        //         LEFT JOIN `staff_department` dep ON dep.id=sp.dep_id 
        //         LEFT JOIN `salary_template` ps ON ps.id=sp.scale_id 
        //         LEFT JOIN `staff` emp1 ON emp1.id=sp.created_by
        //         LEFT JOIN `staff` emp2 ON emp2.id=sp.action_by;


        $this->db->select('
        sp.*, 
        emp.name AS name,
        emp.mobileno AS mobileno,
        emp.qualification AS qualification,
        emp.total_experience AS total_experience,
        emp.staff_id AS staff_id,
        emp.department AS current_department,
        emp.salary_template_id AS current_scale,
        dep.name AS department,
        ps.name AS scale,
        emp1.name AS created_by_name,
        emp2.name AS action_by_name
        ');

        $this->db->from('staff_promotions sp');
        $this->db->join('staff emp', 'emp.id = sp.emp_id', 'left');
        $this->db->join('staff_department dep', 'dep.id = sp.dep_id', 'left');
        $this->db->join('salary_template ps', 'ps.id = sp.scale_id', 'left');
        $this->db->join('staff emp1', 'emp1.id = sp.created_by', 'left');
        $this->db->join('staff emp2', 'emp2.id = sp.action_by', 'left');
        if ($emp_id != null) {
            $this->db->where('emp_id', $emp_id);
            $query = $this->db->get();
            return $query->result_array();
        }
        $query = $this->db->get();
        return $query->result_array();
    }


    public function save($data, $id = null)
    {
        $array = array(
            'emp_id' => $data['emp_id'],
            'dep_id' => $data['new_dep_id'],
            'scale_id' => $data['promotion_scale'],
            // 'rating' => $data['ratings'],
            'effective_from' => $data['effective_from'],
            'notes' => $data['notes'],
            "created_by" => get_loggedin_user_id() ? get_loggedin_user_id() : null,
            "action_by" => $data['action_by'] ? $data['action_by'] : null,
            "status" => $data['status'] ? $data['status'] : 'pending',
            "created_at" => (date("Y-m-d", time()) . " " . date("H:i:s", time())),
            "updated_at" => $data['updated_at'] ? $data['updated_at'] : (date("Y-m-d", time()) . " " . date("H:i:s", time()))
        );
        if (!isset($id)) {
            $this->db->insert('staff_promotions', $array);
            $id = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('staff_promotions', $array);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function status_change($id, $status)
    {
        if (!empty($id) && ($status === 'approved' || $status === 'rejected')) {
            $promotions_request = $this->db->where('id', $id)->get('staff_promotions')->row_array();
            if ($promotions_request['status'] === 'pending') {
                if ($status === 'approved') {
                    $response = $this->promote_employee($promotions_request['id']);
                    if ($response) return $this->status_change_db($id, $status);
                    return $response;
                } else {
                    return $this->status_change_db($id, $status);
                }
            }
            return false;
        }
        return false;
    }

    public function status_change_db($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update('staff_promotions', ['status' => $status, 'action_by' => get_loggedin_user_id(), 'updated_at' => (date("Y-m-d", time()) . " " . date("H:i:s", time()))]);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function promote_employee($request_id)
    {
        $request = $this->db->where('id', $request_id)->get('staff_promotions')->row_array();
        $changes = array();
        if ($request['dep_id']) {
            $changes['department'] = $request['dep_id'];
        }
        if ($request['scale_id']) {
            $changes['salary_template_id'] = $request['scale_id'];
        }
        if (count($changes) > 0) {
            $this->db->where('id', $request['emp_id']);
            $this->db->update('staff', $changes);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else return false;
        }
        return true;
    }
}
