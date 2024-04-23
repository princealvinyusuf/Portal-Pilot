<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['id']) || (isset($_SESSION['id']) && $_SESSION['id'] <= 0)) {
    header("Location:./login.php");
    exit;
}
require_once ('./DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Portal Pilot</title> -->
    <link rel="stylesheet" href="./fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="./DataTables/datatables.min.css"> -->
    <!-- <script src="./DataTables/datatables.min.js"></script> -->
    <script src="./fontawesome/js/all.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        #floatingButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            /* Ensure it's higher than other elements */
        }
    </style>
</head>

<body class="bg-light">
    <main>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient" id="topNavBar">
            <div class="container">
                <a class="navbar-brand" href="./">
                    Portal Pilot
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'home') ? 'active' : '' ?>" aria-current="page"
                                href="./"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <?php if ($_SESSION['access_level'] == 'Administrator' || $_SESSION['access_level'] == 'Engineer'): ?>
                            <li class="nav-item dropdown"> <!-- Add 'dropdown' class here -->
                                <a class="nav-link dropdown-toggle <?php echo ($page == 'patching') ? 'active' : '' ?>"
                                    href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-cogs"></i> Patching
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown"> <!-- Dropdown menu -->
                                    <li><a class="dropdown-item" href="./?page=patching_deactivate_sms">Deactivate SMS
                                            Notif</a></li>
                                    <li><a class="dropdown-item" href="./?page=patching_deactivate_email">Deactivate Email
                                            Notif</a></li>
                                    <li><a class="dropdown-item" href="./?page=patching_deactivate_wa">Deactivate WA
                                            Notif</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="./?page=bulk_patching_deactivate_sms">Bulk -
                                            Deactivate SMS Notif</a></li>
                                    <li><a class="dropdown-item" href="./?page=bulk_patching_deactivate_email">Bulk -
                                            Deactivate Email Notif</a></li>
                                    <li><a class="dropdown-item" href="./?page=bulk_patching_deactivate_wa">Bulk -
                                            Deactivate WA Notif</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <!-- <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'query') ? 'active' : '' ?>" aria-current="page"
                                href="./?page=query"><i class="fas fa-search"></i> Query</a>
                        </li> -->


                        <?php if ($_SESSION['access_level'] == 'Administrator' || $_SESSION['access_level'] == 'Engineer' || $_SESSION['access_level'] == 'Operator'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($page == 'users') ? 'active' : '' ?>" aria-current="page"
                                    href="./?page=users"><i class="fa fa-users"></i> Users</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['access_level'] == 'Administrator' || $_SESSION['access_level'] == 'Engineer'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($page == 'logs') ? 'active' : '' ?>" aria-current="page"
                                    href="./?page=logs"><i class="fa fa-th-list"></i> Audit Trails</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                    <?php if (isset($_SESSION['id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle bg-transparent  text-light border-0"
                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Hello
                                <?php echo $_SESSION['name'] ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="././Actions.php?a=logout">Logout</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="container py-3" id="page-container">
            <?php
            if (isset($_SESSION['flashdata'])):
                ?>
                <div class="dynamic_alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?>">
                    <div class="float-end"><a href="javascript:void(0)" class="text-dark text-decoration-none"
                            onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a></div>
                    <?php echo $_SESSION['flashdata']['msg'] ?>
                </div>
                <?php unset($_SESSION['flashdata']) ?>
            <?php endif; ?>
            <?php
            include $page . '.php';
            ?>
        </div>
    </main>
    <div class="modal fade" id="uni_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit'
                        onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal_secondary" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit'
                        onclick="$('#uni_modal_secondary form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-primary btn-sm rounded-0" id='confirm'
                        onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-0"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="screenshotModal" tabindex="-1" aria-labelledby="screenshotModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="screenshotModalLabel">Please complete the information below</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="state" class="form-label">State:</label>
                        <select class="form-select" id="state">
                            <option value="Before">Before</option>
                            <option value="Process">Process</option>
                            <option value="After">After</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="screenName" class="form-label">Screen Name:</label>
                        <input type="text" class="form-control" id="screenName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="takeScreenshotAndCloseModal()">Take</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <div style="position: relative; z-index: 10000;">
        <!-- Add a floating button -->
        <div id="floatingButton">
            <button onclick="takeScreenshot()" class="btn btn-primary">
                <i class="fas fa-camera"></i> Take Screenshot
            </button>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

    <script>

        function toggleFloatingButton(visible) {
            var floatingButton = document.getElementById('floatingButton');
            floatingButton.style.display = visible ? 'block' : 'none';
        }

        function takeScreenshot() {

            // Show the modal
            $('#screenshotModal').modal('show');

            // Populate the screenName field with the current tab's name
            document.getElementById('screenName').value = document.title;
        }

        function takeScreenshotAct() {
            toggleFloatingButton(false);

            // Get the values from the modal
            var state = document.getElementById('state').value;
            var screenName = document.getElementById('screenName').value;
            var description = document.getElementById('description').value;

            // Concatenate the values to form the filename
            var filename = state + '_' + screenName + '_' + description + '.png';

            // Calculate the maximum scroll position
            var maxScroll = document.documentElement.scrollHeight - window.innerHeight;

            // Set the scroll position to the top
            window.scrollTo(0, 0);

            // Capture the entire content of the page
            html2canvas(document.documentElement, { scrollY: 0 }).then(function (canvas) {
                var screenshotImage = canvas.toDataURL("image/png");

                // Create a temporary link element
                var downloadLink = document.createElement('a');
                downloadLink.href = screenshotImage;
                downloadLink.download = filename; // Set the filename

                // Append the link to the body
                document.body.appendChild(downloadLink);

                // Trigger the click event on the link
                downloadLink.click();

                // Clean up
                document.body.removeChild(downloadLink);

                // Restore the scroll position
                window.scrollTo(0, maxScroll);

                // After taking the screenshot, show the floating button again
                toggleFloatingButton(true);
            });
        }

        function takeScreenshotAndCloseModal() {

            // Close the modal after taking the screenshot
            $('#screenshotModal').modal('hide');

            setTimeout(function () {
                takeScreenshotAct(); // Call takeScreenshotAct() after 3 seconds
            }, 2000); // 3000 milliseconds = 3 seconds

        }
    </script>


</body>

</html>