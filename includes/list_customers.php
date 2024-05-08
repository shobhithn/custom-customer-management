<?php
function list_customers()
{
    global $wpdb;

    // define customer table name
    $table_name = $wpdb->prefix . 'customers';

    // search operation
    $search = isset($_GET['s']) ? $_GET['s'] : '';

    $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'id';
    $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

    $per_page = 15; // setting pagination per page item
    $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($page - 1) * $per_page;

    $query = "SELECT * FROM $table_name";
    if (!empty($search)) {
        $query .= " WHERE name LIKE %s OR email LIKE %s OR phone_number LIKE %s";
        $query = $wpdb->prepare($query, "%{$search}%", "%{$search}%", "%{$search}%");
    }
    $query .= " ORDER BY $orderby $order LIMIT %d, %d";
    $prepared_query = $wpdb->prepare($query, $offset, $per_page);
    $customers = $wpdb->get_results($prepared_query);

    $total_customers_query = "SELECT COUNT(id) FROM $table_name";
    if (!empty($search)) {
        $total_customers_query .= " WHERE name LIKE %s OR email LIKE %s OR phone_number LIKE %s";
        $total_customers_query = $wpdb->prepare($total_customers_query, "%{$search}%", "%{$search}%", "%{$search}%");
    }
    $total_customers = $wpdb->get_var($total_customers_query);

    ?>

    <!-- Customers View Table -->
    <div class="wrap">
        <div class="title-and-btn">
            <h1>Customer Records</h1>
            <a href="?page=customer-records&action=add" class="page-title-action">Add New Customer</a>
            <?php if (!empty($search)): ?>
                <p class="seach-result">Search results for: <strong><?php echo $search; ?></strong></p>
            <?php endif; ?>
        </div>

        <form method="get" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="page" value="customer-records">
            <p class="search-box">
                <label class="screen-reader-text" for="customer-search-input">Search Customers:</label>
                <input type="search" id="customer-search-input" name="s" value="<?php echo $search; ?>">
                <input type="submit" id="search-submit" class="button" value="Search Customers">
            </p>
        </form>

        <table class="wp-list-table widefat fixed striped customer-records">
            <thead>
                <tr>
                    <th>
                        <a href="<?php echo admin_url('admin.php?page=customer-records&orderby=name&order=' . ($orderby == 'name' && $order == 'ASC' ? 'DESC' : 'ASC')); ?>"> Name <?php echo ($orderby == 'name') ? ($order == 'ASC' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') : ''; ?> </a>
                    </th>
                    <th>
                        <a href="<?php echo admin_url('admin.php?page=customer-records&orderby=email&order=' . ($orderby == 'email' && $order == 'ASC' ? 'DESC' : 'ASC')); ?>"> Email <?php echo ($orderby == 'email') ? ($order == 'ASC' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') : ''; ?> </a>
                    </th>
                    <th>
                        <a  href="<?php echo admin_url('admin.php?page=customer-records&orderby=phone_number&order=' . ($orderby == 'phone_number' && $order == 'ASC' ? 'DESC' : 'ASC')); ?>"> Phone Number <?php echo ($orderby == 'phone_number') ? ($order == 'ASC' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') : ''; ?> </a>
                    </th>
                    <th>
                        <a href="<?php echo admin_url('admin.php?page=customer-records&orderby=gender&order=' . ($orderby == 'gender' && $order == 'ASC' ? 'DESC' : 'ASC')); ?>"> Gender <?php echo ($orderby == 'gender') ? ($order == 'ASC' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') : ''; ?> </a>
                    </th>
                    <th>
                        <a href="<?php echo admin_url('admin.php?page=customer-records&orderby=cr_number&order=' . ($orderby == 'cr_number' && $order == 'ASC' ? 'DESC' : 'ASC')); ?>"> CR Number <?php echo ($orderby == 'cr_number') ? ($order == 'ASC' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') : ''; ?> </a>
                    </th>
                    <th>
                        <a href="<?php echo admin_url('admin.php?page=customer-records&orderby=city&order=' . ($orderby == 'city' && $order == 'ASC' ? 'DESC' : 'ASC')); ?>">
                            City <?php echo ($orderby == 'city') ? ($order == 'ASC' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') : ''; ?> </a>
                    </th>
                    <th>
                        <a href="<?php echo admin_url('admin.php?page=customer-records&orderby=country&order=' . ($orderby == 'country' && $order == 'ASC' ? 'DESC' : 'ASC')); ?>"> Country <?php echo ($orderby == 'country') ? ($order == 'ASC' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>') : ''; ?> </a>
                    </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="8">No customers found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo $customer->name; ?></td>
                            <td><?php echo $customer->email; ?></td>
                            <td><?php echo $customer->phone_number; ?></td>
                            <td><?php echo $customer->gender; ?></td>
                            <td><?php echo $customer->cr_number; ?></td>
                            <td><?php echo $customer->city; ?></td>
                            <td><?php echo $customer->country; ?></td>
                            <td>
                                <a href="?page=customer-records&action=view&customer_id=<?php echo $customer->id; ?>">View</a>
                                |
                                <a href="?page=customer-records&action=edit&customer_id=<?php echo $customer->id; ?>">Edit</a>
                                |
                                <a href="#" class="delete-customer" data-customer-id="<?php echo $customer->id; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        if (!empty($customers)) {
            $total_pages = ceil($total_customers / $per_page);
            if ($total_pages > 1) {
                $page_links = paginate_links(
                    array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo; Previous',
                        'next_text' => 'Next &raquo;',
                        'total' => $total_pages,
                        'current' => $page
                    )
                );
                if ($page_links) {
                    echo '<div class="tablenav">';
                    echo '<div class="tablenav-pages">' . $page_links . '</div>';
                    echo '</div>';
                }
            }
        }
        ?>
    </div>
    <?php
}
?>