<?php
/*
Plugin Name: Custom Customer Management
Description: WordPress plugin designed to manage a custom database of customer information, with comprehensive administrative capabilities including adding, editing, deleting, and viewing customer records.
Version: 1.0
Author: SHOBHITH N
*/

// plugin activation hook and create seperate table 
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

require_once (plugin_dir_path(__FILE__) . 'includes/add_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/view_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/edit_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/delete_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/list_customers.php');

function custom_customer_records_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Handle actions (add, edit, delete)
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    switch ($action) {
        case 'add':
            custom_add_customer();
            break;
        case 'view':
            custom_view_customer();
            break;
        case 'edit':
            custom_edit_customer();
            break;
        case 'delete':
            custom_delete_customer();
            break;
        default:
            custom_list_customers();
            break;
    }
}

function custom_customer_menu()
{
    add_menu_page(
        'Customer Records',
        'Customer Records',
        'manage_options',
        'custom-customer-records',
        'custom_customer_records_page',
        'dashicons-businessman',
        30
    );
}

add_action('admin_menu', 'custom_customer_menu');