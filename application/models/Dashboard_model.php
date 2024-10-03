<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    } 

    public function getMonthlyBookIssued($id = '')
    {
        $this->db->select('id');
        $this->db->from('leave_application');
        $this->db->where("start_date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 MONTH) AND CURDATE() AND status = '2' AND role_id = '7' AND user_id = " . $this->db->escape($id));
        return $this->db->get()->num_rows();
    }

    public function getStaffCounter($role = '', $branchID = '')
    {
        $this->db->select('COUNT(staff.id) as snumber');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id', 'inner');
        $this->db->where_not_in('login_credential.role', 1);
        if (!empty($role)) {
            $this->db->where('login_credential.role', $role);
        } else {
            $this->db->where_not_in('login_credential.role', array(1, 3, 6, 7));
        }
        if (!empty($branchID)) {
            $this->db->where('staff.branch_id', $branchID);
        }
        return $this->db->get()->row_array();
    }

    public function getMonthlyPayment($id = '')
    {
        $this->db->select('IFNULL(sum(h.amount),0) as amount');
        $this->db->from('fee_allocation as fa');
        $this->db->join('fee_payment_history as h', 'h.allocation_id = fa.id', 'left');
        $this->db->where("h.date BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 MONTH) AND CURDATE() AND fa.student_id = " . $this->db->escape($id) . " AND fa.session_id = " . $this->db->escape(get_session_id()));
        return $this->db->get()->row()->amount;
    }

    /* annual academic fees summary charts */
    public function annualFeessummaryCharts($branchID = '', $studentID = '')
    {
        $total_fee = array();
        $total_paid = array();
        $total_due = array();
        $year = date("Y");
        for ($month = 1; $month <= 12; $month++) {
            $sql = "SELECT `fa`.`id` as `allocation_id`,`gd`.`fee_type_id`,`gd`.`amount` FROM `fee_allocation` as `fa` INNER JOIN `fee_groups_details` as `gd` ON `gd`.`fee_groups_id` = `fa`.`group_id` WHERE MONTH(`gd`.`due_date`) = " . $this->db->escape($month) . " AND YEAR(`gd`.`due_date`) = '$year' AND `fa`.`session_id` = " . $this->db->escape(get_session_id());
            if (!empty($branchID)) {
                $sql .= " AND `fa`.`branch_id` = " . $this->db->escape($branchID);
            }
            if (!empty($studentID)) {
                $sql .= " AND `fa`.`student_id` = " . $this->db->escape($studentID);
            }
            $total_amount = 0;
            $totalpaid = 0;
            $total_discount = 0;
            $result = $this->db->query($sql)->result();
            foreach ($result as $row) {
                $total_amount += $row->amount;
                $sql = "SELECT SUM(`h`.`amount`) AS `total_paid`, SUM(`h`.`discount`) AS `total_discount` FROM `fee_payment_history` as `h` WHERE `h`.`allocation_id` = " . $this->db->escape($row->allocation_id) . " AND  `h`.`type_id` = " . $this->db->escape($row->fee_type_id);
                $r = $this->db->query($sql)->row();
                $totalpaid += $r->total_paid;
                $total_discount += $r->total_discount;
            }
            $total_fee[] = floatval($total_amount);
            $total_paid[] = floatval($totalpaid);
            $total_due[] = floatval($total_amount - ($totalpaid + $total_discount));
        };
        return array(
            'total_fee' => $total_fee,
            'total_paid' => $total_paid,
            'total_due' => $total_due,
        );
    }

    /* student annual attendance charts */
    public function getStudentAttendance($studentID = '')
    {
        $total_present = array();
        $total_absent = array();
        $total_late = array();
        $enrollID = $this->db->select('id')->where(['student_id' => $studentID, 'session_id' => get_session_id()])->get('enroll')->row()->id;
        for ($month = 1; $month <= 12; $month++):
            $total_present[] = $this->db->query("SELECT id FROM student_attendance WHERE MONTH(date) = " . $this->db->escape($month) . " AND YEAR(date) = YEAR(CURDATE()) AND status = 'P' AND enroll_id = " . $this->db->escape($enrollID))->num_rows();
            $total_absent[] = $this->db->query("SELECT id FROM student_attendance WHERE MONTH(date) = " . $this->db->escape($month) . " AND YEAR(date) = YEAR(CURDATE()) AND status = 'A' AND enroll_id = " . $this->db->escape($enrollID))->num_rows();
            $total_late[] = $this->db->query("SELECT id FROM student_attendance WHERE MONTH(date) = " . $this->db->escape($month) . " AND YEAR(date) = YEAR(CURDATE()) AND status = 'L' AND enroll_id = " . $this->db->escape($enrollID))->num_rows();
        endfor;
        return array(
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total_late' => $total_late,
        );
    }

    public function get_monthly_attachments($id = '')
    {
        $branchID = get_loggedin_branch_id();
        $classID = $this->db->select('class_id')->where('student_id', $id)->get('enroll')->row()->class_id;
        $this->db->select('id');
        $this->db->from('attachments');
        $this->db->where("date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 MONTH) AND CURDATE() AND (class_id = " . $this->db->escape($classID) . " OR class_id = 'unfiltered') AND branch_id = " . $this->db->escape($branchID));
        return $this->db->get()->num_rows();
    }

    /* annual academic fees summary charts */
    public function getWeekendAttendance($branchID = '')
    {
        $days = array();
        $employee_att = array();
        $student_att = array();
        $now = new DateTime("6 days ago");
        $interval = new DateInterval('P1D'); // 1 Day interval
        $period = new DatePeriod($now, $interval, 6); // 7 Days
        foreach ($period as $day) {
            $days[] = $day->format("d-M");
            $this->db->select('id');
            if (!empty($branchID)) {
                $this->db->where('branch_id', $branchID);
            }

            $this->db->where('date = "' . $day->format('Y-m-d') . '" AND (status = "P" OR status = "L")');
            $student_att[]['y'] = $this->db->get('student_attendance')->num_rows();

            $this->db->select('id');
            if (!empty($branchID)) {
                $this->db->where('branch_id', $branchID);
            }

            $this->db->where('date = "' . $day->format('Y-m-d') . '" AND (status = "P" OR status = "L")');
            $employee_att[]['y'] = $this->db->get('staff_attendance')->num_rows();
        }
        return array(
            'days' => $days,
            'employee_att' => $employee_att,
            'student_att' => $student_att,
        );
    }

    /* monthly academic cash book transaction charts */
    public function getIncomeVsExpense($branchID = '')
    {
        $query = "SELECT IFNULL(SUM(dr),0) as dr, IFNULL(SUM(cr),0) as cr FROM transactions WHERE month(DATE) = MONTH(now()) AND year(DATE) = YEAR(now())";
        if (!empty($branchID)) {
            $query .= " AND branch_id = " . $this->db->escape($branchID);
        }
        $r = $this->db->query($query)->row_array();
        return array(['name' => translate("expense"), 'value' => $r['dr']], ['name' => translate("income"), 'value' => $r['cr']]);
    }

    /* total academic students strength classes divided into charts */
    public function getStudentByClass($branchID = '')
    {
        $this->db->select('IFNULL(COUNT(e.student_id), 0) as total_student, c.name as class_name');
        $this->db->from('enroll as e');
        $this->db->join('class as c', 'c.id = e.class_id', 'inner');
        $this->db->group_by('e.class_id');
        if (!empty($branchID)) {
            $this->db->where('e.branch_id', $branchID);
        }

        $query = $this->db->get();
        $data = array();
        if ($query->num_rows() > 0) {
            $students = $query->result();
            foreach ($students as $row) {
                $data[] = ['value' => floatval($row->total_student), 'name' => $row->class_name];
            }
        } else {
            $data[] = ['value' => 0, 'name' => translate('not_found_anything')];
        }
        return $data;
    }

    public function get_total_student($branchID = '')
    {
        $sessionID = get_session_id();
        $this->db->select('IFNULL(COUNT(enroll.id), 0) as total_student');
        $this->db->from('enroll');
        $this->db->join('student', 'student.id = enroll.student_id', 'inner');
        $this->db->where('enroll.session_id', $sessionID);
        if (!empty($branchID)) {
            $this->db->where('enroll.branch_id', $branchID);
        }
        return $this->db->get()->row()->total_student;
    }

    public function getMonthlyAdmission($branchID = '')
    {
        $this->db->select('s.id');
        $this->db->from('student as s');
        $this->db->join('enroll as e', 'e.student_id = s.id', 'inner');
        $this->db->where('s.admission_date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 MONTH) AND CURDATE()');
        if (!empty($branchID)) {
            $this->db->where('e.branch_id', $branchID);
        }
        return $this->db->get()->num_rows();
    }

    public function getVoucher($branchID = '')
    {
        $this->db->select('id');
        if (!empty($branchID)) {
            $this->db->where('branch_id', $branchID);
        }
        $this->db->where('date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 MONTH) AND CURDATE()');
        return $this->db->get('transactions')->num_rows();
    }

    public function get_transport_route($branchID = '')
    {
        if (!empty($branchID)) {
            $this->db->where('branch_id', $branchID);
        }
        return $this->db->get('transport_route')->num_rows();
    }


    public function languageShortCodes($lang='')
    {
        $codes = array (
          'english' => 'en',
          'bengali' => 'bn',
          'arabic' => 'ar',
          'french' => 'fr',
          'hindi' => 'hi',
          'indonesian' => 'id',
          'italian' => 'it',
          'japanese' => 'ja',
          'korean' => 'ko',
          'portuguese' => 'pt',
          'thai' => 'th',
          'turkish' => 'tr',
          'urdu' => 'ur',
          'chinese' => 'zh',
          'afrikaans' => 'af',
          'german' => 'de',
          'nepali' => 'ne',
          'russian' => 'ru',
          'danish' => 'da',
          'armenian' => 'hy',
          'georgian' => 'ka',
          'marathi' => 'mr',
          'malay' => 'ms',
          'tamil' => 'ta',
          'telugu' => 'te',
          'swedish' => 'sv',
          'dutch' => 'nl',
          'greek' => 'el',
          'spanish' => 'es',
          'punjabi' => 'pa',
        );
        return empty($codes[$lang]) ? '' : $codes[$lang];
    }

    public function getEmployeeAttendanceStatus($role = '', $school_id = '') {
        // Initialize attendance data array
        $attendanceData = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'holiday' => 0,
            'total' => 0
        ];
    
        // Add role filter if needed
        if (!empty($role)) {
            $this->db->join('login_credential', 'login_credential.user_id = staff_attendance.staff_id', 'inner');
            $this->db->where('login_credential.role', $role);
        } else {
            // Exclude certain roles if needed (same logic as in getStaffCounter)
            $this->db->join('login_credential', 'login_credential.user_id = staff_attendance.staff_id', 'inner');
            $this->db->where_not_in('login_credential.role', array(1, 3, 6, 7));
        }
    
        // Query to count present employees (status = 'P')
        $this->db->select('COUNT(*) as present');
        $this->db->where('staff_attendance.status', 'P');
        if (!empty($school_id)) {
            $this->db->where('staff_attendance.branch_id', $school_id);
        }
        $query = $this->db->get('staff_attendance');
        $result = $query->row_array();
        $attendanceData['present'] = $result['present'];
    
        // Query to count absent employees (status = 'A')
        $this->db->select('COUNT(*) as absent');
        $this->db->where('staff_attendance.status', 'A');
        if (!empty($school_id)) {
            $this->db->where('staff_attendance.branch_id', $school_id);
        }
        $query = $this->db->get('staff_attendance');
        $result = $query->row_array();
        $attendanceData['absent'] = $result['absent'];
    
        // Query to count late employees (status = 'L')
        $this->db->select('COUNT(*) as late');
        $this->db->where('staff_attendance.status', 'L');
        if (!empty($school_id)) {
            $this->db->where('staff_attendance.branch_id', $school_id);
        }
        $query = $this->db->get('staff_attendance');
        $result = $query->row_array();
        $attendanceData['late'] = $result['late'];
    
        // Query to count holiday (status = 'H') (if needed)
        $this->db->select('COUNT(*) as holiday');
        $this->db->where('staff_attendance.status', 'H');
        if (!empty($school_id)) {
            $this->db->where('staff_attendance.branch_id', $school_id);
        }
        $query = $this->db->get('staff_attendance');
        $result = $query->row_array();
        $attendanceData['holiday'] = $result['holiday'];
    
        // Calculate total employees (excluding holidays if necessary)
        $attendanceData['total'] = $attendanceData['present'] + $attendanceData['absent'] + $attendanceData['late'];
    
        return $attendanceData;
    }

    public function getStudentAttendanceStatus($school_id = '') {
        // Initialize attendance data array
        $attendanceData = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'holiday' => 0,
            'total_days' => 0,
            'total_students' => 0,
            'present_percentage' => 0,
            'absent_percentage' => 0,
            'late_percentage' => 0,
            'holiday_percentage' => 0
        ]; 
    
        // Step 1: Get total distinct students
        $this->db->select('COUNT(DISTINCT enroll_id) as total_students');
        if (!empty($school_id)) {
            $this->db->where('branch_id', $school_id);
        }
        $query = $this->db->get('student_attendance');
        $result = $query->row_array();
        $totalStudents = $result['total_students'];
        $attendanceData['total_students'] = $totalStudents;
    
        // Step 2: Get total number of attendance days (assuming you are counting by date)
        $this->db->select('COUNT(DISTINCT date) as total_days');
        if (!empty($school_id)) {
            $this->db->where('branch_id', $school_id);
        }
        $query = $this->db->get('student_attendance');
        $result = $query->row_array();
        $totalDays = $result['total_days'];
        $attendanceData['total_days'] = $totalDays;
    
        // Step 3: Count attendance status across all students and days
        $this->db->select('COUNT(*) as present');
        $this->db->where('status', 'P');
        if (!empty($school_id)) {
            $this->db->where('branch_id', $school_id);
        }
        $query = $this->db->get('student_attendance');
        $result = $query->row_array();
        $attendanceData['present'] = $result['present'];
    
        $this->db->select('COUNT(*) as absent');
        $this->db->where('status', 'A');
        if (!empty($school_id)) {
            $this->db->where('branch_id', $school_id);
        }
        $query = $this->db->get('student_attendance');
        $result = $query->row_array();
        $attendanceData['absent'] = $result['absent'];
    
        $this->db->select('COUNT(*) as late');
        $this->db->where('status', 'L');
        if (!empty($school_id)) {
            $this->db->where('branch_id', $school_id);
        }
        $query = $this->db->get('student_attendance');
        $result = $query->row_array();
        $attendanceData['late'] = $result['late'];
    
        $this->db->select('COUNT(*) as holiday');
        $this->db->where('status', 'H');
        if (!empty($school_id)) {
            $this->db->where('branch_id', $school_id);
        }
        $query = $this->db->get('student_attendance');
        $result = $query->row_array();
        $attendanceData['holiday'] = $result['holiday'];
    
        // Step 4: Calculate total attendance records (all statuses)
        $totalAttendanceRecords = $attendanceData['present'] + $attendanceData['absent'] + $attendanceData['late'] + $attendanceData['holiday'];
    
        // Step 5: Calculate percentages based on total possible attendance slots
        // Total slots = total students * total days
        $totalSlots = $totalStudents * $totalDays;
    
        if ($totalSlots > 0) {
            $attendanceData['present_percentage'] = ($attendanceData['present'] / $totalSlots) * 100;
            $attendanceData['absent_percentage'] = ($attendanceData['absent'] / $totalSlots) * 100;
            $attendanceData['late_percentage'] = ($attendanceData['late'] / $totalSlots) * 100;
            $attendanceData['holiday_percentage'] = ($attendanceData['holiday'] / $totalSlots) * 100;
        }
    
        return $attendanceData;
    }
    
    
    
}
