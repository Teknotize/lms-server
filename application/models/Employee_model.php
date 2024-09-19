<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Employee_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // moderator employee all information
    public function save($data, $role = null, $id = null)
    {
        $inser_data1 = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['name'],
            'sex' => $data['sex'],
            'religion' => $data['religion'],
            'blood_group' => $data['blood_group'],
            'birthday' => $data["birthday"],
            'mobileno' => $data['mobile_no'],
            'present_address' => $data['present_address'],
            'permanent_address' => $data['permanent_address'],
            'photo' => $this->uploadImage('staff'),
            'designation' => $data['designation_id'],
            'department' => $data['department_id'],
            'joining_date' => date("Y-m-d", strtotime($data['joining_date'])),
            'qualification' => $data['qualification'],
            'experience_details' => $data['experience_details'],
            'total_experience' => $data['total_experience'],
            'email' => $data['email'],
            'facebook_url' => $data['facebook'],
            'linkedin_url' => $data['linkedin'],
            'twitter_url' => $data['twitter'],
        );

        $inser_data2 = array(
            'username' => $data["username"],
            'role' => $data["user_role"],
        );

        if (!isset($data['staff_id']) && empty($data['staff_id'])) {
            // RANDOM STAFF ID GENERATE
            $inser_data1['staff_id'] = substr(app_generate_hash(), 3, 7);
            // SAVE EMPLOYEE INFORMATION IN THE DATABASE
            $this->db->insert('staff', $inser_data1);
            $employeeID = $this->db->insert_id();

            // SAVE EMPLOYEE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
            $inser_data2['active'] = 1;
            $inser_data2['user_id'] = $employeeID;
            $inser_data2['password'] = $this->app_lib->pass_hashed($data["password"]);
            $this->db->insert('login_credential', $inser_data2);

            // SAVE USER BANK INFORMATION IN THE DATABASE
            if (!isset($data['chkskipped'])) {
                $data['staff_id'] = $employeeID;
                $this->bankSave($data);
            }
            return $employeeID;
        } else {
            $inser_data1['staff_id'] = $data['staff_id_no'];
            // UPDATE ALL INFORMATION IN THE DATABASE
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $data['staff_id']);
            $this->db->update('staff', $inser_data1);
            // UPDATE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
            $this->db->where('user_id', $data['staff_id']);
            $this->db->where_not_in('role', array(6, 7));
            $this->db->update('login_credential', $inser_data2);
        }
    }


    // GET SINGLE EMPLOYEE DETAILS
    public function getSingleStaff($id = '')
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id,login_credential.active,login_credential.username, roles.name as role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('staff.id', $id);
        if (!is_superadmin_loggedin()) {
            $this->db->where('staff.branch_id', get_loggedin_branch_id());
        }
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            show_404();
        }
        return $query->row_array();
    }

    // get staff all list
    public function getStaffList($branchID = '', $role_id = '', $active = 1)
    {
        // if ($role_id === '3') {
        //     $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name');
        //     $this->db->from('staff');
        //     $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        //     $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        //     if ($branchID != "") {
        //         $this->db->where('staff.branch_id', $branchID);
        //     }
        //     $this->db->order_by('staff.id', 'ASC');
        //     return $this->db->get()->result();
        // }

        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id, roles.name as role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        if ($branchID != "") {
            $this->db->where('staff.branch_id', $branchID);
        }
        $this->db->where('login_credential.role', $role_id);
        $this->db->where('login_credential.active', $active);
        $this->db->order_by('staff.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_schedule_by_id($id)
    {
        $this->db->select('timetable_class.*,subject.name as subject_name,class.name as class_name,section.name as section_name');
        $this->db->from('timetable_class');
        $this->db->join('subject', 'subject.id = timetable_class.subject_id', 'inner');
        $this->db->join('class', 'class.id = timetable_class.class_id', 'inner');
        $this->db->join('section', 'section.id = timetable_class.section_id', 'inner');
        $this->db->where('timetable_class.teacher_id', $id);
        $this->db->where('timetable_class.session_id', get_session_id());
        return $this->db->get();
    }

    public function bankSave($data)
    {
        $inser_data = array(
            'staff_id' => $data['staff_id'],
            'bank_name' => $data['bank_name'],
            'holder_name' => $data['holder_name'],
            'bank_branch' => $data['bank_branch'],
            'bank_address' => $data['bank_address'],
            'ifsc_code' => $data['ifsc_code'],
            'account_no' => $data['account_no'],
        );
        if (isset($data['bank_id'])) {
            $this->db->where('id', $data['bank_id']);
            $this->db->update('staff_bank_account', $inser_data);
        } else {
            $this->db->insert('staff_bank_account', $inser_data);
        }
    }

    public function educationSave($data)
    {
        $inser_data = array(
            'staff_id' => $data['staff_id'],
            'institute_name' => $data['institute_name'],
            'degree' => $data['degree'],
            'study_field' => $data['study_field'],
            'location' => $data['location'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        );
        if (isset($data['staff_education_id'])) {
            $this->db->where('id', $data['staff_education_id']);
            $this->db->update('staff_education', $inser_data);
        } else {
            $this->db->insert('staff_education', $inser_data);
        }
    }

    public function experienceSave($data)
    {
        $inser_data = array(
            'staff_id'       => $data['staff_id'],
            'title'          => $data['title'],
            'type'           => $data['type'],
            'institute_name' => $data['institute_name'],
            'location'       => $data['location'],
            'start_date'     => $data['start_date'],
            'end_date'       => $data['end_date'],
        );
        if (isset($data['staff_experience_id'])) {
            $this->db->where('id', $data['staff_experience_id']);
            $this->db->update('staff_experience', $inser_data);
        } else {
            $this->db->insert('staff_experience', $inser_data);
        }
    }

    public function spouseSave($data)
    {
        $inser_data = array(
            'staff_id'       => $data['staff_id'],
            'name'          => $data['name'],
            'occupation'     => $data['occupation'],
            'total_child'     => $data['total_child'],
            'dependent_child' => $data['dependent_child'],
        );
        if (isset($data['staff_spouse_id'])) {
            $this->db->where('id', $data['staff_spouse_id']);
            $this->db->update('staff_spouse', $inser_data);
        } else {
            $this->db->insert('staff_spouse', $inser_data);
        }
    }

    public function jobStatusSave($data)
    {
        $inser_data = array(
            'staff_id'        => $data['staff_id'],
            'type'            => $data['type'],
            'comment'         => $data['comment'],
            'status_date'     => $data['status_date'],
        );
        if (isset($data['staff_job_status_id'])) {
            $this->db->where('id', $data['staff_job_status_id']);
            $this->db->update('staff_job_status', $inser_data);
        } else {
            $this->db->insert('staff_job_status', $inser_data);
        }
    }

    public function performanceSave($data)
    {
        $inser_data = array(
            'staff_id'              => $data['staff_id'],
            'year_id'               => $data['year_id'],
            'role'                  => $data['role'],
            'comment'               => isset($data['comment']) ? $data['comment'] : NULL,
            'verification_date'     => isset($data['verification_date']) ? $data['verification_date'] : '',
            'academic_achievement'  => isset($data['academic_achievement']) ? $data['academic_achievement'] : NULL,
            'attendance'            => isset($data['attendance']) ? $data['attendance'] : NULL,
            'lesson_planning'       => isset($data['lesson_planning']) ? $data['lesson_planning'] : NULL,
            'personality'           => isset($data['personality']) ? $data['personality'] : NULL,
            'school_contribution'   => isset($data['school_contribution']) ? $data['school_contribution'] : NULL,
            'documentation'         => isset($data['documentation']) ? $data['documentation'] : NULL,
            'created_by'            => isset($data['created_by']) ? $data['created_by'] : NULL,
            'action_by'             => isset($data['action_by']) ? $data['action_by'] : NULL,
            'evaluation_date'       => date('Y-m-d H:i:s'),
            'status'                => isset($data['status']) ? $data['status'] : 'pending',
        );
        // print_r($inser_data);exit;
        if (isset($data['staff_performance_id'])) {
            $this->db->where('id', $data['staff_performance_id']);
            $this->db->update('staff_performance', $inser_data);
        } else {
            $this->db->insert('staff_performance', $inser_data);
        }
    }

    public function csvImport($row, $branchID, $userRole, $designationID, $departmentID)
    {
        $inser_data1 = array(
            'name' => $row['Name'],
            'sex' => $row['Gender'],
            'religion' => $row['Religion'],
            'blood_group' => $row['BloodGroup'],
            'birthday' => date("Y-m-d", strtotime($row['DateOfBirth'])),
            'joining_date' => date("Y-m-d", strtotime($row['JoiningDate'])),
            'qualification' => $row['Qualification'],
            'mobileno' => $row['MobileNo'],
            'present_address' => $row['PresentAddress'],
            'permanent_address' => $row['PermanentAddress'],
            'email' => $row['Email'],
            'designation' => $designationID,
            'department' => $departmentID,
            'branch_id' => $branchID,
            'photo' => 'defualt.png',
        );

        $inser_data2 = array(
            'username' => $row["Email"],
            'role' => $userRole,
        );

        // RANDOM STAFF ID GENERATE
        $inser_data1['staff_id'] = substr(app_generate_hash(), 3, 7);
        // SAVE EMPLOYEE INFORMATION IN THE DATABASE
        $this->db->insert('staff', $inser_data1);
        $employeeID = $this->db->insert_id();

        // SAVE EMPLOYEE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
        $inser_data2['active'] = 1;
        $inser_data2['user_id'] = $employeeID;
        $inser_data2['password'] = $this->app_lib->pass_hashed($row["Password"]);
        $this->db->insert('login_credential', $inser_data2);
        return true;
    }

    public function getBranch($id)
    {

        $this->db->where('id', $id);
        $query = $this->db->get('branch');
        return $query->row_array();
    }

    public function tenure($interval)
    {
        $tenure = '';
        if ($interval->y) {
            $tenure .= $interval->y . ' years ';
        }
        if ($interval->m) {
            $tenure .= $interval->m . ' months ';
        }
        if ($interval->d) {
            $tenure .= $interval->d . ' days';
        }
        $tenure = trim($tenure); // Remove any trailing spaces
        if ($tenure == '') {
            $tenure = '0 days';
        }
        return $tenure;
    }

    public function get_emp_tenure($id)
    {
        // dd(array(
        //     // $this->db->where('emp_id', $id)->order_by('updated_at', 'DESC')->get('staff_promotions')->result(),
        //     // $this->db->select('transfer_posting.*, staff_promotions.*')
        //     // $this->db->select('
        //     //     transfer_posting.id as tp_id,
        //     //     transfer_posting.emp_id as emp_id, 
        //     //     transfer_posting.effective_from as tp_effective_from, 
        //     //     transfer_posting.updated_at as tp_updated_at, 
        //     //     staff_promotions.id as sp_id, 
        //     //     staff_promotions.created_at as sp_created_at, 
        //     //     staff_promotions.updated_at as sp_updated_at
        //     // ')
        //     //     ->from('transfer_posting')
        //     //     ->join('staff_promotions', 'staff_promotions.created_at < transfer_posting.effective_from AND staff_promotions.emp_id = transfer_posting.emp_id')
        //     //     ->where('transfer_posting.emp_id', $id)
        //     //     ->order_by('transfer_posting.updated_at', 'DESC')
        //     //     ->get()
        //     //     ->result_array(),
        // ));
        // $result = $this->db->where('emp_id', $id)->order_by('updated_at', 'DESC')->get('transfer_posting')->result_array();
        $this->db->select('
            tp.id,
            tp.designation_id,
            des.name AS designation,
            tp.new_dep_id,
            dep.name AS department,
            tp.new_branch_id,
            ins.name AS branch,
            tp.effective_from,
            tp.notes,
            tp.created_by,
            st.name AS created_by_name,
            tp.action_by,
            st1.name AS action_by_name,
            st2.name AS emp_name,
            st2.staff_id AS staff_id,
            tp.status,
            tp.emp_id,
            tp.updated_at
        ');
        $this->db->from('transfer_posting tp');
        $this->db->join('staff_designation des', 'tp.designation_id = des.id', 'left');
        $this->db->join('staff_department dep', 'tp.new_dep_id = dep.id', 'left');
        $this->db->join('branch ins', 'tp.new_branch_id = ins.id', 'left');
        $this->db->join('staff st', 'tp.created_by = st.id', 'left');
        $this->db->join('staff st1', 'tp.action_by = st1.id', 'left');
        $this->db->join('staff st2', 'tp.emp_id = st2.id', 'left');
        $this->db->where('tp.emp_id', $id);
        $this->db->where('tp.status', 'approved');
        $this->db->where('tp.deleted_at', null);
        $this->db->order_by('updated_at', 'ASC');
        $result = $this->db->get()->result_array();
        // dd($query);
        for ($i = 0; $i < (count($result) - 1); $i++) {
            $current_effective_from = $result[$i]['effective_from'];
            $next_effective_from = $result[$i + 1]['effective_from'];

            $current_date = new DateTime($current_effective_from);
            $next_date = new DateTime($next_effective_from);

            $interval = $next_date->diff($current_date);

            $result[$i]['tenure'] = $this->tenure($interval);
        }
        $today = new DateTime(date('Y-m-d'));
        $last_effective_from = $result[count($result) - 1]['effective_from'];
        $result[count($result) - 1]['tenure'] = $this->tenure($today->diff(new DateTime($last_effective_from)));
        return $result;
    }
}
