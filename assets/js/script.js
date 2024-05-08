jQuery(document).ready(function ($) {
    // calculate age based on Date of Birth
    function calculateAge() {
        var dobValue = $('#dob').val();

        if (dobValue) {
            var dob = new Date(dobValue);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            $('#age').val(age);
        } else {
            $('#age').val('');
        }
    }

    $('#dob').on('change', calculateAge);

    calculateAge();

    //delete operation using ajax
    $('.delete-customer').on('click', function (e) {
        e.preventDefault();
        var confirmDelete = confirm('Are you sure you want to delete this customer?');
        if (confirmDelete) {
            var $row = $(this).closest('tr');
            var customerId = $(this).data('customer-id');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_customer',
                    customer_id: customerId
                },
                success: function (response) {
                    alert(response.data);
                    if (response.success) {
                        $row.remove();
                    }
                }
            });
        }
    });
});
