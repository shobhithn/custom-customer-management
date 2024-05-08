<?php
/*
Plugin Name: Custom Customer Management
Description: WordPress plugin designed to manage a custom database of customer information, with comprehensive administrative capabilities including adding, editing, deleting, and viewing customer records.
Version: 1.0
Author: SHOBHITH N
*/


// plugin activation hook and create seperate table 
register_activation_hook(__FILE__, 'customer_plugin_activate');

function customer_plugin_activate()
{
    global $wpdb;

    // define customer table name
    $table_name = $wpdb->prefix . 'customers';

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
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// loading all files required for CRUD operation
require_once (plugin_dir_path(__FILE__) . 'includes/add_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/view_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/edit_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/delete_customer.php');
require_once (plugin_dir_path(__FILE__) . 'includes/list_customers.php');

// medu creation at admin dashboard
function customer_menu()
{
    add_menu_page(
        'Customer Records',
        'Customer Records',
        'manage_options',
        'customer-records',
        'customer_records_page',
        'dashicons-groups',
        30
    );
}

add_action('admin_menu', 'customer_menu');

// loading css and script files for admin dashboard
function my_crud_plugin_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_style('my-crud-plugin-style', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('my-crud-plugin-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('admin_enqueue_scripts', 'my_crud_plugin_scripts');

// loading css and script files for shortcode
function my_plugin_enqueue_scripts()
{
    wp_enqueue_style('my-plugin-style', plugin_dir_url(__FILE__) . 'public/css/style.css', array(), '1.0.0', 'all');
    wp_enqueue_script('my-plugin-script', plugin_dir_url(__FILE__) . 'public/js/script.js', array('jquery'), '1.0.0', true);
     $ajax_url = admin_url('admin-ajax.php');
     wp_localize_script('my-plugin-script', 'my_ajax_object', array('ajax_url' => $ajax_url));
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_scripts');

// handling actions (add, view, edit and list)
function customer_records_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    switch ($action) {
        case 'add':
            add_customer();
            break;
        case 'view':
            view_customer();
            break;
        case 'edit':
            edit_customer();
            break;
        default:
            list_customers();
            break;
    }
}

// creating shortcode to dipslay customers
function display_active_customers_shortcode()
{
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/frontend-customers.php';
    return ob_get_clean();
}
add_shortcode('display_active_customers', 'display_active_customers_shortcode');

function get_active_customers_ajax()
{
    global $wpdb;

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    $per_page = 15; // setting pagination per page item
    $offset = ($page - 1) * $per_page;

    // define customer table name
    $table_name = $wpdb->prefix . 'customers';

    $total_customers_sql = "SELECT COUNT(*) FROM $table_name WHERE status = 'active'";
    if (!empty($search)) {
        $total_customers_sql .= $wpdb->prepare(" AND (name LIKE %s OR email LIKE %s OR phone_number LIKE %s)", "%{$search}%", "%{$search}%", "%{$search}%");
    }
    $total_customers = $wpdb->get_var($total_customers_sql);

    $total_pages = ceil($total_customers / $per_page);

    $sql = "SELECT * FROM $table_name WHERE status = 'active'";
    if (!empty($search)) {
        $sql .= $wpdb->prepare(" AND (name LIKE %s OR email LIKE %s OR phone_number LIKE %s)", "%{$search}%", "%{$search}%", "%{$search}%");
    }
    $sql .= " LIMIT $per_page OFFSET $offset";

    $customers = $wpdb->get_results($sql);

    ob_start();
    if ($customers) {
        foreach ($customers as $customer) {
            $age = date_diff(date_create($customer->dob), date_create(date("Y-m-d")));
            $dob = strtotime($customer->dob);
            $dob = date('d-m-Y', $dob);

            echo '<tr>';
            echo '<td>' . $customer->name . '</td>';
            echo '<td>' . $customer->email . '</td>';
            echo '<td>' . $customer->phone_number . '</td>';
            echo '<td>' . $dob . '</td>';
            echo '<td>' . $age->y . '</td>';
            echo '<td>' . $customer->gender . '</td>';
            echo '<td>' . $customer->cr_number . '</td>';
            echo '<td>' . $customer->address . '</td>';
            echo '<td>' . $customer->city . '</td>';
            echo '<td>' . $customer->country . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10">No customers found.</td></tr>';
    }
    $customers_html = ob_get_clean();

    wp_send_json_success(
        array(
            'customers' => $customers_html,
            'totalPages' => $total_pages
        )
    );
}
add_action('wp_ajax_get_active_customers', 'get_active_customers_ajax');