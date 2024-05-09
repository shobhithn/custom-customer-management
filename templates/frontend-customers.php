<!-- Active Customers -->
<div class="frontend-customers">
    <h2>Active Customers</h2>
    <div class="search-form">
        <input type="text" id="customer-search" placeholder="Search customers using name, email or phone number">
    </div>
    <table id="customer-table" class="wp-list-table widefat fixed striped customer-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th>Age</th>
                <th>Gender</th>
                <th>CR Number</th>
                <th>Address</th>
                <th>City</th>
                <th>Country</th>
            </tr>
        </thead>
        <tbody id="customer-list"></tbody>
    </table>
    <div id="pagination"></div>
</div>