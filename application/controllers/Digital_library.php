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

class Digital_library extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Lms_grades_model');
    }

    public function branch($branch_id)
    {
        //echo $grade_id; exit;

        $this->data['title'] = translate('LMS Library');
        $this->data['branch_id'] = $branch_id;
        $this->data['sub_page'] = 'digital_library/bnch';
        $this->data['main_menu'] = 'digital_library';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    /* branch all data are prepared and stored in the database here */
    public function grade($branch_id, $grade_id)
    {
        //echo $grade_id; exit;

        $this->data['title'] = translate('LMS Library');
        $this->data['branch_id'] = $branch_id;
        $this->data['grade_id'] = $grade_id;
        $this->data['sub_page'] = 'digital_library/add';
        $this->data['main_menu'] = 'digital_library';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function subject($branch_id, $grade_id, $subject_id, $page=1){

        $this->load->library('pagination');

        $attachments_per_page = 5;

        $offset = ($page - 1) * $attachments_per_page;

        $attachments = $this->getAttachmentsWithPagination($grade_id, $subject_id, $attachments_per_page, $offset);
        $resources = $this->getAttachmentsResources($grade_id, $subject_id);

        $this->data['attachments'] = $attachments;
        $this->data['resources'] = $resources;

        // Configure pagination
        $config['base_url'] = base_url("digital_library/subject/$branch_id/$grade_id/$subject_id");     

        $total_rows =  $this->countAttachments($grade_id, $subject_id);

        $config['total_rows'] = $total_rows;

        $config['per_page'] = $attachments_per_page;

        $config['use_page_numbers'] = true;

        $this->pagination->initialize($config);

        $pagination_links = [];

        $this->data['current_page'] = $page;

        // Create custom pagination links array
        for ($i = 1; $i <= ceil($total_rows / $attachments_per_page); $i++) {
            $pagination_links[$i]['page'] = $i;
            $pagination_links[$i]['link'] = base_url("digital_library/subject/$branch_id/$grade_id/$subject_id/$i");
        }
        // echo "<pre>";
        // print_r($pagination_links);
        // echo "</pre>";
        // exit;

        $this->data['pagination_links'] = $pagination_links;

        //$this->data['pagination_links'] = $this->pagination->create_links();
        // Pass the pagination links to the view

        //var_dump($this->data['pagination_links']); exit;

        $this->data['title'] = translate('LMS Library');
        $this->data['branch_id'] = $branch_id;
        $this->data['grade_id'] = $grade_id;
        $this->data['subject_id'] = $subject_id;
        $this->data['sub_page'] = 'digital_library/sub';
        $this->data['main_menu'] = 'digital_library';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function getAttachmentsWithPagination($grade_id, $subject_id, $limit, $offset)
    {
        $this->db->select('sub.name AS subject_name, at.id AS type_id, at.name AS type_name, a.*');
        $this->db->from('subject AS sub');
        $this->db->join('attachments AS a', 'sub.id = a.subject_id', 'inner');
        $this->db->join('attachments_type AS at', 'at.id = a.type_id', 'inner');
        $this->db->where('a.class_id', $grade_id);
        $this->db->where('a.subject_id', $subject_id);
        $this->db->where('at.name', 'video');
        $this->db->group_by('a.id');
        $this->db->limit($limit, $offset); // Add limit and offset for pagination

        return $this->db->get()->result();
    }

    public function getAttachmentsResources($grade_id, $subject_id)
    {
        $this->db->select('sub.name AS subject_name, at.id AS type_id, at.name AS type_name, a.*');
        $this->db->from('subject AS sub');
        $this->db->join('attachments AS a', 'sub.id = a.subject_id', 'inner');
        $this->db->join('attachments_type AS at', 'at.id = a.type_id', 'inner');
        $this->db->where('a.class_id', $grade_id);
        $this->db->where('a.subject_id', $subject_id);
        $this->db->where('at.name <>', 'video');
        $this->db->group_by('a.id'); // Add limit and offset for pagination

        return $this->db->get()->result();
    }

    public function countAttachments($grade_id, $subject_id)
    {
        $this->db->select('sub.name AS subject_name, at.id AS attachment_type_id, at.name AS type_name, a.*');
        $this->db->from('subject AS sub');
        $this->db->join('attachments AS a', 'sub.id = a.subject_id', 'inner');
        $this->db->join('attachments_type AS at', 'at.id = a.type_id', 'inner');
        $this->db->where('a.class_id', $grade_id);
        $this->db->where('a.subject_id', $subject_id);
        $this->db->where('at.name', 'video');
        $this->db->group_by('a.id');

        return $this->db->count_all_results();
    }

    /* branch information update here */
    public function edit($id = '')
    {
        if (is_superadmin_loggedin()) {
            if ($this->input->post('submit') == 'save') {
                $this->form_validation->set_rules('grade_name', translate('grade_name'), 'required|callback_unique_name');
                if ($this->form_validation->run() == true) {
                    $post = $this->input->post();
                    $response = $this->Lms_grades_model->save($post, $id);
                    if ($response) {
                        set_alert('success', translate('information_has_been_updated_successfully'));
                    }
                    redirect(base_url('lms_grades'));
                }
            }

            $this->data['data'] = $this->Lms_grades_model->getSingle('lms_grades', $id, true);
            $this->data['title'] = translate('LMS Grades');
            $this->data['sub_page'] = 'lms_grades/edit';
            $this->data['main_menu'] = 'lms_grades';
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
            $this->db->delete('lms_grades');
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    /* unique valid branch name verification is done here */
    public function unique_name($name)
    {
        $lms_grade_id = $this->input->post('lms_grade_id');
        if (!empty($lms_grade_id)) {
            $this->db->where_not_in('id', $lms_grade_id);
        }
        $this->db->where('grade_name', $name);
        $name = $this->db->get('lms_grades')->num_rows();
        if ($name == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_name", translate('already_taken'));
            return false;
        }
    }
}
