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
            // Assuming each row of data has columns in the order: phone number, account number, email address, username update
            for (var i = 1; i < data.length; i++) { // Start from index 1 to skip header row
                var phoneNumber = data[i][0];
                var accountNumber = data[i][1];
                var emailAddress = data[i][2];
                var usernameUpdate = data[i][3]; // Assuming "username update" is the 4th column

                // Remove leading zeros from accountNumber if it starts with "0"
                if (accountNumber.charAt(0) === "0") {
                    accountNumber = accountNumber.replace(/^0+/, '');
                }

                // Replace "X" characters in accountNumber with wildcard character
                var accountForSearch = accountNumber.replace(/X/g, '%');
                console.log(accountForSearch); // Log the replaced account number for debugging

                // Call updateStatusSMS function for each row
                updateStatusSMS(usernameUpdate, phoneNumber, accountForSearch, function (success) {
                    if (success) {
                        // Optional: Update UI or perform other actions upon success
                        console.log('Status updated successfully for:', usernameUpdate);
                    } else {
                        // Optional: Handle failure case
                        console.error('Failed to update status for:', usernameUpdate);
                    }
                });

                // Call searchSMSNotification with current row data
                searchSMSNotification(phoneNumber, accountForSearch, emailAddress);
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

            // AJAX request  to search SMS notification data
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


        function displaySMSNotificationResult(data) {
            if (!data || data.length === 0) {
                console.log("No data to display.");
                return;
            }

            var tableBody = document.querySelector('#smsNotificationResult table tbody');

            // Check if tableBody is null or undefined
            if (!tableBody) {
                // Create table body if it doesn't exist
                var table = document.querySelector('#smsNotificationResult table');
                if (!table) {
                    console.error("Table not found.");
                    return;
                }
                tableBody = document.createElement('tbody');
                table.appendChild(tableBody);
            }

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
                newRow += '</tr>';

                // Check if tableBody is null or undefined
                if (tableBody) {
                    tableBody.insertAdjacentHTML('beforeend', newRow);
                } else {
                    console.error("Table body not found.");
                }
            });

            // Show the button after the data table is displayed
            var executePatchingBtn = document.getElementById('executePatchingBtn');
            if (executePatchingBtn) {
                executePatchingBtn.style.display = 'block';
            } else {
                console.error("Button with ID 'executePatchingBtn' not found.");
            }


        }


        // Event listener for the "Execute all patching process" button
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