<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_600 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {	
       

        $this->db->query("ALTER TABLE `payment_config` ADD `nepalste_public_key` varchar(255) DEFAULT NULL AFTER `payhere_status`;");
        $this->db->query("ALTER TABLE `payment_config` ADD `nepalste_secret_key` varchar(255) DEFAULT NULL AFTER `nepalste_public_key`;");
        $this->db->query("ALTER TABLE `payment_config` ADD `nepalste_status` tinyint(1) NOT NULL DEFAULT '0' AFTER `nepalste_secret_key`;");

        $this->db->query("TRUNCATE TABLE `payment_types`;");
        $this->db->query("INSERT INTO `payment_types` (`id`, `name`, `branch_id`, `timestamp`) VALUES
                            (1, 'Cash', 0, '2019-07-27 18:12:21'),
                            (2, 'Card', 0, '2019-07-27 18:12:31'),
                            (3, 'Cheque', 0, '2019-12-21 10:07:59'),
                            (4, 'Bank Transfer', 0, '2019-12-21 10:08:36'),
                            (5, 'Other', 0, '2019-12-21 10:08:45'),
                            (6, 'Paypal', 0, '2019-12-21 10:08:45'),
                            (7, 'Stripe', 0, '2019-12-21 10:08:45'),
                            (8, 'PayUmoney', 0, '2019-12-21 10:08:45'),
                            (9, 'Paystack', 0, '2019-12-21 10:08:45'),
                            (10, 'Razorpay', 0, '2019-12-21 10:08:45'),
                            (11, 'SSLcommerz', 0, '2022-05-21 10:08:45'),
                            (12, 'Jazzcash', 0, '2022-05-21 10:08:45'),
                            (13, 'Midtrans', 0, '2022-05-21 10:08:45'),
                            (14, 'Flutter Wave', 0, '2022-05-15 10:08:45'),
                            (15, 'Offline Payments', 0, '2022-05-15 10:08:45'),
                            (16, 'Paytm', 0, '2023-05-12 12:08:45'),
                            (17, 'toyyibPay', 0, '2023-05-12 12:08:45'),
                            (18, 'Payhere', 0, '2023-05-12 12:08:45'),
                            (19, 'Nepalste', 0, '2023-05-12 12:08:45');");
        $this->db->query("CREATE TABLE IF NOT EXISTS `product` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `name` varchar(100) NOT NULL,
                          `code` varchar(50) NOT NULL,
                          `category_id` int NOT NULL,
                          `purchase_unit_id` int NOT NULL,
                          `sales_unit_id` int NOT NULL,
                          `unit_ratio` varchar(20) DEFAULT '1',
                          `purchase_price` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `sales_price` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `available_stock` varchar(11) NOT NULL DEFAULT '0',
                          `photo` varchar(100) DEFAULT NULL,
                          `remarks` text NOT NULL,
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `product_category` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `name` varchar(100) NOT NULL,
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `product_issues` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `role_id` int NOT NULL,
                          `user_id` int NOT NULL,
                          `date_of_issue` date NOT NULL,
                          `due_date` date NOT NULL,
                          `return_date` date DEFAULT NULL,
                          `remarks` text NOT NULL,
                          `prepared_by` int DEFAULT NULL,
                          `status` tinyint(1) NOT NULL DEFAULT '0',
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `product_issues_details` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `issues_id` int NOT NULL,
                          `product_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                          `quantity` varchar(20) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `product_store` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `code` varchar(255) NOT NULL,
                          `mobileno` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                          `address` varchar(300) DEFAULT NULL,
                          `description` varchar(255) DEFAULT NULL,
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `product_supplier` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `name` varchar(200) NOT NULL,
                          `address` text NOT NULL,
                          `mobileno` varchar(30) NOT NULL,
                          `email` varchar(100) NOT NULL,
                          `company_name` varchar(200) NOT NULL,
                          `product_list` mediumtext NOT NULL,
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `product_unit` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `name` varchar(50) NOT NULL,
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `purchase_bill` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `bill_no` varchar(200) NOT NULL,
                          `supplier_id` int NOT NULL,
                          `store_id` int NOT NULL,
                          `remarks` text NOT NULL,
                          `total` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `discount` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `paid` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `due` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `payment_status` int NOT NULL,
                          `purchase_status` int NOT NULL,
                          `date` date DEFAULT NULL,
                          `prepared_by` int DEFAULT NULL,
                          `modifier_id` int DEFAULT NULL,
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `purchase_bill_details` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `purchase_bill_id` int NOT NULL,
                          `product_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                          `unit_price` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `quantity` varchar(20) NOT NULL,
                          `discount` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `sub_total` decimal(18,2) NOT NULL DEFAULT '0.00',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `purchase_payment_history` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `purchase_bill_id` varchar(11) NOT NULL,
                          `payment_by` int DEFAULT NULL,
                          `amount` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `pay_via` varchar(25) NOT NULL,
                          `remarks` text NOT NULL,
                          `attach_orig_name` varchar(255) DEFAULT NULL,
                          `attach_file_name` varchar(255) DEFAULT NULL,
                          `paid_on` date DEFAULT NULL,
                          `coll_type` tinyint NOT NULL DEFAULT '0',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `sales_bill` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `bill_no` varchar(200) NOT NULL,
                          `role_id` int NOT NULL,
                          `user_id` int NOT NULL,
                          `remarks` text NOT NULL,
                          `total` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `discount` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `paid` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `due` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `payment_status` int NOT NULL,
                          `date` date DEFAULT NULL,
                          `prepared_by` int DEFAULT NULL,
                          `modifier_id` int DEFAULT NULL,
                          `branch_id` int NOT NULL,
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `updated_at` datetime DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `sales_bill_details` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `sales_bill_id` int NOT NULL,
                          `product_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                          `unit_price` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `quantity` varchar(20) NOT NULL,
                          `discount` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `sub_total` decimal(18,2) NOT NULL DEFAULT '0.00',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `sales_payment_history` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `sales_bill_id` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                          `payment_by` int DEFAULT NULL,
                          `amount` decimal(18,2) NOT NULL DEFAULT '0.00',
                          `pay_via` varchar(25) NOT NULL,
                          `remarks` text NOT NULL,
                          `attach_orig_name` varchar(255) DEFAULT NULL,
                          `attach_file_name` varchar(255) DEFAULT NULL,
                          `paid_on` date DEFAULT NULL,
                          `coll_type` tinyint NOT NULL DEFAULT '0',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `transactions_links_details` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `payment_id` int NOT NULL,
                          `transactions_id` int NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->db->query("INSERT INTO `permission_modules` (`id`, `name`, `prefix`, `system`, `sorted`, `in_module`, `created_at`) VALUES
                        (25, 'Inventory', 'inventory', 1, 3, 1, '2023-06-13 19:16:49');");
        $this->db->query("INSERT INTO `permission` (`id`, `module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`) VALUES
                        (157, 25, 'Product', 'product', 1, 1, 1, 1, '2023-06-13 19:21:42'),
                        (158, 25, 'Product Category', 'product_category', 1, 1, 1, 1, '2023-06-13 19:21:42'),
                        (159, 25, 'Product Supplier', 'product_supplier', 1, 1, 1, 1, '2023-06-13 19:21:42'),
                        (160, 25, 'Product Unit', 'product_unit', 1, 1, 1, 1, '2023-06-13 19:21:42'),
                        (161, 25, 'Product Purchase', 'product_purchase', 1, 1, 1, 1, '2023-06-13 19:21:42'),
                        (162, 25, 'Purchase Payment', 'purchase_payment', 1, 0, 0, 0, '2023-06-13 19:21:42'),
                        (163, 25, 'Product Store', 'product_store', 1, 1, 1, 1, '2023-06-13 19:21:42'),
                        (164, 25, 'Product Sales', 'product_sales', 1, 1, 0, 1, '2023-06-13 19:21:42'),
                        (165, 25, 'Sales Payment', 'sales_payment', 1, 0, 0, 0, '2023-06-21 07:05:10'),
                        (166, 25, 'Product Issue', 'product_issue', 1, 1, 0, 1, '2023-06-13 19:21:42'),
                        (167, 25, 'Inventory Report', 'inventory_report', 1, 0, 0, 0, '2023-06-27 03:56:45');");

        $this->db->query("INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`) VALUES
                        (2, 157, 1, 1, 1, 1),
                        (2, 158, 1, 1, 1, 1),
                        (2, 159, 1, 1, 1, 0),
                        (2, 160, 1, 1, 1, 1),
                        (2, 161, 1, 1, 1, 1),
                        (2, 162, 0, 0, 1, 0),
                        (2, 163, 1, 1, 1, 1),
                        (2, 164, 1, 0, 1, 1),
                        (2, 165, 0, 0, 1, 0),
                        (2, 166, 1, 0, 1, 1),
                        (2, 167, 0, 0, 1, 0);");
    }
}
