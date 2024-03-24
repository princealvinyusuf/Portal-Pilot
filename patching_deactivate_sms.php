<style>
    .success-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #ff8000;
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
                        <button type="button" class="btn btn-primary" onclick="searchAndSave()">Access</button>
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
                updateStatusSMS(username, phone, account);
            }
        }


        function searchSMSNotification() {
            // Get search parameters
            var phone = document.getElementById('phone').value;
            var account = document.getElementById('account').value;
            var email = document.getElementById('email').value;

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
                tableHtml += '<td><button class="btn btn-primary run_sms" data-username="' + row.username_update + '" data-phone="' + row.phone_number + '" data-rekening="' + row.rekening + '">Run</button></td>';
                tableHtml += '</tr>';
            });

            tableHtml += '</tbody></table>';

            document.getElementById('smsNotificationResult').innerHTML = tableHtml;

            // Define the updateStatusSMS function
            function updateStatusSMS(usernameUpdate, phoneNumber, rekening) {
                // AJAX request to update status_sms
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "./Actions.php?a=update_status_sms", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            // Handle success
                            console.log(response.message);
                        } else {
                            // Handle failure
                            console.error(response.message);
                        }
                    }
                };
                xhr.send("username_update=" + usernameUpdate + "&phone_number=" + phoneNumber + "&rekening=" + rekening);
            }

            // Add event listener to "Run" buttons
            document.querySelectorAll('.run_sms').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var usernameUpdate = this.getAttribute('data-username');
                    var phoneNumber = this.getAttribute('data-phone');
                    var rekening = this.getAttribute('data-rekening');

                    // Call the updateStatusSMS function
                    updateStatusSMS(usernameUpdate, phoneNumber, rekening);
                });
            });
        }

        function updateStatusSMS(usernameUpdate, phoneNumber, accountNumber) {
            // AJAX request to update status_sms
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./Actions.php?a=update_status_sms", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
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
                    } else {
                        // Handle failure
                        console.error(response.message);
                        // You can optionally show an error message here
                    }
                }
            };
            xhr.send("username_update=" + usernameUpdate + "&phone_number=" + phoneNumber + "&rekening=" + accountNumber);
        }


        function searchAndSave() {
            // Call saveLog function
            saveLog('User Searching: SMS Notification Data: ' + document.getElementById('phone').value + ' ' + document.getElementById('account').value + ' ' + document.getElementById('email').value);

            // Call searchSMSNotification function
            searchSMSNotification();
        }
    </script>