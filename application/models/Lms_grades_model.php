<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lms_grades_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function save($data = array())
    {
        $arrayLmsGrades = array(
            'grade_name' => $data['grade_name']
        );

        if (isset($data['lms_grade_id']) && !empty($data['lms_grade_id'])) {
            $this->db->where('id', $data['lms_grade_id']);
            $this->db->update('lms_grades', $arrayLmsGrades);
        } else {
            $this->db->insert('lms_grades', $arrayLmsGrades);
        }
    }
}
