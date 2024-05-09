<?php
function edit_customer()
{
    global $wpdb;

    // define customer table name
    $table_name = $wpdb->prefix . 'customers';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customer_id = isset($_POST["customer_id"]) ? $_POST["customer_id"] : 0;
        $name = isset($_POST["name"]) ? $_POST["name"] : '';
        $email = isset($_POST["email"]) ? $_POST["email"] : '';
        $phone_number = isset($_POST["phone_number"]) ? $_POST["phone_number"] : '';
        $dob = isset($_POST["dob"]) ? $_POST["dob"] : '';
        $gender = isset($_POST["gender"]) ? $_POST["gender"] : '';
        $cr_number = isset($_POST["cr_number"]) ? $_POST["cr_number"] : '';
        $address = isset($_POST["address"]) ? $_POST["address"] : '';
        $city = isset($_POST["city"]) ? $_POST["city"] : '';
        $country = isset($_POST["country"]) ? $_POST["country"] : '';
        $status = isset($_POST["status"]) ? $_POST["status"] : '';

        //updating customer
        $wpdb->update(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone_number,
                'dob' => $dob,
                'gender' => $gender,
                'cr_number' => $cr_number,
                'address' => $address,
                'city' => $city,
                'country' => $country,
                'status' => $status
            ),
            array('id' => $customer_id)
        );

        echo '<div class="updated"><p>Customer updated successfully!</p></div>';
    }

    // getting customer detail using customer id 
    $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 0;
    $customer = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $customer_id));

    if (!$customer) {
        echo '<div class="error"><p>Customer not found!</p></div>';
        return;
    }

    ?>

    <!-- Edi Customer Form -->
    <div class="wrap">
        <h1>Edit Customer</h1>
        <form method="post" action="" class="customer-form">
            <input type="hidden" name="customer_id" value="<?php echo $customer->id; ?>">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo $customer->name; ?>" required><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo $customer->email; ?>" required><br>

            <label for="phone_number">Phone Number:</label><br>
            <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{10}"
                value="<?php echo $customer->phone_number; ?>" required><br>

            <label for="dob">Date of Birth:</label><br>
            <input type="date" id="dob" name="dob" value="<?php echo $customer->dob; ?>" required><br>

            <label for="age">Age</label><br>
            <input type="text" id="age" name="age" readonly><br>

            <label for="gender">Gender:</label><br>
            <select id="gender" name="gender" required>
                <option value="Male" <?php selected('Male', $customer->gender); ?>>Male</option>
                <option value="Female" <?php selected('Female', $customer->gender); ?>>Female</option>
                <option value="Other" <?php selected('Other', $customer->gender); ?>>Other</option>
            </select><br>

            <label for="cr_number">CR Number:</label><br>
            <input type="text" id="cr_number" name="cr_number" value="<?php echo $customer->cr_number; ?>"
                required><br>

            <label for="address">Address:</label><br>
            <textarea id="address" name="address"><?php echo $customer->address; ?></textarea><br>

            <label for="city">City:</label><br>
            <input type="text" id="city" name="city" value="<?php echo $customer->city; ?>"><br>

            <label for="country">Country:</label><br>
            <input type="text" id="country" name="country" value="<?php echo $customer->country; ?>"><br>

            <label for="status">Status:</label><br>
            <select id="status" name="status">
                <option value="active" <?php selected('active', $customer->status); ?>>Active</option>
                <option value="inactive" <?php selected('inactive', $customer->status); ?>>Inactive</option>
            </select>

            <input type="submit" value="Update Customer" class="button-primary">
        </form>
    </div>
    <?php
}
?>