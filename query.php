<div class="container py-5">
    <div class="d-flex w-100">
        <h3 class="col-auto flex-grow-1"><b>Query</b></h3>
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="py-1 px-2"></th>
                        <th class="py-1 px-2">Query Name</th>
                        <th class="py-1 px-2">Query Text</th>
                        <th class="py-1 px-2">Database Name</th>
                        <th class="py-1 px-2">Description</th>
                        <th class="py-1 px-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $qry = $conn->query("SELECT * FROM `query` ORDER BY id ASC");
                    $i = 1;
                    while($row=$qry->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="py-1 px-2"><?php echo $i++ ?></td>
                        <td class="py-1 px-2"><?php echo $row['query_name'] ?></td>
                        <td class="py-1 px-2"><?php echo $row['query_text'] ?></td>
                        <td class="py-1 px-2"><?php echo $row['database_name'] ?></td>
                        <td class="py-1 px-2"><?php echo $row['description'] ?></td>
                        <td class="py-1 px-2">
                            <form id="runQueryForm_<?php echo $row['id'] ?>" action="" method="post">
                                <input type="hidden" name="query_id" value="<?php echo $row['id'] ?>">
                                <input type="hidden" id="queryText_<?php echo $row['id'] ?>" value="<?php echo htmlspecialchars($row['query_text']) ?>">
                                <button type="button" class="btn btn-primary" onclick="confirmRun(<?php echo $row['id']; ?>, '<?php echo $row['query_name']; ?>')">Run</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if($qry->num_rows <=0): ?>
                        <tr>
                            <th class="tex-center"  colspan="6">No data to display.</th>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmRun(queryId, queryName) {
        var queryText = document.getElementById("queryText_" + queryId).value;
        var confirmMessage = "Are you sure to run this query? This activity cannot be undone. This activity will impact to production level.\n\nQuery Text:\n" + queryText;
        if (confirm(confirmMessage)) {
            // AJAX request to save_log before submitting the form
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./Actions.php?a=save_log", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // If the log is successfully saved, submit the form to run the query
                    document.getElementById("runQueryForm_" + queryId).submit();
                }
            };
            xhr.send("a=save_log&user_id=<?php echo $_SESSION['id']; ?>&action_made=Run query with Query Name: " + queryName);
        }
    }
</script>

