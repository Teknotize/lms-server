<?php
defined('BASEPATH') or exit('No direct script access allowed');

class db_table_create extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->dbforge();
    }

    public function index()
    {
        $json = file_get_contents('tables.json');
        $tables = json_decode($json, true);
        foreach ($tables as $item) {
            $this->create_table($item['table_name'], $item['fields'], $item['primary_key']);
            echo "<br><br>".$item['table_name'];
        }
        
        exit;
    }


    public function create_table($table_name, $fields, $primary_key)
    {
        // Check if the table already exists
        if ($this->db->table_exists($table_name)) {
            echo "Table '" . $table_name . "' already exists.";
            return;
        }

        // Define the fields for the new table

        // Add primary key
        $this->dbforge->add_key($primary_key, TRUE);

        // Set the table options for character set and collation
        $attributes = array(
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        );

        // Create the table with the specified attributes
        if ($this->dbforge->add_field($fields) && $this->dbforge->create_table($table_name, TRUE, $attributes)) {
            echo "Table " . $table_name . " created successfully.";
        } else {
            echo "Error creating table '" . $table_name . "' -- " . json_encode($this->db->error());
        }
    }
}
