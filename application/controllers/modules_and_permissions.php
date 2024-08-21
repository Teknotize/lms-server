<?php
defined('BASEPATH') or exit('No direct script access allowed');

class modules_and_permissions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('modules_model');
        $this->load->model('permissions_model');
    }

    public function modules()
    {
        if (!get_permission('add_modules', 'is_view')) {
            access_denied();
        }

        if ($this->input->post('submit') == 'save') {
            $this->form_validation->set_rules('name', translate('name'), 'required|callback_unique_name');
            $this->form_validation->set_rules('prefix', translate('prefix'), 'required|callback_unique_prefix');
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $response = $this->modules_model->save($post);
                if ($response) {
                    set_alert('success', translate('information_has_been_saved_successfully'));
                }
                redirect(base_url('modules_and_permissions/modules'));
            } else {
                $this->data['validation_error'] = true;
            }
        }
        $this->data['title'] = translate('Modules');
        $this->data['sub_page'] = 'modules_and_permissions/modules';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
    }
    public function modules_edit($id = '')
    {
        if (!get_permission('add_modules', 'is_edit')) {
            access_denied();
        }

        if ($this->input->post('submit') == 'save') {
            $this->form_validation->set_rules('name', translate('name'), 'required|callback_unique_name');
            $this->form_validation->set_rules('prefix', translate('prefix'), 'required|callback_unique_prefix');
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $response = $this->modules_model->save($post, $id);
                if ($response) {
                    set_alert('success', translate('information_has_been_saved_successfully'));
                }
                redirect(base_url('modules_and_permissions/modules'));
            } else {
                $this->data['validation_error'] = true;
            }
        }
        $this->data['data'] = $this->modules_model->getSingle('permission_modules', $id, true);
        $this->data['title'] = translate('Edit Module');
        $this->data['sub_page'] = 'modules_and_permissions/modules_edit';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
    }

    public function permission()
    {
        if (!get_permission('add_permissions', 'is_view')) {
            access_denied();
        }

        if ($this->input->post('submit') == 'save') {
            $this->form_validation->set_rules('module_id', translate('module'), 'required');
            $this->form_validation->set_rules('name', translate('name'), 'required');
            $this->form_validation->set_rules('prefix', translate('prefix'), 'required');
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $response = $this->permissions_model->save($post);
                if ($response) {
                    set_alert('success', translate('information_has_been_saved_successfully'));
                }
                redirect(base_url('modules_and_permissions/permission'));
            } else {
                $this->data['validation_error'] = true;
            }
        }
        $this->data['title'] = translate('Permissions');
        $this->data['sub_page'] = 'modules_and_permissions/permission';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
    }

    public function permission_edit($id = '')
    {
        if (!get_permission('add_permission', 'is_edit')) {
            access_denied();
        }

        if ($this->input->post('submit') == 'save') {
            $this->form_validation->set_rules('module_id', translate('module'), 'required');
            $this->form_validation->set_rules('name', translate('name'), 'required');
            $this->form_validation->set_rules('prefix', translate('prefix'), 'required');
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $response = $this->permissions_model->save($post, $id);
                if ($response) {
                    set_alert('success', translate('information_has_been_saved_successfully'));
                }
                redirect(base_url('modules_and_permissions/permission'));
            } else {
                $this->data['validation_error'] = true;
            }
        }
        $this->data['data'] = $this->permissions_model->getSingle('permission', $id, true);
        $this->data['title'] = translate('Edit Permission');
        $this->data['sub_page'] = 'modules_and_permissions/permission_edit';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
    }

    /* unique valid branch name verification is done here */
    public function unique_name($name)
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('name', $name);
        $name = $this->db->get('permission_modules')->num_rows();
        if ($name == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_name", translate('already_taken'));
            return false;
        }
    }
    public function unique_prefix($prefix)
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->db->where_not_in('id', $id);
        }
        $this->db->where('prefix', $prefix);
        $prefix = $this->db->get('permission_modules')->num_rows();
        if ($prefix == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_prefix", translate('already_taken'));
            return false;
        }
    }
}
