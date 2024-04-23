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

<title>Audit Trails</title>

<div class="container py-5">
<div class="d-flex w-100">
        <h3 class="col-auto flex-grow-1"><b>Audit Log</b></h3>
        <button class="btn btn-sm btn-primary rounded-0 mr-2" type="button" onclick="location.reload()"><i class="fa fa-retweet"></i> Refresh List</button>
        <button id="exportExcelBtn" class="btn btn-sm btn-success rounded-0" type="button"><i class="fa fa-file-excel"></i> Export to Excel</button>
    </div>
    <hr>
    <div class="container py-3">
        <form id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_date">Filter by Date:</label>
                        <input type="date" class="form-control" id="filter_date" name="filter_date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_username">Filter by Username:</label>
                        <input type="text" class="form-control" id="filter_username" name="filter_username">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="logTable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="py-1 px-2"></th>
                        <th class="py-1 px-2">Date Time</th>
                        <th class="py-1 px-2">Username</th>
                        <th class="py-1 px-2">IP Address</th>
                        <th class="py-1 px-2">User Agent</th>
                        <th class="py-1 px-2">Action Made</th>
                        <th class="py-1 px-2">Query</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be dynamically populated using JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>

<script>
    $(function () {
        // Function to handle form submission and update audit log table
        function filterLogs() {
            // Serialize form data
            var formData = $('#filterForm').serialize();
            // Make AJAX request to filter_logs action in Actions.php
            $.ajax({
                url: 'Actions.php',
                data: formData + '&a=filter_logs',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Clear existing table rows
                    $('#logTable tbody').empty();
                    // Populate table with filtered data
                    $.each(data, function (index, row) {
                        var newRow = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + row.date_time + '</td>' +
                            '<td>' + row.username + '</td>' +
                            '<td>' + row.ip_address + '</td>' +
                            '<td>' + row.user_agent + '</td>' +
                            '<td>' + row.action_made + '</td>' +
                            '<td>' + row.query + '</td>' +
                            '</tr>';
                        $('#logTable tbody').append(newRow);
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Handle form submission
        $('#filterForm').submit(function (e) {
            e.preventDefault();
            filterLogs();
        });

        // Handle click event of "Export to Excel" button
        $('#exportExcelBtn').click(function () {
            exportToExcel();
        });

        // Initial filter on page load (optional)
        filterLogs();
    });

    // Function to export table data to Excel
    function exportToExcel() {
        var wb = XLSX.utils.table_to_book(document.getElementById('logTable'), { sheet: "Sheet JS" }); // Convert table to workbook
        XLSX.writeFile(wb, 'audit_log.xlsx'); // Save workbook as Excel file with name 'audit_log.xlsx'
    }

</script>