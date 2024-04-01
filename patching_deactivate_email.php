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
        <h3 class="col-auto flex-grow-1"><b>Patching - Menu Deaktivasi Email Notifikasi</b></h3>
    </div>
    <hr>
    <!-- Looking SMS Notification Data -->
    <div class="card">
        <div class="container py-5">
            <h4 class="mb-4"><strong>Looking for Email Notification Data</strong></h4>
            <div class="row">
                <!-- Hide row phone number -->
                <div class="col-md-6 d-none">
                    <div class="form-group mb-3">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" placeholder="Enter phone number">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="account">Account Number*</label>
                        <input type="text" class="form-control" id="account" placeholder="Enter account number">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="email">Email Address*</label>
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
    <!-- Result Email Notification Data -->
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="mb-4"><strong>Result of Email Notification Data</strong></h4>
            <div>
                <button id="exportExcelBtn" class="btn btn-sm btn-success rounded-0" type="button"
                    style="display: none;"><i class="fa fa-file-excel"></i> Export to Excel</button>
            </div>
        </div>
        <div class="card-body">
            <div id="smsNotificationResult"></div>
        </div>
    </div>


    <br>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure to patch this data?</h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="" id="user-form">
                            <input type="hidden" name="id" value="">
                            <div class="form-group">
                                <label for="textWarn" class="control-label"><strong>Here's the information of the data
                                        that you want to patch the Email Status to be 0</strong></label>
                                <br>
                                <br>
                            </div>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Yes</button>
                </div>
            </div>
        </div>
    </div>



    <div id="successPopup" class="success-popup">
        <h4>Patching Complete</h4>
        <p>The Email notification has been successfully updated.</p>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>


    <script>
        var usernameGlobal = '<?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "" ?>';
        console.log(usernameGlobal);

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

        function searchSMSNotification() {
            // Get search parameters
            var phone = document.getElementById('phone').value.trim();
            var account = document.getElementById('account').value.trim();
            var email = document.getElementById('email').value.trim();

            // Check if any of the fields are empty
            if (phone === "" && account === "" && email === "") {
                alert("Please enter the required information in the column provided.");
                return;
            }

            // Check if any of the fields are empty
            if (email === "") {
                alert("Please enter the email number.");
                return;
            }

            if (account === "") {
                alert("Please enter the account number.");
                return;
            }


            if (account.charAt(0) === "0") {
                account = account.replace(/^0+/, '');
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
            tableHtml += '<thead><tr><th>Registration Date</th><th>Username Registration</th><th>Account Number</th><th>Email</th><th>Phone Number</th><th>Username Update</th><th>Email Status</th><th>Action</th></tr></thead>';
            tableHtml += '<tbody>';

            data.forEach(function (row) {
                tableHtml += '<tr>';
                tableHtml += '<td>' + row.date_reg + '</td>';
                tableHtml += '<td>' + row.username_reg + '</td>';
                tableHtml += '<td>' + row.rekening + '</td>';
                tableHtml += '<td>' + row.email + '</td>';
                tableHtml += '<td>' + row.phone_number + '</td>';
                tableHtml += '<td>' + row.username_update + '</td>';
                tableHtml += '<td>' + row.status_email + '</td>';
                tableHtml += '<td><button class="btn btn-primary run_sms" data-username="' + row.username_update + '" data-phone="' + row.phone_number + '" data-rekening="' + row.rekening + '">Patch</button></td>';
                tableHtml += '</tr>';
            });

            tableHtml += '</tbody></table>';

            document.getElementById('smsNotificationResult').innerHTML = tableHtml;

            // Disable Patch buttons where status_email is 0
            var buttons = document.querySelectorAll('.run_sms');
            buttons.forEach(function (button) {
                var status_email = button.parentElement.previousElementSibling.textContent;
                if (status_email === '0') {
                    button.disabled = true;
                }
                if (status_email === '1') {
                    button.removeEventListener('click', showModal); // Remove previous event listeners to prevent duplication
                    button.addEventListener('click', showModal);
                }
            });


            // Show the Export to Excel button after the table is rendered
            document.getElementById('exportExcelBtn').style.display = 'inline-block';
        }

        function exportToExcel() {
            var wb = XLSX.utils.table_to_book(document.getElementById('smsNotificationResult'), { sheet: "Sheet JS" }); // Convert table to workbook
            XLSX.writeFile(wb, 'email_notification_data.xlsx'); // Save workbook as Excel file with name 'email_notification_data.xlsx'
        }

        // Bind the exportToExcel function to the click event of the export Excel button
        document.getElementById('exportExcelBtn').addEventListener('click', exportToExcel);


        function showModal() {
            var phoneNumber = this.getAttribute('data-phone');
            var accountNumber = this.getAttribute('data-rekening');

            // Show alert to input usernameUpdate
            var usernameUpdate = prompt("Enter Username Update:");
            if (usernameUpdate === null || usernameUpdate.trim() === '') {
                return; // Stop further execution if usernameUpdate is empty or null
            }

            // Populate modal fields with data from the row
            document.querySelector('#myModal input[name="phone_number"]').value = phoneNumber;
            document.querySelector('#myModal input[name="account_number"]').value = accountNumber;
            document.querySelector('#myModal input[name="username_update"]').value = usernameUpdate;
            // Disable input fields
            document.querySelector('#myModal input[name="username_update"]').readOnly = true;
            document.querySelector('#myModal input[name="phone_number"]').readOnly = true;
            document.querySelector('#myModal input[name="account_number"]').readOnly = true;

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

            saveLog("Do Patching: Deactivate Email Notification. Username update: " + usernameUpdate + ", Phone number: " + phoneNumber + ", Account number: " + accountNumber);
        });

        // Handle Cancel button click inside the modal
        document.querySelector('#myModal button[data-dismiss="modal"]').addEventListener('click', function () {
            // Reset modal fields if needed
        });




        function updateStatusSMS(usernameUpdate, phoneNumber, accountNumber, callback) {
            // AJAX request to update status_sms
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./Actions.php?a=update_status_email", true);
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
            saveLog('User Searching: Email Notification Data: ' + document.getElementById('phone').value + ' ' + document.getElementById('account').value + ' ' + document.getElementById('email').value);

            // Call searchSMSNotification function
            searchSMSNotification();
        }
    </script>