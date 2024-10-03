<?php
defined('BASEPATH') or exit('No direct script access allowed');

class job_posting_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $query = $this->db->get('job_postings');
        return $query->result();
    }

    public function save($data, $id = null)
    {
        $array = array(
            'branch_id' => $data['branch_id'] ? $data['branch_id'] : null,
            'title' => $data['title'],
            'qualification' => $data['qualification'],
            'experience' => $data['experience'],
            'contract_type' => $data['contract_type'],
            'no_of_posts' => $data['no_of_posts'],
            'no_of_filled_posts' => $data['no_of_filled_posts'],
            'description' => $data['description'],
            'due_date' => $data['due_date'],
            'status' => $data['status'] ? $data['status'] : 'vacant',
            "created_by" => get_loggedin_user_id() ? get_loggedin_user_id() : null,
            "action_by" => $data['action_by'] ? $data['action_by'] : null,
            "created_at" => (date("Y-m-d", time()) . " " . date("H:i:s", time())),
            "updated_at" => $data['updated_at'] ? $data['updated_at'] : (date("Y-m-d", time()) . " " . date("H:i:s", time())),
            "deleted_at" => $data['deleted_at'] ? $data['deleted_at'] : null
        );

        if ($array['status'] == 'filled') {
            $array['no_of_filled_posts'] = $array['no_of_posts'];
        }

        if (!isset($id)) {
            $this->db->insert('job_postings', $array);
            $id = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('job_postings', $array);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_data($id = null)
    {
        $this->db->select('jp.*, ins.name AS branch_name, st.name AS created_by_name, st1.name AS action_by_name');
        $this->db->from('job_postings jp');
        $this->db->join('branch ins', 'jp.branch_id = ins.id', 'left');
        $this->db->join('staff st', 'jp.created_by = st.id', 'left');
        $this->db->join('staff st1', 'jp.action_by = st1.id', 'left');
        $this->db->where('jp.deleted_at', null);
        if ($id !== null) {
            $this->db->where('jp.id', $id);
        }

        $query = $this->db->get();
        if ($id !== null) {
            return $query->row_array(); // Return a single row if ID is provided
        } else {
            return $query->result_array(); // Return all rows if no ID is provided
        }
    }

    public function get_data_filter($branch_id = null, $start_date, $end_date)
    {
        $this->db->select('jp.*,ins.name AS branch_name, st.name AS created_by_name, st1.name AS action_by_name');
        $this->db->where('jp.deleted_at', null);
        $this->db->where('jp.created_at >=', $start_date);
        $this->db->where('jp.created_at <=', $end_date);
        if ($branch_id)
            $this->db->where('jp.branch_id', $branch_id);
        $this->db->from('job_postings jp');
        $this->db->join('branch ins', 'jp.branch_id = ins.id', 'left');
        $this->db->join('staff st', 'jp.created_by = st.id', 'left');
        $this->db->join('staff st1', 'jp.action_by = st1.id', 'left');
        $this->db->where('jp.deleted_at', null);
        $query = $this->db->get();
        return $query->result_array();
    }


    // public function status_change($id, $status)
    // {
    //     if (!empty($id) && ($status === 'approved' || $status === 'rejected')) {
    //         $transfer_request = $this->db->where('id', $id)->get('transfer_posting')->result_array()[0];
    //         if ($transfer_request['status'] === 'pending') {
    //             $this->db->where('id', $id);
    //             $this->db->update('transfer_posting', ['status' => $status, 'action_by' => get_loggedin_user_id(), 'updated_at' => (date("Y-m-d", time()) . " " . date("H:i:s", time()))]);
    //             if ($this->db->affected_rows() > 0) {
    //                 if ($status === 'approved') {
    //                     $response = $this->transfer_employee($transfer_request['id']);
    //                     return $response;
    //                 }
    //                 return true;
    //             }
    //         }
    //         return false;
    //     }
    //     return false;
    // }

    // public function transfer_employee($request_id)
    // {
    //     $request = $this->db->where('id', $request_id)->get('transfer_posting')->result_array()[0];
    //     // $emp = $this->db->where('id', $emp_id)->get('staff')->result_array()[0];
    //     $changes = array();
    //     if ($request['designation_id']) {
    //         $changes['designation'] = $request['designation_id'];
    //     }
    //     if ($request['new_dep_id']) {
    //         $changes['department'] = $request['new_dep_id'];
    //     }
    //     if ($request['new_branch_id']) {
    //         $changes['branch_id'] = $request['new_branch_id'];
    //     }
    //     if (count($changes) > 0) {
    //         $this->db->where('id', $request['emp_id']);
    //         $this->db->update('staff', $changes);
    //         if ($this->db->affected_rows() > 0) {
    //             return true;
    //         } else return false;
    //     }
    //     return true;
    // }
}
