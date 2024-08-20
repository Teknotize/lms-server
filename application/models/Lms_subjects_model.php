<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lms_subjects_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function save($data = array())
    {
        $arrayLmsSubjects = array(
            'subject_name' => $data['subject_name']
        );

        if (isset($data['lms_subject_id']) && !empty($data['lms_subject_id'])) {
            $this->db->where('id', $data['lms_subject_id']);
            $this->db->update('lms_subjects', $arrayLmsSubjects);
        } else {
            $this->db->insert('lms_subjects', $arrayLmsSubjects);
        }
    }
}
