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

    public function getSingleBranch($id = '')
    {
        $this->db->select('name');
        $this->db->from('branch'); 
        $this->db->where('id', $id); 
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getStaffPerformance($id = '')
    {
         
        $this->db->select('staff_performance.*, 
                           staff.name as staff_name, 
                           staff.staff_id as staff_id_name, 
                           staff_designation.name as designation_name, 
                           login_credential.role as role_id, 
                           login_credential.active, 
                           login_credential.username, 
                           roles.name as role');
        $this->db->from('staff_performance');
    
        // Join the staff table
        $this->db->join('staff', 'staff.id = staff_performance.staff_id', 'left');
    
        // Join the login_credential table
        $this->db->join('login_credential', 'login_credential.user_id = staff.id', 'inner');
    
        // Join the staff_designation table
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
    
        // Join the roles table
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
    
        // Add the where condition
        $this->db->where('staff_performance.id', $id);
    
        
        // Execute the query
        $query = $this->db->get();
    
        // Handle the case where no results are found
         
    
        return $query->row_array();
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
            'staff_id'        => $data['staff_id'],
            'name'            => $data['name'],
            'occupation'      => $data['occupation'],
            'phone'           => $data['phone'],
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
            'comment'               => isset($data['comment'])?$data['comment']:NULL,
            // 'verification_date'     => isset($data['verification_date'])?$data['verification_date']:'', 
            'academic_achievement'  => isset($data['academic_achievement'])?$data['academic_achievement']:NULL, 
            'attendance'            => isset($data['attendance'])?$data['attendance']:NULL, 
            'lesson_planning'       => isset($data['lesson_planning'])?$data['lesson_planning']:NULL, 
            'personality'           => isset($data['personality'])?$data['personality']:NULL, 
            'school_contribution'   => isset($data['school_contribution'])?$data['school_contribution']:NULL, 
            'documentation'         => isset($data['documentation'])?$data['documentation']:NULL, 
            'created_by'            => isset($data['created_by'])?$data['created_by']:NULL, 
            'action_by'             => isset($data['action_by'])?$data['action_by']:NULL,  
            'status'                => isset($data['status'])?$data['status']:'pending', 
        );

        
              
        if(isset($data['evaluation_date'])){
            $inser_data['evaluation_date']=$data['evaluation_date'];
        }

        if(isset($data['verification_date'])){
            $inser_data['verification_date']=$data['verification_date'];
        }
       
        // print_r($inser_data);exit;
        if (isset($data['staff_performance_id'])) {
            $this->db->where('id', $data['staff_performance_id']);
            $this->db->update('staff_performance', $inser_data);
        } else {
            $this->db->insert('staff_performance', $inser_data);
        }
        
    }

    public function staff_latest_rating($staff_id)
    {
         
        $this->db->select('*');
        $this->db->from('staff_performance');  
        $this->db->where('staff_id', $staff_id);
        $this->db->where('status', 'approved');  
        $this->db->order_by('created_at', 'DESC');  
        $this->db->limit(1);   

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $rating_data = $query->row_array();  
          return  $this->calculate_rating($rating_data);
        } else {
          return $rating_data = 0;  
        }  
    }

    public function staff_row_rating($id)
    {
        // Fetch the staff rating data
        $this->db->select('*');
        $this->db->from('staff_performance'); 
        $this->db->where('id', $id);    
        $query = $this->db->get();
    
        // If record is found, calculate rating
        if ($query->num_rows() > 0) {
            $rating_data = $query->row_array();  
            return $this->calculate_rating($rating_data);
        } else {
            return 0;  
        }
    }    
    
    public function calculate_rating($rating_data)
    {
        if ($rating_data) {
            // Define weights for each attribute
            $weights = [
                'academic_achievement' => 0.40, // 40%
                'attendance'           => 0.15, // 15%
                'lesson_planning'      => 0.15, // 15%
                'personality'          => 0.10, // 10%
                'school_contribution'  => 0.10, // 10%
                'documentation'        => 0.10  // 10%
            ];
    
            // Calculate the weighted sum using the actual rating values (assuming max 4 for each)
            $totalScore = (
                ($rating_data['academic_achievement'] ?? 0) * $weights['academic_achievement'] +
                ($rating_data['attendance'] ?? 0) * $weights['attendance'] +
                ($rating_data['lesson_planning'] ?? 0) * $weights['lesson_planning'] +
                ($rating_data['personality'] ?? 0) * $weights['personality'] +
                ($rating_data['school_contribution'] ?? 0) * $weights['school_contribution'] +
                ($rating_data['documentation'] ?? 0) * $weights['documentation']
            );
    
            // The maximum possible score is 4 (if each category is rated out of 4)
            $maxScore = 4;
    
            // Calculate total percentage score (based on the 4-star system)
            $totalPercentage = ($totalScore / $maxScore) * 100;
    
            // Return both total score and percentage
            return [
                'totalScore' => round($totalScore, 2),  // Raw weighted score rounded to 2 decimal places
                'totalPercentage' => round($totalPercentage, 2) // Percentage rounded to 2 decimal places
            ];
        } else {
            return 0;
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
}
