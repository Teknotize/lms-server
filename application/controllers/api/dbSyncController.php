<?php
defined('BASEPATH') or exit('No direct script access allowed');

class dbSyncController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('transfer_posting_model');
    }

    public function insertUpdateData($table_name, $data, $branch_id)
    {
        $response = array();
        foreach ($data as $object) {
            $toUpdate = False;

            if ($object->master_id)
                $toUpdate = True;

            if ($object->branch_id)
                $object->branch_id = $branch_id;

            $id = $object->id;
            $master_id = $object->master_id ? $object->master_id : null;
            unset($object->id, $object->master_id, $object->log_id, $object->is_sync);

            if ($toUpdate) {
                $this->db->where('id', $master_id);
                $this->db->update($table_name, $object);
                $response[$id] = $master_id;
            } else {
                $this->db->insert($table_name, $object);
                $response[$id] = $this->db->insert_id();
            }
        }
        return $response;
    }

    public function checkPostRequest()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(405) // Method Not Allowed
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Invalid request method')));
            return false;
        }
        return true;
    }

    public function checkJsonData($data)
    {
        $data = json_decode($data);
        if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
            $this->JsonInvalidError();
        }
        return $data;
    }

    public function JsonInvalidError()
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(400) // Bad Request
            ->set_output(json_encode(array('status' => 'error', 'message' => 'Invalid JSON')));
        exit();
    }

    public function deleteTableData()
    {
        if ($this->checkPostRequest()) {
            $data = $this->checkJsonData($this->input->raw_input_stream);
            $response = array();
            foreach ($data as $object) {
                $record = $this->db->get_where($object->source, array('id' => $object->master_id));
                if ($record->num_rows() > 0) {
                    $this->db->where('id', $object->master_id);
                    if ($this->db->delete($object->source))
                        $response[$object->id] = true;
                    else
                        $response[$object->id] = false;
                } else {
                    $response[$object->id] = 'Record not found';
                }
            }
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }

    public function checkStaffIDsForTransfernPosting($staff)
    {
        // also add else -> upsert function for emp that are new and haven't been transfered
        foreach ($staff as $item) {
            $response = array();
            $staff_id = $item->staff_id;
            $branch_id = $item->branch_id;
            $emp = $this->db->select(['id', 'branch_id'])->where('staff_id', $staff_id)->limit(1)->get('staff')->row_array();
            // print_r($emp);
            // exit();
            $emp_id = $emp['id'];
            $emp_branch_id = $emp['branch_id'];
            if ($emp_id && ($emp_branch_id === $branch_id)) {
                $transfer_id = $this->db->where('emp_id', $emp_id)->where('status', 'approved')->order_by('created_at', 'DESC')->limit(1)->get('transfer_posting')->row_array();
                $res = $this->transfer_posting_model->transfer_employee($transfer_id);
                if ($res)
                    $response[$item->id] = $emp_id;
            }
        }
        exit;
    }

    public function handleTableUpsert($table_name, $id = null)
    {
        // $this->output
        //     ->set_content_type('application/json')
        //     ->set_status_header(200)
        //     ->set_output(json_encode($table_name . " - " . $id));
        if ($this->checkPostRequest()) {
            $data = $this->checkJsonData($this->input->raw_input_stream);
            $response = null;
            if ($table_name === 'staff')
                $response = $this->checkStaffIDsForTransfernPosting($data);
            else
                $response = $this->insertUpdateData($table_name, $data, $id);
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }

    // public function studentTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('student', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function classTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('class', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function sectionTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('section', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function parentsTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('parent', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function sectionsAllocationTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('sections_allocation', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function enrollTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('enroll', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function studentAttendanceTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('student_attendance', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function homeworkTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('homework', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function homeworkEvaluationTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('homework_evaluation', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function homeworkSubmitTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('homework_submit', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function subjectTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('subject', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }

    // public function subjectAssignTable()
    // {
    //     if ($this->checkPostRequest()) {
    //         $data = $this->checkJsonData($this->input->raw_input_stream);
    //         $response = $this->insertUpdateData('subject_assign', $data);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($response));
    //     }
    // }
}
