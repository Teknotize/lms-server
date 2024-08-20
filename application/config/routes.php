<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['(:any)/authentication'] = 'authentication/index/$1';
$route['(:any)/teachers'] = 'home/teachers';
$route['(:any)/events'] = 'home/events';
$route['(:any)/about'] = 'home/about';
$route['(:any)/faq'] = 'home/faq';
$route['(:any)/admission'] = 'home/admission';
$route['(:any)/gallery'] = 'home/gallery';
$route['(:any)/contact'] = 'home/contact';
$route['(:any)/admit_card'] = 'home/admit_card';
$route['(:any)/exam_results'] = 'home/exam_results';
$route['(:any)/certificates'] = 'home/certificates';
$route['(:any)/page/(:any)'] = 'home/page/$2';
$route['(:any)/gallery_view/(:any)'] = 'home/gallery_view/$2';
$route['(:any)/event_view/(:num)'] = 'home/event_view/$2';

$route['dashboard'] = 'dashboard/index';
$route['digital_library/branches/(:num)'] = 'digital_library/branch/$1';
$route['digital_library/branches/(:num)/grades/(:num)'] = 'digital_library/grade/$1/$2';
$route['digital_library/branches/("num)/grades/(:num)/subjects/(:num)'] = 'digital_library/subject/$1/$2/$3';
$route['lms_grades'] = 'lms_grades/index';
$route['lms_subjects'] = 'lms_subjects/index';
$route['branch'] = 'branch/index';
$route['attachments'] = 'attachments/index';
$route['homework'] = 'homework/index';
$route['onlineexam'] = 'onlineexam/index';
$route['hostels'] = 'hostels/index';
$route['event'] = 'event/index';
$route['accounting'] = 'accounting/index';
$route['school_settings'] = 'school_settings/index';
$route['role'] = 'role/index';
$route['sessions'] = 'sessions/index';
$route['translations'] = 'translations/index';
$route['cron_api'] = 'cron_api/index';
$route['modules'] = 'modules/index';
$route['system_student_field'] = 'system_student_field/index';
$route['custom_field'] = 'custom_field/index';
$route['backup'] = 'backup/index';
$route['advance_salary'] = 'advance_salary/index';
$route['system_update'] = 'system_update/index';
$route['certificate'] = 'certificate/index';
$route['payroll'] = 'payroll/index';
$route['leave'] = 'leave/index';
$route['award'] = 'award/index';
$route['classes'] = 'classes/index';
$route['student_promotion'] = 'student_promotion/index';
$route['live_class'] = 'live_class/index';
$route['exam'] = 'exam/index';
$route['profile'] = 'profile/index';
$route['sections'] = 'sections/index';
// API ROUTES
// the param that I am passing to handleUpsert function is the table name
$route['api/dbSyncController/studentTable'] = 'api/dbSyncController/handleTableUpsert/student';
$route['api/dbSyncController/classTable'] = 'api/dbSyncController/handleTableUpsert/class';
$route['api/dbSyncController/sectionTable'] = 'api/dbSyncController/handleTableUpsert/section';
$route['api/dbSyncController/parentsTable'] = 'api/dbSyncController/handleTableUpsert/parent';
$route['api/dbSyncController/sectionsAllocationTable'] = 'api/dbSyncController/handleTableUpsert/sections_allocation';
$route['api/dbSyncController/enrollTable'] = 'api/dbSyncController/handleTableUpsert/enroll';
$route['api/dbSyncController/studentAttendanceTable'] = 'api/dbSyncController/handleTableUpsert/student_attendance';
$route['api/dbSyncController/homeworkTable'] = 'api/dbSyncController/handleTableUpsert/homework';
$route['api/dbSyncController/homeworkEvaluationTable'] = 'api/dbSyncController/handleTableUpsert/homework_evaluation';
$route['api/dbSyncController/homeworkSubmitTable'] = 'api/dbSyncController/handleTableUpsert/homework_submit';
$route['api/dbSyncController/subjectTable'] = 'api/dbSyncController/handleTableUpsert/subject';
$route['api/dbSyncController/subjectAssignTable'] = 'api/dbSyncController/handleTableUpsert/subject_assign';
// Zeeshan is Working on Following
$route['api/dbSyncController/examTable'] = 'api/dbSyncController/handleTableUpsert/exam';
$route['api/dbSyncController/examAttendanceTable'] = 'api/dbSyncController/handleTableUpsert/exam_attendance';
$route['api/dbSyncController/examHallTable'] = 'api/dbSyncController/handleTableUpsert/exam_hall';
$route['api/dbSyncController/examMarkDistributionTable'] = 'api/dbSyncController/handleTableUpsert/exam_mark_distribution';
$route['api/dbSyncController/examTermTable'] = 'api/dbSyncController/handleTableUpsert/exam_term';
$route['api/dbSyncController/timetableClassTable'] = 'api/dbSyncController/handleTableUpsert/timetable_class';
$route['api/dbSyncController/timetableExamTable'] = 'api/dbSyncController/handleTableUpsert/timetable_exam';
$route['api/dbSyncController/staffTable'] = 'api/dbSyncController/handleTableUpsert/staff';
$route['api/dbSyncController/staffAttendanceTable'] = 'api/dbSyncController/handleTableUpsert/staff_attendance';
$route['api/dbSyncController/staffBankAccountTable'] = 'api/dbSyncController/handleTableUpsert/staff_bank_account';
$route['api/dbSyncController/staffDepartmentTable'] = 'api/dbSyncController/handleTableUpsert/staff_department';
$route['api/dbSyncController/staffDesignationTable'] = 'api/dbSyncController/handleTableUpsert/staff_designation';
$route['api/dbSyncController/staffDocumentsTable'] = 'api/dbSyncController/handleTableUpsert/staff_documents';
$route['api/dbSyncController/staffPrivilegesTable'] = 'api/dbSyncController/handleTableUpsert/staff_privileges';
$route['api/dbSyncController/teacherAllocationTable'] = 'api/dbSyncController/handleTableUpsert/teacher_allocation';
$route['api/dbSyncController/teacherNoteTable'] = 'api/dbSyncController/handleTableUpsert/teacher_note';
$route['api/dbSyncController/salaryTemplateTable'] = 'api/dbSyncController/handleTableUpsert/salary_template';
// Upcoming
$route['api/dbSyncController/attachmentsTable'] = 'api/dbSyncController/handleTableUpsert/attachments';
$route['api/dbSyncController/attachmentsTypeTable'] = 'api/dbSyncController/handleTableUpsert/attachments_type';
$route['api/dbSyncController/schoolYearTable'] = 'api/dbSyncController/handleTableUpsert/schoolyear';
$route['api/dbSyncController/lmsGradesTable'] = 'api/dbSyncController/handleTableUpsert/lms_grades';
$route['api/dbSyncController/lmsSubjectsTable'] = 'api/dbSyncController/handleTableUpsert/lms_subjects';
$route['api/dbSyncController/leaveApplicationTable'] = 'api/dbSyncController/handleTableUpsert/leave_application';
$route['api/dbSyncController/leaveCategoryTable'] = 'api/dbSyncController/handleTableUpsert/leave_category';
$route['api/dbSyncController/loginCredentialsTable'] = 'api/dbSyncController/handleTableUpsert/login_credential';




$route['authentication'] = 'authentication/index';
$route['home'] = 'home/index';

$route['404_override'] = 'errors';
$route['default_controller'] = 'home';
$route['(:any)'] = 'home/index/$1';
$route['translate_uri_dashes'] = FALSE;
