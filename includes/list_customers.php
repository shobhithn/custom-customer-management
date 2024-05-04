<?php
function custom_list_customers()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'customers'; // customers is the table name

    $query = "SELECT * FROM $table_name";
    $customers = $wpdb->get_results($query);
    print_r($customers);

}
