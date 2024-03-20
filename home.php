
<!-- Container Patching  -->
<br>
<br>
<div class="container">

<h5 class="col-auto flex-grow-1"><b>Patching</b></h5>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu Deaktivasi SMS Notifikasi</h6>
                    <button type="button" class="btn btn-primary" onclick="saveLog('User Accesssing Menu: Deaktivasi SMS Notifikasi')">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 2</h6>
                    <button type="button" class="btn btn-primary" onclick="saveLog('User Accesssing Menu: Menu 2')">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 3</h6>
                    <button type="button" class="btn btn-primary" onclick="saveLog('User Accesssing Menu: Menu 2')">Access</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 4</h6>
                    <button type="button" class="btn btn-primary" onclick="saveLog('User Accesssing Menu: Menu 2')">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 5</h6>
                    <button type="button" class="btn btn-primary" onclick="saveLog('User Accesssing Menu: Menu 2')">Access</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Menu 6</h6>
                    <button type="button" class="btn btn-primary" onclick="saveLog('User Accesssing Menu: Menu 2')">Access</button>
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
                <button type="button" class="btn btn-primary" onclick="saveLog('User Accesssing Menu: Menu 2')">Access</button>
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
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // If the log is successfully saved, submit the form to run the query
                    // document.getElementById("runQueryForm_" + queryId).submit();
                }
            };
            xhr.send("a=save_log&user_id=<?php echo $_SESSION['id']; ?>&action_made=" + queryAction);
        
    }
</script>
