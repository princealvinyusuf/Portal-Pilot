
<title>Portal Pilot</title>

<!-- Total Users Online -->
<div class="container">
    <div class="card bg-light">
        <div class="card-body">
            <h5 class="card-title"><b>User Information</b></h5>
            <div id="totalUsersOnline"></div>
            <div id="totalUsersRegistered"></div>
        </div>
    </div>
</div>



<!-- Container Patching  -->
<br>
<br>
<?php if ($_SESSION['access_level'] == 'Administrator' || $_SESSION['access_level'] == 'Engineer'): ?>

    <div class="container">

        <h5 class="col-auto flex-grow-1"><b>Patching</b></h5>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title" style="font-weight: bold;">Menu Deaktivasi SMS Notifikasi</h6>
                        <button type="button" class="btn btn-primary" onclick="saveLogAndRedirect(1)">Access</button>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title" style="font-weight: bold;">Menu Deaktivasi Email Notifikasi</h6>
                        <button type="button" class="btn btn-primary" onclick="saveLogAndRedirect(2)">Access</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title" style="font-weight: bold;">Menu Deaktivasi WA Notifikasi</h6>
                        <button type="button" class="btn btn-primary" onclick="saveLogAndRedirect(3)">Access</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title" style="font-weight: bold;">Menu Bulk SMS Notifikasi</h6>
                        <button type="button" class="btn btn-primary" onclick="saveLogAndRedirect(4)">Access</button>
                    </div>
                </div>
            </div>



            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title" style="font-weight: bold;">Menu Bulk Email Notifikasi</h6>
                        <button type="button" class="btn btn-primary" onclick="saveLogAndRedirect(5)">Access</button>
                    </div>
                </div>
            </div>



            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title" style="font-weight: bold;">Menu Bulk WA Notifikasi</h6>
                        <button type="button" class="btn btn-primary" onclick="saveLogAndRedirect(6)">Access</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<!-- Container Query  -->

<!-- <div class="container">

    <h5 class="col-auto flex-grow-1"><b>Query</b></h5>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-12">
                <div class="card-body d-flex justify-content-between">
                    <h6 class="card-title">Menu Query Data</h6>
                    <button type="button" class="btn btn-primary"
                        onclick="saveLog('User Accesssing Menu: Menu 2')">Access</button>
                </div>
            </div>
        </div>
    </div>


</div> -->

<script>
    function saveLogAndRedirect(number) {

        // Redirect based on the number
        switch (number) {
            case 1:
                window.location.href = './?page=patching_deactivate_sms';
                saveLog('User Accessing Menu: Deaktivasi SMS Notifikasi');
                break;
            case 2:
                window.location.href = './?page=patching_deactivate_email';
                saveLog('User Accessing Menu: Deaktivasi Email Notifikasi');
                break;
            case 3:
                window.location.href = './?page=patching_deactivate_wa';
                saveLog('User Accessing Menu: Deaktivasi WA Notifikasi');
                break;
            case 4:
                window.location.href = './?page=bulk_patching_deactivate_sms';
                saveLog('User Accessing Menu: Bulk Deaktivasi SMS Notifikasi');
                break;
            case 5:
                window.location.href = './?page=bulk_patching_deactivate_email';
                saveLog('User Accessing Menu: Bulk Deaktivasi Email Notifikasi');
                break;
            case 6:
                window.location.href = './?page=bulk_patching_deactivate_wa';
                saveLog('User Accessing Menu: Bulk Deaktivasi WA Notifikasi');
                break;
            default:
                // Default redirection if number doesn't match any case
                window.location.href = './';
                break;
        }
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

    function displayTotalUsersOnline() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "./Actions.php?a=count_active_users", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("totalUsersOnline").innerHTML = "Total Users Online: " + xhr.responseText;
            }
        };
        xhr.send();
    }

    function updateLastActive() {
        // AJAX request to update_last_active when the page loads
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "./Actions.php?a=update_last_active", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // You can add any handling code here if needed
            }
        };
        xhr.send();
    }

    function displayTotalUsersRegistered() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "./Actions.php?a=count_registered_users", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("totalUsersRegistered").innerHTML = "Total Users Registered: " + xhr.responseText;
            }
        };
        xhr.send();
    }

    // Call the function when the page loads
    window.onload = function () {
        displayTotalUsersOnline();
        displayTotalUsersRegistered();
        updateLastActive(); // Call updateLastActive function to update the last active time
    };
</script>