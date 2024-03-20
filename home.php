
<?php
require_once('Actions.php');

$actions = new Actions();
$totalUsersOnline = $actions->getTotalUsersOnline();
?>


<!-- Summary Information  -->
<div class="container">
    <h5 class="col-auto flex-grow-1"><b>User Information</b></h5>

    <!-- Display total users online -->
    <div class="row">
        <div class="col-md-12">
            <p>Total Users Online: <?php echo $totalUsersOnline; ?></p>
        </div>
    </div>
    
    
</div>
<br>

<!-- Container Patching  -->
<div class="container">

<h5 class="col-auto flex-grow-1"><b>Patching</b></h5>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu Deaktivasi SMS Notifikasi</h6>
                    <button class="btn btn-primary">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 2</h6>
                    <button class="btn btn-primary">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 3</h6>
                    <button class="btn btn-primary">Access</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 4</h6>
                    <button class="btn btn-primary">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 5</h6>
                    <button class="btn btn-primary">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 6</h6>
                    <button class="btn btn-primary">Access</button>
                </div>
            </div>
        </div>
    </div>
    
</div>


<!-- Container Query  -->

<div class="container">

<h5 class="col-auto flex-grow-1"><b>Query</b></h5>
    
<div class="row">
    <div class="col-md-12">
        <div class="card mb-12">
            <div class="card-body d-flex justify-content-between">
                <h6 class="card-title">Menu Query Data</h6>
                <button class="btn btn-primary">Access</button>
            </div>
        </div>
    </div>
</div>

    
</div>

