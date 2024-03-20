<div class="container py-5">
    <div class="d-flex w-100">
        <h3 class="col-auto flex-grow-1"><b>Patching - Menu Deaktivasi SMS Notifikasi</b></h3>
    </div>
    <hr>
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
                        <button type="button" class="btn btn-primary"
                            onclick="saveLog('User Searching: SMS Notification Data: ' + document.getElementById('phone').value + ' ' + document.getElementById('account').value + ' ' + document.getElementById('email').value)">Access</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="card">

        <div class="card-body">
            <h4 class="mb-4"><strong>Result of SMS Notification Data</strong></h4>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="py-1 px-2"></th>
                        <th class="py-1 px-2">Registration Date</th>
                        <th class="py-1 px-2">Username</th>
                        <th class="py-1 px-2">Account Number</th>
                        <th class="py-1 px-2">Email</th>
                        <th class="py-1 px-2">Phone Number</th>
                        <th class="py-1 px-2">Username Update</th>
                        <th class="py-1 px-2">SMS Status</th>
                        <th class="py-1 px-2">Email Status</th>
                        <th class="py-1 px-2">Whatsapp Status</th>
                        <th class="py-1 px-2">Channel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qry = $conn->query("SELECT * FROM customers ORDER BY registration_date ASC");
                    $i = 1;
                    while ($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="py-1 px-2">
                                <?php echo $i++ ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo date("M d, Y", strtotime($row['registration_date'])) ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['username'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['account_number'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['email'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['phone_number'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['username_update'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['sms_status'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['email_status'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['whatsapp_status'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['channel'] ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($qry->num_rows <= 0): ?>
                        <tr>
                            <th class="text-center" colspan="9">No data to display.</th>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <br>
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
                <button type="button" class="btn btn-primary"
                        onclick="saveLog('Patching Action: Deactivate SMS Notification: ' + document.getElementById('username_update').value + ' ' + document.getElementById('phone_act').value + ' ' + document.getElementById('account_act').value)">Patching SMS Notification</button>
            </div>
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
</script>