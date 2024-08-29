<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promotions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load any required models, libraries, etc.
        $this->load->model('staff_promotions_model');
    }

    protected function custom_validation_rules()
    {
        $this->form_validation->set_rules('emp_id', translate('emp_id'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('new_dep_id', translate('new_dep_id'), 'trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('promotion_scale', translate('promotion_scale'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('ratings', translate('ratings'), 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('effective_from', translate('effective_from'), 'trim|required|date|callback_date_greater_than_today');
        $this->form_validation->set_rules('notes', translate('notes'), 'trim|max_length[500]');
    }

    function date_greater_than_today($date)
    {
        $today = date('Y-m-d');
        if ($date <= $today) {
            $this->form_validation->set_message('date_greater_than_today', 'The Effective From date must be a date greater than today.');
            return false;
        }
        return true;
    }

    public function index()
    {
        $this->data['promotions'] = $this->staff_promotions_model->get_data();
        // echo ('<pre>');
        // print_r($this->data['promotions']);
        // echo ('</pre>');
        // exit;


        $this->data['title'] = translate('promotions');
        $this->data['sub_page'] = 'promotions/index';
        $this->data['main_menu'] = 'promotions';
        // $this->data['headerelements'] = array(
        //     'js' => array(
        //         'js/transfer_posting.js',
        //     ),
        // );
        $this->load->view('layout/index', $this->data);
    }

    public function create()
    {
        if (!get_permission('promotions', 'is_add')) {
            ajax_access_denied();
        }
        $this->custom_validation_rules();
        if ($this->form_validation->run() == False) {
            $error = $this->form_validation->error_array();
            echo json_encode(array('status' => 'fail', 'error' => $error));
            return;
        } else {
            $post = $this->input->post();
            $response = $this->staff_promotions_model->save($post);
            if ($response) {
                set_alert('success', translate('information_has_been_saved_successfully'));
                $this->session->set_flashdata('promotions_tab', 1);
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => 'Something went wrong. Please try again.'));
            }
        }
    }

    public function edit($id)
    {

        if (!get_permission('promotions', 'is_edit')) {
            access_denied();
        }

        if ($this->input->post('submit') == 'update') {
            $this->custom_validation_rules();
            if ($this->form_validation->run() == False) {
                $this->form_validation->error_array();
                set_alert('error', 'Something went wrong. Please try againasd.');
            } else {
                $post = $this->input->post();
                $post['action_by'] = get_loggedin_user_id();
                $post['updated_at'] = (date("Y-m-d", time()) . " " . date("H:i:s", time()));
                $response = $this->staff_promotions_model->save($post, $id);
                if ($response) {
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    redirect(base_url('promotions/edit/' . $id));
                } else {
                    set_alert('error', 'Something went wrong. Please try again.');
                }
            }
        }

        $this->db->select('emp_id');
        $this->db->from('staff_promotions');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $emp_id = $query->row_array()['emp_id'];
        $staff = $this->db->select('*')->where('id', $emp_id)->get('staff')->row_array();

        $this->data['promotions'] = $this->staff_promotions_model->get_data($emp_id)[0];

        $this->data['departments'] = $this->app_lib->getDepartment($staff['department']);

        $this->data['payscales'] = $this->app_lib->getSelectList('salary_template');

        $this->data['ratings'] = array(
            null => translate('select_rating'),
            1 => translate('1_star'),
            2 => translate('2_star'),
            3 => translate('3_star'),
            4 => translate('4_star'),
            5 => translate('5_star'),
        );


        $this->data['title'] = translate('promotions');
        $this->data['sub_page'] = 'promotions/edit';
        $this->data['main_menu'] = 'promotions';
        $this->load->view('layout/index', $this->data);
    }



    public function approve_reject($id, $status)
    {
        $response = array('status' => 'failed');

        if ($status === 'approved' || $status === 'rejected') {
            $db_response = $this->staff_promotions_model->status_change($id, $status);
            if ($db_response) $response['status'] = 'success';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
