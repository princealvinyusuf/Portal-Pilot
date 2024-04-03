<?php

// Check if the user is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
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
        <h3 class="col-auto flex-grow-1"><b>Patching - Bulk SMS Notifikasi</b></h3>
    </div>
    <hr>

    <!-- Upload file -->

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Upload Excel File</h5>
                    <a href="./downloads/sms_deac_bulk.xlsx" download class="btn btn-secondary">Download Template</a>
                </div>
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
            <button id="exportExcelBtn" class="btn btn-success"
                style="float: right; margin-top: 10px; margin-bottom: 10px; display: none;">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <!-- Total row display: <span id="totalRowCount">0</span> -->

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
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table body content will be appended here -->
                    </tbody>
                </table>
                <!-- Button to execute all patching process -->
                <button id="executePatchingBtn" class="btn btn-success" style="display: none;">Execute all patching
                    process
                    now</button>
            </div>
        </div>
    </div>




    <br>

    <div id="successPopup" class="success-popup">
        <h4>Patching Complete</h4>
        <p>The SMS notification has been successfully updated.</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


    <script>

        var ajaxRequestsCount = 0; // Variable to count the number of AJAX requests made
        var ajaxResponsesCount = 0; // Variable to count the number of AJAX responses received

        document.getElementById('uploadForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission

            // Clear the table before processing the uploaded data
            clearTable();

            var fileInput = document.getElementById('fileInput');
            var file = fileInput.files[0];

            if (file) {
                // Check file size
                if (file.size > 15000) { // 15KB = 15000 bytes
                    alert('File size exceeds 15KB. Please upload a smaller file.');
                    return; // Exit function
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, { type: 'array' });
                    var sheetName = workbook.SheetNames[0];
                    var worksheet = workbook.Sheets[sheetName];
                    var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                    // Filter out empty rows
                    var nonEmptyRows = jsonData.filter(function (row) {
                        return Object.keys(row).length > 0;
                    });

                    // Check if the row count of non-empty rows exceeds 50
                    if (nonEmptyRows.length > 51) {
                        alert('Row count exceeds 50. Please upload a file with fewer rows.');
                        return; // Exit function
                    }

                    // Track unique combinations of phone numbers and account numbers
                    var uniqueCombinations = new Set();
                    var duplicateData = [];

                    // Check for duplicate data, invalid account numbers, and invalid phone numbers, starting from row index 1
                    var duplicateFound = false;
                    for (var i = 1; i < jsonData.length; i++) {
                        var row = jsonData[i];
                        if (Object.keys(row).length > 0) { // Exclude empty rows
                            var phoneNumber = row[0]; // Assuming phone number is in the first column
                            var accountNumber = row[1]; // Assuming account number is in the second column
                            var combination = phoneNumber + '-' + accountNumber;
                            if (uniqueCombinations.has(combination)) {
                                duplicateFound = true;
                                duplicateData.push(row);
                            } else {
                                uniqueCombinations.add(combination);

                                // Check if account number contains characters other than numbers and stars
                                if (!/^[0-9*]+$/.test(accountNumber)) {
                                    alert('Invalid account number: ' + accountNumber + '. Please upload a file with valid account numbers.');
                                    return; // Exit function
                                }

                                // Check if phone number contains characters other than numbers
                                if (!/^[0-9]+$/.test(phoneNumber)) {
                                    alert('Invalid phone number: ' + phoneNumber + '. Please upload a file with valid phone numbers.');
                                    return; // Exit function
                                }

                                // Check if account number has more than 15 characters
                                if (accountNumber.length > 15) {
                                    alert('Account number exceeds 15 characters: ' + accountNumber + '. Please upload a file with account numbers of maximum 15 characters.');
                                    return; // Exit function
                                }
                            }
                        }
                    }

                    if (duplicateFound) {
                        console.log('Duplicate data found:');
                        console.log(duplicateData);
                        alert('Duplicate data found. Please upload a file without duplicate entries.');
                        return; // Exit function
                    }

                    // Process the data
                    processData(jsonData);

                    // Close the workbook
                    workbook = null; // or workbook = undefined;

                    // Reset jsonData for next file upload
                    jsonData = null;
                };
                reader.readAsArrayBuffer(file);
            }
        });




        function clearTable() {
            // Clear the table content
            var tableBody = document.querySelector('#smsNotificationResult table tbody');
            tableBody.innerHTML = '';
        }


        function processData(data) {
            // Reset counts for new data
            ajaxRequestsCount = 0;
            ajaxResponsesCount = 0;

            // Assuming each row of data has columns in the order: phone number, account number
            for (var i = 1; i < data.length; i++) { // Start from index 1 to skip header row
                var phoneNumber = data[i][0];
                var accountNumber = data[i][1]; // Assuming "username update" is the 4th column

                // Remove leading zeros from accountNumber if it starts with "0"
                if (accountNumber.charAt(0) === "0") {
                    accountNumber = accountNumber.replace(/^0+/, '');
                }

                // Replace "X" characters in accountNumber with wildcard character
                var accountForSearch = accountNumber.replace(/X/g, '%');

                // Call searchSMSNotification with current row data
                searchSMSNotification(phoneNumber, accountNumber);
            }
        }



        // Event listener for the "Execute all patching process" button
        document.getElementById('executePatchingBtn').addEventListener('click', function () {
            // Prompt the user to input the username update
            var usernameUpdate = prompt("Please enter the username update:");

            // Check if the user provided a username update
            if (usernameUpdate !== null && usernameUpdate.trim() !== '') {
                // Call the updateStatusSMS function for each row in the table
                document.querySelectorAll('#smsNotificationResult table tbody tr').forEach(function (row) {
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

            } else {
                // User cancelled or provided an empty username update
                console.log('No username update provided.');
            }
        });



        function searchSMSNotification(phone, account) {
            // Check if any of the fields are empty
            if (phone === "" && account === "") {
                alert("Please enter the required information in one of the columns provided.");
                return;
            }

            // Increment the count of AJAX requests made
            ajaxRequestsCount++;

            // AJAX request  to search SMS notification data
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "./Actions.php?a=search_sms_notification_bulk&phone=" + phone + "&account=" + account, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        displaySMSNotificationResult(response.data);

                        // Increment the count of AJAX responses received
                        ajaxResponsesCount++;

                        // If all AJAX requests have been completed, call countAndLogTotalRows()
                        if (ajaxResponsesCount === ajaxRequestsCount) {
                            countAndLogTotalRows();
                        }
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
                newRow += '<td data-rekening="' + row.rekening + '">' + row.rekening + '</td>';
                newRow += '<td>' + row.email + '</td>';
                newRow += '<td data-phone="' + row.phone_number + '">' + row.phone_number + '</td>';
                newRow += '<td data-username="' + row.username_update + '">' + row.username_update + '</td>';
                newRow += '<td>' + row.status_sms + '</td>';
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

            // Create Export to Excel button
            var exportExcelBtn = document.createElement('button');
            exportExcelBtn.id = 'exportExcelBtn';
            exportExcelBtn.className = 'btn btn-success';
            exportExcelBtn.style.float = 'right';
            exportExcelBtn.style.marginTop = '10px';
            exportExcelBtn.style.display = 'none';
            exportExcelBtn.innerHTML = '<i class="fas fa-file-excel"></i> Export to Excel';
            exportExcelBtn.addEventListener('click', exportToExcel);

            // Show the export button
            var exportExcelBtnElement = document.getElementById('exportExcelBtn');
            if (exportExcelBtnElement) {
                exportExcelBtnElement.style.display = 'block';
            } else {
                console.error("Export button not found.");
            }
        }

        function countAndLogTotalRows() {
            var rowCount = document.querySelectorAll('#smsNotificationResult table tbody tr').length;
            console.log("Total rows displayed: " + rowCount);

            // Update the content of the span element with the total row count
            var totalRowCountElement = document.getElementById('totalRowCount');
            if (totalRowCountElement) {
                totalRowCountElement.textContent = rowCount.toString();
            }
        }

        function exportToExcel() {
            var wb = XLSX.utils.table_to_book(document.getElementById('smsNotificationResult').getElementsByTagName('table')[0], { sheet: "Sheet JS" }); // Convert table to workbook
            XLSX.writeFile(wb, 'sms_notification_data.xlsx'); // Save workbook as Excel file with name 'email_notification_data.xlsx'
        }

        // Bind the exportToExcel function to the click event of the export Excel button
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('exportExcelBtn').addEventListener('click', exportToExcel);
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