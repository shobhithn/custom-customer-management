
# Custom Customer Management

WordPress plugin designed to manage a custom database of customer
information, with comprehensive administrative capabilities including adding, editing, deleting, and viewing customer records.

## Requirements

- PHP version 7.4 or greater.
- MySQL version 8.0 or greater OR MariaDB version 10.4 or greater.

## Installation
### Manual Installation

- Download the plugin ZIP file from the [GitHub repository](https://github.com/shobhithn/custom-customer-management/).
- Extract the ZIP file.
- Upload the extracted folder to the `wp-content/plugins` directory of your WordPress installation.
- Activate the plugin from the WordPress admin dashboard.

### WordPress Admin Dashboard

- Log in to your WordPress admin dashboard.
- Navigate to "Plugins" > "Add New".
- Click on the "Upload Plugin" button.
- Choose the plugin ZIP file and click "Install Now".
- Activate the plugin from the WordPress admin dashboard.

## Importing Sample Data

To import sample data into the plugin-created table ({prefix}_customers), follow these steps:

- Prepare the sample data file (`sample_customers_data.txt`) containing valid sample data for your plugin-created table ({prefix}_customers).

- Access your WordPress database using a tool like phpMyAdmin or through your hosting control panel.

- Locate the plugin-created table({prefix}_customers) where you want to import the data.

- Find the option to import data and select the `sample_customers_data.txt` file from your local computer.

- Specify the appropriate import settings and start the import process.

- After the import is complete, verify that the sample data has been successfully imported into the plugin-created table.

## Viewing Active Customers

To view active customers on your website, follow these steps:

- **Place the Shortcode**: In any page or post where you want to display active customers, add the `[display_active_customers]` shortcode.

   Example:
   ```plaintext
   [display_active_customers]