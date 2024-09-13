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
$route['api/dbSyncController/studentTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/student/$1';
$route['api/dbSyncController/studentCategoryTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/student_category/$1';
$route['api/dbSyncController/classTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/class/$1';
$route['api/dbSyncController/sectionTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/section/$1';
$route['api/dbSyncController/parentsTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/parent/$1';
$route['api/dbSyncController/sectionsAllocationTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/sections_allocation/$1';
$route['api/dbSyncController/enrollTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/enroll/$1';
$route['api/dbSyncController/studentAttendanceTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/student_attendance/$1';
$route['api/dbSyncController/homeworkTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/homework/$1';
$route['api/dbSyncController/homeworkEvaluationTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/homework_evaluation/$1';
$route['api/dbSyncController/homeworkSubmitTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/homework_submit/$1';
$route['api/dbSyncController/subjectTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/subject/$1';
$route['api/dbSyncController/subjectAssignTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/subject_assign/$1';
// Zeeshan is Working on Following
$route['api/dbSyncController/examTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/exam/$1';
$route['api/dbSyncController/examAttendanceTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/exam_attendance/$1';
$route['api/dbSyncController/examHallTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/exam_hall/$1';
$route['api/dbSyncController/examMarkDistributionTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/exam_mark_distribution/$1';
$route['api/dbSyncController/examTermTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/exam_term/$1';
$route['api/dbSyncController/timetableClassTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/timetable_class/$1';
$route['api/dbSyncController/timetableExamTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/timetable_exam/$1';
$route['api/dbSyncController/staffTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/staff/$1';
$route['api/dbSyncController/staffAttendanceTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/staff_attendance/$1';
$route['api/dbSyncController/staffBankAccountTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/staff_bank_account/$1';
$route['api/dbSyncController/staffDepartmentTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/staff_department/$1';
$route['api/dbSyncController/staffDesignationTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/staff_designation/$1';
$route['api/dbSyncController/staffDocumentsTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/staff_documents/$1';
$route['api/dbSyncController/staffPrivilegesTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/staff_privileges/$1';
$route['api/dbSyncController/teacherAllocationTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/teacher_allocation/$1';
$route['api/dbSyncController/teacherNoteTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/teacher_note/$1';
$route['api/dbSyncController/salaryTemplateTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/salary_template/$1';
// Upcoming
$route['api/dbSyncController/attachmentsTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/attachments/$1';
$route['api/dbSyncController/attachmentsTypeTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/attachments_type/$1';
$route['api/dbSyncController/schoolYearTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/schoolyear/$1';
$route['api/dbSyncController/lmsGradesTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/lms_grades/$1';
$route['api/dbSyncController/lmsSubjectsTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/lms_subjects/$1';
$route['api/dbSyncController/leaveApplicationTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/leave_application/$1';
$route['api/dbSyncController/leaveCategoryTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/leave_category/$1';
$route['api/dbSyncController/loginCredentialsTable/(:num)'] = 'api/dbSyncController/handleTableUpsert/login_credential/$1';





$route['authentication'] = 'authentication/index';
$route['home'] = 'home/index';

$route['404_override'] = 'errors';
$route['default_controller'] = 'home';
$route['(:any)'] = 'home/index/$1';
$route['translate_uri_dashes'] = FALSE;
