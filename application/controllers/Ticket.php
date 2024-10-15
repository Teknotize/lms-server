<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 6.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Event.php
 * @copyright : Reserved RamomCoder Team
 */

class Ticket extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ticket_model'); 
    }

    public function index()
    {
        
        if (!get_permission('event', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            // check access permission
            if (!get_permission('event', 'is_add')) {
               ajax_access_denied();
            }

            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('institution'), 'required');
            }
            
            $this->form_validation->set_rules('subject', translate('subject'), 'trim|required');  
            $this->form_validation->set_rules('attachment', 'attachment', 'callback_photoHandleUpload[attachment]');
                
            if ($this->form_validation->run() !== false) {
                 

                $attachment = 'null';
                if (isset($_FILES["attachment"]) && $_FILES['attachment']['name'] != '' && (!empty($_FILES['attachment']['name']))) {
                    $attachment = $this->ticket_model->fileupload("attachment", "./uploads/frontend/tickets/",'', false);
                }

                $subject = $this->input->post('subject'); 
                $message = $this->input->post('message'); 
                $arrayTicket = array(
                    'branch_id' => $branchID,
                    'user_id'   =>get_loggedin_user_id(),
                    'status'    => 'open',
                    'subject'   => $subject,
                    'message'   => $message,
                    'attachment' => $attachment,  
                );

                // print_r($arrayTicket); exit;
                $this->ticket_model->save($arrayTicket);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('ticket/index');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('Tickets');
        $this->data['sub_page'] = 'ticket/index';
        $this->data['main_menu'] = 'ticket';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/daterangepicker/daterangepicker.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }
    public function show($tid='')
    {
        
        if (!get_permission('event', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            // check access permission
            if (!get_permission('event', 'is_add')) {
               ajax_access_denied();
            }

            // if (is_superadmin_loggedin()) {
            //     $this->form_validation->set_rules('branch_id', translate('institution'), 'required');
            // }
            
            // $this->form_validation->set_rules('message', translate('subject'), 'trim|required');  
            $this->form_validation->set_rules('attachment', 'attachment', 'callback_photoHandleUpload[attachment]');
                
            if ($this->form_validation->run() !== false) { 

                $attachment = 'null';
                if (isset($_FILES["attachment"]) && $_FILES['attachment']['name'] != '' && (!empty($_FILES['attachment']['name']))) {
                    $attachment = $this->ticket_model->fileupload("attachment", "./uploads/frontend/ticketreply/",'', false);
                }

                $ticket_id = $this->input->post('ticket_id'); 
                $message   = $this->input->post('message'); 
                $status   = $this->input->post('status'); 
                $arrayTicket = array(  
                    'user_id'   => is_superadmin_loggedin() ?null:get_loggedin_user_id(), 
                    'agent_id'  => is_superadmin_loggedin() ?get_loggedin_user_id():null, 
                    'ticket_id' => $ticket_id,
                    'message'   => $message,
                    'status'    => $status,
                    'attachment'=> $attachment,  
                );

                // print_r($arrayTicket); exit;
                $this->ticket_model->saveReply($arrayTicket);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('ticket/index');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id'] = $branchID;
        $this->data['ticket_id'] = $tid;
        $this->data['title'] = translate('Tickets');
        $this->data['sub_page'] = 'ticket/show';
        $this->data['main_menu'] = 'ticket';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/daterangepicker/daterangepicker.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

  
  
}
