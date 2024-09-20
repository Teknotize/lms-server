<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Performance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('job_posting_model');
        // Load any required models, libraries, etc.
        $this->load->model('employee_model');
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
        // $this->data['performance'] = $this->employee_model->get_data(); 
        // $this->data['job_postings'] = $this->job_posting_model->get_data();
        $this->data['title'] = translate('performance');
        $this->data['sub_page'] = 'performance/index';
        $this->data['main_menu'] = 'performance';
       
        $this->load->view('layout/index', $this->data);
    }

    
 
}
