<?php
/*
Plugin Name: Custom Customer Management
Description: WordPress plugin designed to manage a custom database of customer information, with comprehensive administrative capabilities including adding, editing, deleting, and viewing customer records.
Version: 1.0
Author: SHOBHITH N
*/

register_activation_hook(__FILE__, 'custom_customer_management_activate');

function custom_customer_management_activate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'customers'; // customers is the table name

    // create table if not exists in the database
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone_number VARCHAR(20) NOT NULL,
            dob DATE NOT NULL,
            gender ENUM('Male', 'Female', 'Other') NOT NULL,
            cr_number VARCHAR(20) NOT NULL,
            address TEXT,
            city VARCHAR(50),
            country VARCHAR(50),
            status ENUM('active', 'inactive') NOT NULL,
            created_by INT NOT NULL,
            created_on DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php'); //include upgrade.php for accessing the dbDelta() function
        dbDelta($sql);
    }
}
