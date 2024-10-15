<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ticket_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function save($data = array())
    {
        $arrayTicket = array(
            'branch_id' => $data['branch_id'],
            'user_id' => $data['user_id'],
            'subject' => $this->input->post('subject'),
            'message' => $this->input->post('message'),
            'status' => $data['status'], 
            'attachment' => $data['attachment'],   
        );

        // print_r($arrayTicket); exit;
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('tickets', $arrayTicket);
        } else {  
            $this->db->insert('tickets', $arrayTicket);
        }
    }

    function saveReply($data = array())
    {
       
        $arrayReply = array(
            'user_id'   => $data['user_id'],
            'agent_id'  => $data['agent_id'],
            'ticket_id' => $data['ticket_id'], 
            'message'   => $data['message'], 
            'attachment' => $data['attachment'],   
        );

        // print_r($arrayTicket); exit;
        if (isset($data['status']) && !empty($data['status'])) {
            $this->db->where('id', $data['ticket_id']);
            $this->db->update('tickets', ['status'=>$data['status']]);
        }
        
        if(isset($data['message']) && !empty($data['message'])) {  
            $this->db->insert('ticket_replies', $arrayReply);
        }
    }
}
