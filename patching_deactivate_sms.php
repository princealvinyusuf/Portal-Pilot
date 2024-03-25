<?php

// Check if the user is logged in
if (!isset ($_SESSION['id']) || $_SESSION['id'] <= 0) {
    header("Location: ./login.php");
    exit;
}

// Check user access level
if (!isset($_SESSION['access_level']) || !in_array($_SESSION['access_level'], ['Administrator', 'Engineer'])) {
    // Display the unauthorized message
    echo '<p style="font-weight: bold; font-size: 18px; text-align: center;">Unauthorized User</p>';
    // Optionally, you may want to include additional HTML or redirect the user
    exit;
}

?>


<style>
    .success-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #228B22;
        /* Blue color */
        color: #fff;
        /* Black text color */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        display: none;
    }
</style>

<div class="container py-5">
    <div class="d-flex w-100">
        <h3 class="col-auto flex-grow-1"><b>Patching - Menu Deaktivasi SMS Notifikasi</b></h3>
    </div>
    <hr>
    <!-- Looking SMS Notification Data -->
    <div class="card">
        <div class="container py-5">
            <h4 class="mb-4"><strong>Looking for SMS Notification Data</strong></h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" placeholder="Enter phone number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="account">Account Number</label>
                        <input type="text" class="form-control" id="account" placeholder="Enter account number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email address">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-md-end">
                        <button type="button" class="btn btn-primary" onclick="searchAndSave()">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>
    <!-- Result SMS Notification Data -->
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4"><strong>Result of SMS Notification Data</strong></h4>
            <div id="smsNotificationResult"></div>
        </div>
    </div>

    <br>
    <!-- Deactivate SMS Notification -->

    <div class="card">
        <div class="container py-5">
            <h4 class="mb-4"><strong>Action - Deactivate SMS Notification</strong></h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="username_update">Username Update</label>
                        <input type="text" class="form-control" id="username_update" placeholder="Enter username">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="phone_act">Phone Number</label>
                        <input type="text" class="form-control" id="phone_act" placeholder="Enter phone number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="account_act">Account Number</label>
                        <input type="text" class="form-control" id="account_act" placeholder="Enter account number">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-md-end">
                        <button type="button" class="btn btn-primary" onclick="validateForm()">Patching SMS
                            Notification</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="successPopup" class="success-popup">
        <h4>Patching Complete</h4>
        <p>The SMS notification has been successfully updated.</p>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Patching this data?</h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="" id="user-form">
                            <input type="hidden" name="id" value="">
                            <div class="form-group">
                                <label for="phone_number" class="control-label">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control form-control-sm rounded-0"
                                    value="" readonly>
                            </div>
                            <div class="form-group">
                                <label for="account_number" class="control-label">Account Number</label>
                                <input type="text" name="account_number" class="form-control form-control-sm rounded-0"
                                    value="" readonly>
                            </div>
                            <div class="form-group">
                                <label for="username_update" class="control-label">Username Update</label>
                                <input type="text" name="username_update" class="form-control form-control-sm rounded-0"
                                    value="">
                            </div>
                            <!-- Add other form fields as needed -->
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Process</button>
                </div>
            </div>
        </div>
    </div>




    <script>

        function saveLog(queryAction) {
            // AJAX request to save_log before submitting the form
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./Actions.php?a=save_log", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // If the log is successfully saved, submit the form to run the query
                    // document.getElementById("runQueryForm_" + queryId).submit();
                }
            };
            xhr.send("a=save_log&user_id=<?php echo $_SESSION['id']; ?>&action_made=" + queryAction);

        }

        function validateForm() {
            var username = document.getElementById('username_update').value;
            var phone = document.getElementById('phone_act').value;
            var account = document.getElementById('account_act').value;

            if (username === '' || phone === '' || account === '') {
                alert('Please completely fill the form');
                return;
            }

            // Regular expressions for validation
            var usernameRegex = /^[a-zA-Z0-9\s!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]+$/;
            var phoneRegex = /^\d+$/;
            var accountRegex = /^\d+$/;

            // Validation checks
            if (!usernameRegex.test(username)) {
                alert('Please fill the username field with letters, numbers, and special characters only.');
                return;
            }
            if (!phoneRegex.test(phone)) {
                alert('Please fill the phone number field with only numbers.');
                return;
            }
            if (phone.substring(0, 2) !== '08') {
                alert('The phone number entered is incorrect.');
                return;
            }
            if (!accountRegex.test(account)) {
                alert('Please fill the account number field with only numbers.');
                return;
            }

            // Show confirmation popup
            var confirmationMessage = "Are you sure to process the patching with the complete information below:\n" +
                "\nUsername Update: " + username + "\n" +
                "Phone Number: " + phone + "\n" +
                "Account Number: " + account;
            if (confirm(confirmationMessage)) {
                // If user confirms, proceed with the update
                updateStatusSMS(username, phone, account, function (success) {
                    // Clear input fields after successful processing
                    if (success) {
                        document.getElementById('username_update').value = '';
                        document.getElementById('phone_act').value = '';
                        document.getElementById('account_act').value = '';
                    }
                });

                saveLog("Do Patching: Deactivate SMS Notification. Username update: " + username + ", Phone number: " + phone + ", Account number: " + account);

            }
        }



        function searchSMSNotification() {
            // Get search parameters
            var phone = document.getElementById('phone').value.trim();
            var account = document.getElementById('account').value.trim();
            var email = document.getElementById('email').value.trim();

            // Check if any of the fields are empty
            if (phone === "" && account === "" && email === "") {
                alert("Please enter the required information in one of the columns provided.");
                return;
            }

            // AJAX request to search SMS notification data
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "./Actions.php?a=search_sms_notification&phone=" + phone + "&account=" + account + "&email=" + email, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        displaySMSNotificationResult(response.data);
                    } else {
                        alert("Failed to retrieve SMS notification data.");
                    }
                }
            };
            xhr.send();
        }


        function displaySMSNotificationResult(data) {
            if (data.length === 0) {
                document.getElementById('smsNotificationResult').innerHTML = '<p style="text-align: center; color: blue;"><strong>No Data Found</strong></p>';
                return;
            }

            var tableHtml = '<table class="table table-bordered table-striped table-hover">';
            tableHtml += '<thead><tr><th>Registration Date</th><th>Username Registration</th><th>Account Number</th><th>Email</th><th>Phone Number</th><th>Username Update</th><th>SMS Status</th><th>Email Status</th><th>WA Status</th><th>Action</th></tr></thead>';
            tableHtml += '<tbody>';

            data.forEach(function (row) {
                tableHtml += '<tr>';
                tableHtml += '<td>' + row.date_reg + '</td>';
                tableHtml += '<td>' + row.username_reg + '</td>';
                tableHtml += '<td>' + row.rekening + '</td>';
                tableHtml += '<td>' + row.email + '</td>';
                tableHtml += '<td>' + row.phone_number + '</td>';
                tableHtml += '<td>' + row.username_update + '</td>';
                tableHtml += '<td>' + row.status_sms + '</td>';
                tableHtml += '<td>' + row.status_email + '</td>';
                tableHtml += '<td>' + row.status_wa + '</td>';
                tableHtml += '<td><button class="btn btn-primary run_sms" data-username="' + row.username_update + '" data-phone="' + row.phone_number + '" data-rekening="' + row.rekening + '">Patch</button></td>';
                tableHtml += '</tr>';
            });

            tableHtml += '</tbody></table>';

            document.getElementById('smsNotificationResult').innerHTML = tableHtml;

            // Add event listener to "Run" buttons
            document.querySelectorAll('.run_sms').forEach(function (btn) {
                btn.removeEventListener('click', showModal); // Remove previous event listeners to prevent duplication
                btn.addEventListener('click', showModal);
            });
        }


        function showModal() {
            var usernameUpdate = this.getAttribute('data-username');
            var phoneNumber = this.getAttribute('data-phone');
            var rekening = this.getAttribute('data-rekening');

            // Populate modal fields with data from the row
            document.querySelector('#myModal input[name="phone_number"]').value = phoneNumber;
            document.querySelector('#myModal input[name="account_number"]').value = rekening;

            // Show the modal
            $('#myModal').modal('show');
        }

        // Handle Ok button click inside the modal
        // Handle Ok button click inside the modal
        document.getElementById('saveChangesBtn').addEventListener('click', function () {
            // Retrieve data from modal form
            var usernameUpdateInput = document.querySelector('#myModal input[name="username_update"]');
            var phoneNumberInput = document.querySelector('#myModal input[name="phone_number"]');
            var accountNumberInput = document.querySelector('#myModal input[name="account_number"]');

            var usernameUpdate = usernameUpdateInput.value;
            var phoneNumber = phoneNumberInput.value;
            var accountNumber = accountNumberInput.value;

            // Validate username update field
            if (usernameUpdate.trim() === '') {
                usernameUpdateInput.classList.add('is-invalid'); // Add 'is-invalid' class to indicate error
                return; // Stop further execution
            } else {
                usernameUpdateInput.classList.remove('is-invalid'); // Remove 'is-invalid' class if previously added
            }

            // Call updateStatusSMS function
            updateStatusSMS(usernameUpdate, phoneNumber, accountNumber, function (success) {
                // Clear input fields after successful processing
                if (success) {
                    usernameUpdateInput.value = '';
                    phoneNumberInput.value = '';
                    accountNumberInput.value = '';
                }
            });

            // Close the modal
            $('#myModal').modal('hide');

            saveLog("Do Patching: Deactivate SMS Notification. Username update: " + usernameUpdate + ", Phone number: " + phoneNumber + ", Account number: " + accountNumber);
        });

        // Handle Cancel button click inside the modal
        document.querySelector('#myModal button[data-dismiss="modal"]').addEventListener('click', function () {
            // Reset modal fields if needed
        });




        function updateStatusSMS(usernameUpdate, phoneNumber, accountNumber, callback) {
            // AJAX request to update status_sms
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./Actions.php?a=update_status_sms", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            // Show success popup
                            document.getElementById('successPopup').style.display = 'block';

                            // Hide success popup after 3 seconds
                            setTimeout(function () {
                                document.getElementById('successPopup').style.display = 'none';
                            }, 3000);

                            // Reload searchSMSNotification after success
                            searchSMSNotification();

                            // Call the callback function with success flag
                            if (typeof callback === 'function') {
                                callback(true);
                            }
                        } else {
                            // Handle failure
                            console.error(response.message);
                            // You can optionally show an error message here
                            // Call the callback function with failure flag
                            if (typeof callback === 'function') {
                                callback(false);
                            }
                        }
                    } else {
                        // Handle network errors
                        console.error('Network error occurred');
                        // Call the callback function with failure flag
                        if (typeof callback === 'function') {
                            callback(false);
                        }
                    }
                }
            };
            xhr.send("username_update=" + usernameUpdate + "&phone_number=" + phoneNumber + "&rekening=" + accountNumber);
        }

        function searchAndSave() {
            // Call saveLog function
            saveLog('User Searching - SMS Notification Data: ' + 'phone number:' + ' ' + document.getElementById('phone').value + ' account number:' + document.getElementById('account').value + ' email address:' + document.getElementById('email').value);

            // Call searchSMSNotification function
            searchSMSNotification();
        }
    </script>