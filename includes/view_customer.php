<?php
function view_customer()
{

    global $wpdb;

    // define customer table name
    $table_name = $wpdb->prefix . 'customers';

    $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 0;
    $customer = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $customer_id));

    if (!$customer) {
        echo '<div class="error"><p>Customer not found!</p></div>';
        return;
    }

    ?>

    <!-- View Customer Form -->
    <div class="wrap">
        <h1>View Customer</h1>
        <form method="post" action="" class="customer-form">
            <input type="hidden" name="customer_id" value="<?php echo $customer->id; ?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo $customer->name; ?>" readonly><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo $customer->email; ?>" readonly><br>

            <label for="phone_number">Phone Number:</label><br>
            <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{10}"
                value="<?php echo $customer->phone_number; ?>" readonly><br>

            <label for="dob">Date of Birth:</label><br>
            <input type="date" id="dob" name="dob" value="<?php echo $customer->dob; ?>" readonly><br>

            <label for="age">Age</label><br>
            <input type="text" id="age" name="age" readonly><br>

            <label for="gender">Gender:</label><br>
            <input type="text" id="gender" name="gender" value="<?php echo ucfirst($customer->gender); ?>" readonly>

            <label for="cr_number">CR Number:</label><br>
            <input type="text" id="cr_number" name="cr_number" value="<?php echo $customer->cr_number; ?>"
            readonly><br>

            <label for="address">Address:</label><br>
            <textarea id="address" name="address" readonly><?php echo $customer->address; ?></textarea><br>

            <label for="city">City:</label><br>
            <input type="text" id="city" name="city" value="<?php echo $customer->city; ?>" readonly><br>

            <label for="country">Country:</label><br>
            <input type="text" id="country" name="country" value="<?php echo $customer->country; ?>" readonly><br>

            <label for="status">Status:</label><br>
            <input type="text" id="status" name="status" value="<?php echo ucfirst($customer->status); ?>" readonly>

            <a class="button-primary" href="?page=customer-records&action=edit&customer_id=<?php echo $customer->id; ?>">Edit Customer</a>
        </form>
    </div>
    <?php
}
?>