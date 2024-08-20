<?php
defined('BASEPATH') or exit('No direct script access allowed');

class dbSyncController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insertUpdateData($table_name, $data)
    {
        $response = array();
        foreach ($data as $object) {
            $toUpdate = False;

            if ($object->master_id)
                $toUpdate = True;

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

    public function handleTableUpsert($table_name)
    {
        if ($this->checkPostRequest()) {
            // echo $table_name;
            // exit();
            $data = $this->checkJsonData($this->input->raw_input_stream);
            $response = $this->insertUpdateData($table_name, $data);
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
