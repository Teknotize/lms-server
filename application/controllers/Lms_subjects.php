<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Branch.php
 * @copyright : Reserved RamomCoder Team
 */

class Lms_subjects extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Lms_subjects_model');
    }

    /* branch all data are prepared and stored in the database here */
    public function index()
    {
        if (is_superadmin_loggedin()) {
            if ($this->input->post('submit') == 'save') {
                $this->form_validation->set_rules('subject_name', translate('subject_name'), 'required|callback_unique_name');
                
                if ($this->form_validation->run() == true) {
                    $post = $this->input->post();
                    $response = $this->Lms_subjects_model->save($post);
                    if ($response) {
                        set_alert('success', translate('information_has_been_saved_successfully'));
                    }
                    redirect(base_url('lms_subjects'));
                } else {
                    $this->data['validation_error'] = true;
                }
            }
            $this->data['title'] = translate('LMS Subjects');
            $this->data['sub_page'] = 'lms_subjects/add';
            $this->data['main_menu'] = 'lms_subjects';
            $this->data['headerelements'] = array(
                'css' => array(
                    'vendor/dropify/css/dropify.min.css',
                ),
                'js' => array(
                    'vendor/dropify/js/dropify.min.js',
                ),
            );
            $this->load->view('layout/index', $this->data);
        } else {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
    }

    /* branch information update here */
    public function edit($id = '')
    {
        if (is_superadmin_loggedin()) {
            if ($this->input->post('submit') == 'save') {
                $this->form_validation->set_rules('subject_name', translate('subject_name'), 'required|callback_unique_name');
                if ($this->form_validation->run() == true) {
                    $post = $this->input->post();
                    $response = $this->Lms_subjects_model->save($post, $id);
                    if ($response) {
                        set_alert('success', translate('information_has_been_updated_successfully'));
                    }
                    redirect(base_url('lms_subjects'));
                }
            }
            

            $this->data['data'] = $this->Lms_subjects_model->getSingle('lms_subjects', $id, true);
            $this->data['title'] = translate('LMS Subjects');
            $this->data['sub_page'] = 'lms_subjects/edit';
            $this->data['main_menu'] = 'lms_subjects';
            $this->data['headerelements'] = array(
                'css' => array(
                    'vendor/dropify/css/dropify.min.css',
                ),
                'js' => array(
                    'vendor/dropify/js/dropify.min.js',
                ),
            );
            $this->load->view('layout/index', $this->data);
        } else {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
    }

    /* delete information */
    public function delete_data($id = '')
    {
        if (is_superadmin_loggedin()) {
            $this->db->where('id', $id);
            $this->db->delete('lms_subjects');
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    /* unique valid branch name verification is done here */
    public function unique_name($name)
    {
        $lms_subject_id = $this->input->post('lms_subject_id');
        if (!empty($lms_subject_id)) {
            $this->db->where_not_in('id', $lms_subject_id);
        }
        $this->db->where('subject_name', $name);
        $name = $this->db->get('lms_subjects')->num_rows();
        if ($name == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_name", translate('already_taken'));
            return false;
        }
    }
}
