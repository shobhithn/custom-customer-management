<?php
function delete_customer()
{
    global $wpdb;

    // define customer table name
    $table_name = $wpdb->prefix . 'customers';

    $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : 0;

    if ($customer_id > 0) {
        $wpdb->delete($table_name, array('id' => $customer_id));
        wp_send_json_success('Customer deleted successfully!');
    } else {
        wp_send_json_error('Customer ID is missing!');
    }
}
add_action('wp_ajax_delete_customer', 'delete_customer');
