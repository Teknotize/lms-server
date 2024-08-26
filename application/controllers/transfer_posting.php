<?php
defined('BASEPATH') or exit('No direct script access allowed');

class transfer_posting extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('transfer_posting_model');
        $this->load->library('form_validation');
        // Load any models, libraries, etc, here
    }


    protected function custom_validation_rules()
    {
        $this->form_validation->set_rules('emp_id', translate('emp_id'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('current_branch_id', translate('current_branch_id'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('new_branch_id', translate('new_branch_id'), 'trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('designation_id', translate('designation_id'), 'trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('current_dep_id', translate('current_dep_id'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('new_dep_id', translate('new_dep_id'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('effective_from', translate('effective_from'), 'trim|required|date');
        $this->form_validation->set_rules('notes', translate('notes'), 'trim|max_length[500]');
    }


    public function index()
    {
        $this->data['transfer_posting_requests'] = $this->transfer_posting_model->get_transfer_request();
        // echo ('<pre>');
        // print_r($this->data['requests']);
        // echo ('</pre>');
        // exit;


        $this->data['title'] = translate('transfer_posting');
        $this->data['sub_page'] = 'transfer_posting/index';
        $this->data['main_menu'] = 'award';
        $this->data['headerelements'] = array(
            'js' => array(
                'js/transfer_posting.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function create()
    {
        if (!get_permission('transfer_posting', 'is_add')) {
            ajax_access_denied();
        }
        $this->custom_validation_rules();
        if ($this->form_validation->run() == False) {
            $error = $this->form_validation->error_array();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        } else {
            $post = $this->input->post();
            $this->transfer_posting_model->save($post);
            set_alert('success', translate('information_has_been_saved_successfully'));
            $this->session->set_flashdata('transfer_posting_tab', 1);
            echo json_encode(array('status' => 'success'));
        }
    }

    public function edit($id)
    {
        if (!get_permission('transfer_posting', 'is_edit')) {
            access_denied();
        }

        if ($this->input->post('submit') == 'update') {

            $this->custom_validation_rules();
            if ($this->form_validation->run() == true) {
                $this->transfer_posting_model->save($this->input->post(), $id);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('transfer_posting/edit/' . $id));
            }
        }


        $this->data['request'] = $this->db->where('id', $id)->get('transfer_posting')->result_array()[0];
        $this->data['institutes'] = $this->app_lib->getSelectList('branch');
        $this->data['req_user'] = $this->db->where('id', $this->data['request']['emp_id'])->get('staff')->result_array()[0];

        $department_id = $this->data['request']['new_branch_id'] ? $this->data['request']['new_branch_id'] : $this->data['request']['current_branch_id'];
        $this->data['departments'] = $this->app_lib->getDepartment($department_id);




        $this->data['title'] = translate('transfer_posting');
        $this->data['sub_page'] = 'transfer_posting/edit';
        $this->data['main_menu'] = 'award';
        $this->data['headerelements'] = array(
            'js' => array(
                'js/transfer_posting.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id)
    {
        if (!get_permission('transfer_posting', 'is_delete')) {
            access_denied();
        }
        $this->db->where('id', $id);
        $this->db->update('transfer_posting', ['deleted_at' => date("Y-m-d H:i:s")]);
    }

    public function approve_reject($id, $status)
    {
        $response = array('status' => 'failed');

        if ($status === 'approved' || $status === 'rejected') {
            $db_response = $this->transfer_posting_model->status_change($id, $status);
            if ($db_response) $response['status'] = 'success';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
