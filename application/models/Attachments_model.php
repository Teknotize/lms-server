<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attachments_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save($data)
    {
        $classID = (!isset($data['all_class_set']) ? $data['class_id'] : 'unfiltered');
        $branchID = ($data['branch_id'] != 'all_branches' ? $data['branch_id'] : 'unfiltered');
        $typeID = ($data['branch_id'] != 'all_branches' ? $data['type_id'] : 'unfiltered');
        $subjectID = ($data['branch_id'] != 'all_branches' ? $data['subject_id'] : 'unfiltered');
        
        $commonClass = "";
        $commonSubject = "";
        
        if(isset($data['branch_id'])){
            if($data['branch_id'] == 'all_branches') {
                $branchID = 'unfiltered';
                $typeID = 'unfiltered';
                $classID = 'unfiltered';
                $subjectID = 'unfiltered';
                $commonClass = $data['lms_grade_id'];
                $commonSubject = $data['lms_subject_id'];
            } else {
                $branchID = $data['branch_id'];
                $typeID = $data['type_id'];
                $classID = $data['class_id'];
                $subjectID = $data['subject_id'];
            }
        } else {
            $branchID = $this->application_model->get_branch_id();
            $typeID = $data['type_id'];
        }
        
        $arrayData = array(
            'branch_id' => $branchID,
            'title' => $data['title'],
            'type_id' => $typeID,
            'remarks' => $data['remarks'],
            'date' => date("Y-m-d", strtotime($data['date'])),
            'session_id' => get_session_id(),
            'uploader_id' => get_loggedin_user_id(),
            'class_id' => $classID,
            'subject_id' => get_loggedin_user_id(),
            'lms_grade_id' => $commonClass,
            'lms_subject_id' => $commonSubject,
            'updated_at' => date("Y-m-d H:i:s"),
            'file_name' => $data['file_name'],
            'enc_name' => $data['enc_name'],
        );

        if (!isset($data['all_class_set']) && !isset($data['subject_wise'])) {
            $arrayData['subject_id'] = $data['subject_id'];
        } else {
            $arrayData['subject_id'] = 'unfiltered';
        }

        if (!isset($data['attachment_id'])) {
            $this->db->insert('attachments', $arrayData);
        } else {
            $this->db->where('id', $data['attachment_id']);
            $this->db->update('attachments', $arrayData);
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function type_save($data, $id = null)
    {
        $arrayType = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['type_name'],
        );
        if ($id == null) {
            $this->db->insert('attachments_type', $arrayType);
        } else {
            $this->db->where('id', $id);
            $this->db->update('attachments_type', $arrayType);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get attachments list
    public function getAttachmentsList()
    {
        $this->db->select('a.*, b.name as branch_name, at.name as type_name, c.name as class_name, s.name as subject_name, lgs.grade_name as lms_grade_name, lss.subject_name as lms_subject_name');
        $this->db->from('attachments as a');
        $this->db->join('attachments_type as at', 'at.id = a.type_id', 'left');
        $this->db->join('class as c', 'c.id = a.class_id', 'left');
        $this->db->join('branch as b', 'b.id = a.branch_id', 'left');
        $this->db->join('subject as s', 's.id = a.subject_id', 'left');
        $this->db->join('lms_grades as lgs', 'lgs.id = a.lms_grade_id', 'left');
        $this->db->join('lms_subjects as lss', 'lss.id = a.lms_subject_id', 'left');
        
        // Show all content if the user is superadmin
        if (!is_superadmin_loggedin()) {
            $this->db->where('a.branch_id', get_loggedin_branch_id());
            // You might need to adjust this part based on the logic of loggedin_role_id()
            if (loggedin_role_id() == 6) {
                $classID = $this->db->select('class_id')->where('student_id', get_activeChildren_id())->get('enroll')->row()->class_id;
                $this->db->where('a.class_id', $classID)->or_where('a.class_id', 'unfiltered');
            }
            if (loggedin_role_id() == 7) {
                $classID = $this->db->select('class_id')->where('student_id', get_loggedin_user_id())->get('enroll')->row()->class_id;
                $this->db->where('a.class_id', $classID)->or_where('a.class_id', 'unfiltered');
            }
            $this->db->or_where('a.branch_id', 'unfiltered');
        }
        
        $this->db->order_by('a.id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
        
        /*$this->db->select('a.*,b.name as branch_name,at.name as type_name,c.name as class_name,s.name as subject_name');
        $this->db->from('attachments as a');
        $this->db->join('attachments_type as at', 'at.id = a.type_id', 'left');
        $this->db->join('class as c', 'c.id = a.class_id', 'left');
        $this->db->join('branch as b', 'b.id = a.branch_id', 'left');
        $this->db->join('subject as s', 's.id = a.subject_id', 'left');
        if (!is_superadmin_loggedin()) {
            $this->db->where('a.branch_id', get_loggedin_branch_id());
        }
        if (loggedin_role_id() == 6) {
            $classID = $this->db->select('class_id')->where('student_id', get_activeChildren_id())->get('enroll')->row()->class_id;
            $this->db->where('class_id', $classID)->or_where('class_id', 'unfiltered');
        }
        if (loggedin_role_id() == 7) {
            $classID = $this->db->select('class_id')->where('student_id', get_loggedin_user_id())->get('enroll')->row()->class_id;
            $this->db->where('class_id', $classID)->or_where('class_id', 'unfiltered');
        }
        $this->db->or_where('a.branch_id', 'unfiltered');
        $this->db->order_by('a.id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;*/
    }
}
