<?php

// Check if the user is logged in
if (!isset ($_SESSION['id']) || $_SESSION['id'] <= 0) {
    header("Location: ./login.php");
    exit;
}

// Check user access level
if (!isset ($_SESSION['access_level']) || !in_array($_SESSION['access_level'], ['Administrator', "Engineer", "Operator"])) {
    // Redirect the user to an error page or homepage
    header("Location: ./");
    exit;
}

// Include the DBConnection file or any necessary files
require_once ('DBConnection.php');

// Your existing HTML and PHP code for the users page
?>
<div class="container py-5">
    <h3><b>Users List</b></h3>
    <div class="card">
        <div class="card-body">
            <div class="col-12 my-2 d-flex justify-content-end">
                <?php if ($_SESSION['access_level'] == 'Administrator'): ?>
                    <button class="btn btn-sm btn-primary rounded-0" id="add_new"><i class="fa fa-plus"></i> Add
                        New</button>
                <?php endif; ?>
            </div>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="py-1 px-2"></th>
                        <th class="py-1 px-2">Name</th>
                        <th class="py-1 px-2">Contact</th>
                        <th class="py-1 px-2">Email Address</th>
                        <th class="py-1 px-2">Role</th>
                        <th class="py-1 px-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qry = $conn->query("SELECT * FROM users order by name asc");
                    $i = 1;
                    while ($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="py-1 px-2">
                                <?php echo $i++ ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['name'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['contact'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['email_address'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['access_level'] ?>
                            </td>
                            <td class="py-1 px-2 text-center">
                                <?php if ($_SESSION['access_level'] == 'Administrator' || $_SESSION['access_level'] == 'Engineer'): ?>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button"
                                            class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0"
                                            data-bs-toggle='dropdown' aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <?php if ($_SESSION['access_level'] == 'Administrator' || $_SESSION['access_level'] == 'Engineer'): ?>
                                                <li><a class="dropdown-item view_data" href="javascript:void(0)"
                                                        data-id="<?php echo $row['id'] ?>">View</a></li>
                                            <?php endif; ?>
                                            <?php if ($_SESSION['access_level'] == 'Administrator'): ?>
                                                <li><a class="dropdown-item edit_data" href="javascript:void(0)"
                                                        data-id="<?php echo $row['id'] ?>">Edit</a></li>
                                            <?php endif; ?>
                                            <?php if ($_SESSION['access_level'] == 'Administrator'): ?>
                                                <li><a class="dropdown-item delete_data" href="javascript:void(0)"
                                                        data-id="<?php echo $row['id'] ?>"
                                                        data-name="<?php echo "[id={$row['id']}] " . $row['name'] ?>">Delete</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($qry->num_rows <= 0): ?>
                        <tr>
                            <th class="tex-center" colspan="6">No data to display.</th>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#add_new').click(function () {
            uni_modal('New User', "manage_user.php");
        })
        $('.edit_data').click(function () {
            uni_modal('Edit User Details', "manage_user.php?id=" + $(this).attr('data-id'));
        })
        $('.delete_data').click(function () {
            var name = $(this).attr('data-name').replace(/\[id=\d+\]\s*/, "");
            _conf("Are you sure to delete <b>" + name + "</b> from user list?", "delete_data", [$(this).attr('data-id')]);
        });
        $('.view_data').click(function () {
            uni_modal('View User Details', "view_user.php?id=" + $(this).attr('data-id'));
        })
    })
    function delete_data($id) {
        $('#confirm_modal button').attr('disabled', true)
        $.ajax({
            url: './Actions.php?a=delete_user',
            method: 'POST',
            data: { id: $id },
            dataType: 'JSON',
            error: err => {
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled', false)
            },
            success: function (resp) {
                if (resp.status == 'success') {
                    location.reload()
                } else {
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled', false)
                }
            }
        })
    }
</script>