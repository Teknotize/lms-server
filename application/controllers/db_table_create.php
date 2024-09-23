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
        }
        exit;
    }


    public function create_table($table_name, $fields, $primary_key)
    {
        // Check if the table already exists
        if ($this->db->table_exists($table_name)) {
            echo ("Table '" . $table_name . "' already exists. Updating fields...<br><br>");

            // Get existing columns
            $existing_fields = $this->db->list_fields($table_name);

            // Update the fields for the existing table
            foreach ($fields as $field_name => $field_attributes) {
                if ($this->db->field_exists($field_name, $table_name)) {
                    // Modify the existing field
                    $this->dbforge->modify_column($table_name, array($field_name => $field_attributes));
                } else {
                    // Add the new field
                    $this->dbforge->add_column($table_name, array($field_name => $field_attributes));
                }
            }

            // Delete columns that are not in the JSON
            foreach ($existing_fields as $existing_field) {
                if (!array_key_exists($existing_field, $fields)) {
                    $this->dbforge->drop_column($table_name, $existing_field);
                }
            }

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
