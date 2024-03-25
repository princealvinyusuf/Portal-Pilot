<?php

// Check if the user is logged in
if (!isset ($_SESSION['id']) || $_SESSION['id'] <= 0) {
    header("Location: ./login.php");
    exit;
}

// Check user access level
if (!isset ($_SESSION['access_level']) || !in_array($_SESSION['access_level'], ['Administrator', 'Engineer'])) {
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
        <h3 class="col-auto flex-grow-1"><b>Patching - Bulking SMS Notifikasi</b></h3>
    </div>
    <hr>

    <!-- Upload file -->

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Upload Excel File</h5>
                <form id="uploadForm">
                    <div class="form-group">
                        <label for="fileInput">Choose Excel File</label>
                        <input type="file" class="form-control-file" id="fileInput" accept=".xlsx, .xls" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>


    <br>
    <!-- Result SMS Notification Data -->
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4"><strong>Result of SMS Notification Data</strong></h4>
            <div id="smsNotificationResult">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Registration Date</th>
                            <th>Username Registration</th>
                            <th>Account Number</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Username Update</th>
                            <th>SMS Status</th>
                            <th>Email Status</th>
                            <th>WA Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table body content will be appended here -->
                    </tbody>
                </table>
            </div>
            <!-- Button to execute all patching process -->
            <button id="executePatchingBtn" class="btn btn-success" style="display: none;">Execute all patching process
                now</button>
        </div>
    </div>


    <br>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


    <script>

        document.getElementById('uploadForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission

            var fileInput = document.getElementById('fileInput');
            var file = fileInput.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, { type: 'array' });
                    var sheetName = workbook.SheetNames[0];
                    var worksheet = workbook.Sheets[sheetName];
                    var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                    // Process the data
                    processData(jsonData);
                };
                reader.readAsArrayBuffer(file);
            }
        });

        function processData(data) {
            // Assuming each row of data has columns in the order: phone number, account number, email address
            for (var i = 1; i < data.length; i++) { // Start from index 1 to skip header row
                var phoneNumber = data[i][0];
                var accountNumber = data[i][1];
                var emailAddress = data[i][2];

                // Remove leading zeros from accountNumber if it starts with "0"
                if (accountNumber.charAt(0) === "0") {
                    accountNumber = accountNumber.replace(/^0+/, '');
                }

                // Call searchSMSNotification with current row data
                searchSMSNotification(phoneNumber, accountNumber, emailAddress);
            }

            // Provide feedback to the user
            alert('Bulk processing completed.');
        }


        function searchSMSNotification(phone, account, email) {
            // Check if any of the fields are empty
            if (phone === "" && account === "" && email === "") {
                alert("Please enter the required information in one of the columns provided.");
                return;
            }

            // Replace "XXX" with a wildcard character (e.g., % in SQL) for database search
            var accountForSearch = account.replace(/X/g, '%');

            // AJAX request to search SMS notification data
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "./Actions.php?a=search_sms_notification&phone=" + phone + "&account=" + accountForSearch + "&email=" + email, true);
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


        function displaySMSNotificationResult(data) {
            if (data.length === 0) {
                document.getElementById('smsNotificationResult').innerHTML = '<p style="text-align: center; color: blue;"><strong>No Data Found</strong></p>';
                return;
            }

            var tableBody = document.querySelector('#smsNotificationResult table tbody');

            data.forEach(function (row) {
                var newRow = '<tr>';
                newRow += '<td>' + row.date_reg + '</td>';
                newRow += '<td>' + row.username_reg + '</td>';
                newRow += '<td>' + row.rekening + '</td>';
                newRow += '<td>' + row.email + '</td>';
                newRow += '<td>' + row.phone_number + '</td>';
                newRow += '<td>' + row.username_update + '</td>';
                newRow += '<td>' + row.status_sms + '</td>';
                newRow += '<td>' + row.status_email + '</td>';
                newRow += '<td>' + row.status_wa + '</td>';
                newRow += '<td><button class="btn btn-primary run_sms" data-username="' + row.username_update + '" data-phone="' + row.phone_number + '" data-rekening="' + row.rekening + '">Patch</button></td>';
                newRow += '</tr>';
                tableBody.insertAdjacentHTML('beforeend', newRow);
            });

            // Show the button after the data table is displayed
            document.getElementById('executePatchingBtn').style.display = 'block';

            // Add event listener to "Run" buttons
            document.querySelectorAll('.run_sms').forEach(function (btn) {
                btn.removeEventListener('click', showModal); // Remove previous event listeners to prevent duplication
                btn.addEventListener('click', showModal);
            });
        }

        // Event listener for the "Execute all patching process" button
        // Fixing in here
        document.getElementById('executePatchingBtn').addEventListener('click', function () {
            // Call the updateStatusSMS function for each row in the table
            document.querySelectorAll('#smsNotificationResult table tbody tr').forEach(function (row) {
                var usernameUpdate = row.querySelector('[data-username]').getAttribute('data-username');
                var phoneNumber = row.querySelector('[data-phone]').getAttribute('data-phone');
                var accountNumber = row.querySelector('[data-rekening]').getAttribute('data-rekening');

                // Call updateStatusSMS function for each row
                updateStatusSMS(usernameUpdate, phoneNumber, accountNumber, function (success) {
                    if (success) {
                        // Optional: Update UI or perform other actions upon success
                        console.log('Status updated successfully for:', usernameUpdate);
                    } else {
                        // Optional: Handle failure case
                        console.error('Failed to update status for:', usernameUpdate);
                    }
                });
            });
        });




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


    </script>