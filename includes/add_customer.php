<?php
function add_customer()
{
    global $wpdb;

    // define customer table name
    $table_name = $wpdb->prefix . 'customers';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        $existing_user = email_exists($email); // checking email in user table

        if (!$existing_user) {
            // creating new user 
            $username = strtolower(str_replace(' ', '', $name)); // username by customer name
            $password = $phone_number; // password by customer phone number
            $user_id = wp_create_user($username, $password, $email);

            if (!is_wp_error($user_id)) {
                $user = new WP_User($user_id);
                $user->set_role('contributor'); // setting role as contributor

                // creating new customer
                $wpdb->insert($table_name, array(
                    'name' => $name,
                    'email' => $email,
                    'phone_number' => $phone_number,
                    'dob' => $dob,
                    'gender' => $gender,
                    'cr_number' => $cr_number,
                    'address' => $address,
                    'city' => $city,
                    'country' => $country,
                    'status' => $status,
                )
                );

                echo '<div class="updated"><p>New customer added successfully!</p></div>';
            } else {
                echo '<div class="error"><p>Error creating WordPress user.</p></div>';
            }
        } else {
            echo '<div class="error"><p>Email already exists in WordPress user list.</p></div>';
        }
    }

    ?>

    <!-- Add New Customer Form -->
    <div class="wrap">
        <h1>Add New Customer</h1>
        <form method="post" action="" class="customer-form">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="phone_number">Phone Number:</label><br>
            <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{10}" required><br>

            <label for="dob">Date of Birth:</label><br>
            <input type="date" id="dob" name="dob" required><br>

            <label for="age">Age</label><br>
            <input type="text" id="age" name="age" readonly><br>

            <label for="gender">Gender:</label><br>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br>

            <label for="cr_number">CR Number:</label><br>
            <input type="text" id="cr_number" name="cr_number" required><br>

            <label for="address">Address:</label><br>
            <textarea id="address" name="address"></textarea><br>

            <label for="city">City:</label><br>
            <input type="text" id="city" name="city"><br>

            <label for="country">Country:</label><br>
            <input type="text" id="country" name="country"><br>

            <label for="status">Status:</label><br>
            <select id="status" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <input type="submit" value="Add Customer" class="button-primary">
        </form>
    </div>
    <?php
}
?>