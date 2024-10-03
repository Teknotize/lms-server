<?php
defined('BASEPATH') or exit('No direct script access allowed');

class job_posting extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_posting_model');
        // Load any required models, libraries, etc.
    }

    public function validate_no_of_filled_posts($no_of_filled_posts)
    {
        $no_of_posts = $this->input->post('no_of_posts');

        if ($no_of_filled_posts < 0) {
            $this->form_validation->set_message('validate_no_of_filled_posts', 'The {field} field cannot be negative.');
            return FALSE;
        }

        if ($no_of_filled_posts > $no_of_posts) {
            $this->form_validation->set_message('validate_no_of_filled_posts', 'The {field} field cannot be greater than the number of posts.');
            return FALSE;
        }

        return TRUE;
    }

    protected function custom_validation_rules()
    {
        $this->form_validation->set_rules('branch_id', translate('title'), 'trim|numeric|required');
        $this->form_validation->set_rules('title', translate('title'), 'trim|required');
        $this->form_validation->set_rules('qualification', translate('qualification'), 'trim|required');
        $this->form_validation->set_rules('experience', translate('experience'), 'trim|required');
        $this->form_validation->set_rules('contract_type', translate('contract_type'), 'trim|required');
        $this->form_validation->set_rules('no_of_posts', translate('no_of_posts'), 'trim|numeric');
        $this->form_validation->set_rules('no_of_filled_posts', translate('no_of_filled_posts'), 'trim|numeric|callback_validate_no_of_filled_posts');
        $this->form_validation->set_rules('description', translate('description'), 'trim|required');
        $this->form_validation->set_rules('due_date', translate('due_date'), 'trim|required|date');
    }

    public function index()
    {
        $this->data['job_postings'] = $this->job_posting_model->get_data();
        $this->data['title'] = translate('job_posting');
        $this->data['sub_page'] = 'job_posting/index';
        $this->data['main_menu'] = 'job_posting';
        $this->load->view('layout/index', $this->data);
    }

    public function create()
    {
        if (!get_permission('job_posting', 'is_add')) {
            access_denied();
        }

        if ($_POST) {
            if (get_permission('job_posting', 'is_add')) {
                $this->custom_validation_rules();
                if ($this->form_validation->run() !== false) {
                    $response = $this->job_posting_model->save($this->input->post());
                    set_alert('success', translate('information_has_been_saved_successfully - ' . $response));
                    $array = array('status' => 'success');
                } else {
                    $error = $this->form_validation->error_array();
                    $array = array('status' => 'fail', 'error' => $error);
                }
                echo json_encode($array);
                exit();
            }
        } else {
            redirect(base_url('job_posting'));
        }
    }

    public function edit($id)
    {
        if (!get_permission('job_posting', 'is_edit')) {
            access_denied();
        }

        if ($_POST) {
            if (get_permission('job_posting', 'is_edit')) {
                $this->custom_validation_rules();
                if ($this->form_validation->run() !== false) {
                    $post_data = $this->input->post();
                    $post_data['updated_at'] = (date("Y-m-d", time()) . " " . date("H:i:s", time()));
                    $post_data['action_by'] = get_loggedin_user_id();
                    $response = $this->job_posting_model->save($this->input->post(), $id);
                    set_alert('success', translate('information_has_been_saved_successfully - ' . $response));
                    $array = array('status' => 'success');
                } else {
                    $error = $this->form_validation->error_array();
                    $array = array('status' => 'fail', 'error' => $error);
                }
                echo json_encode($array);
                exit();
            }
        }

        $this->data['job_post'] = $this->job_posting_model->get_data($id);
        $this->data['status'] = array(
            null => translate('select_status'),
            'vacant' => translate('vacant'),
            'filled' => translate('filled')
        );
        $this->data['title'] = translate('job_posting');
        $this->data['sub_page'] = 'job_posting/edit';
        $this->data['main_menu'] = 'job_posting';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id)
    {
        if ($_POST) {
            if (!get_permission('job_posting', 'is_delete')) {
                access_denied();
            }
            $this->db->where('id', $id);
            $this->db->update('job_postings', ['deleted_at' => (date("Y-m-d", time()) . " " . date("H:i:s", time())), 'action_by' => get_loggedin_user_id()]);
        }
    }

    public function vacant_filled()
    {
        if (!get_permission('job_posting', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            // dd($this->input->post());
            $post = $this->input->post();
            $this->data['job_postings'] = $this->job_posting_model->get_data_filter($post['branch_id'], $post['start_date'], $post['end_date']);
        }
        $this->data['title'] = translate('job_posting');
        $this->data['sub_page'] = 'job_posting/vacant_filled';
        $this->data['main_menu'] = 'job_posting';
        $this->load->view('layout/index', $this->data);
    }
}
