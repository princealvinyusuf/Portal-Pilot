<?php 
require_once('./DBConnection.php');
$qry= $conn->query("SELECT * FROM users where id = '{$_GET['id']}'");
if($qry->num_rows > 0){
    foreach($qry->fetch_array() as $k => $v){
        if(!is_numeric($k))
        $$k=$v;
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <dl>
        <dt class="fs-6 text-muted">ID</dt>
        <dd class="fs-5 fw-bold"><?php echo $id ?></dd>
        <dt class="fs-6 text-muted">Name</dt>
        <dd class="fs-5 fw-bold"><?php echo $name ?></dd>
        <dt class="fs-6 text-muted">Username</dt>
        <dd class="fs-5 fw-bold"><?php echo $username ?></dd>
        <dt class="fs-6 text-muted">Contact</dt>
        <dd class="fs-5 fw-bold"><?php echo $contact ?></dd>
        <dt class="fs-6 text-muted">Address</dt>
        <dd class="fs-5 fw-bold"><?php echo $address ?></dd>
        <dt class="fs-6 text-muted">Access Level</dt>
        <dd class="fs-5 fw-bold"><?php echo $access_level ?></dd>
    </dl>
    <div class="col-12">
        <div class="w-100 d-flex justify-content-end">
            <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<script>
$(function(){
    $.ajax({
        url:'./Actions.php?a=save_log',
        method:'POST',
        data:{action_made:"Viewed the data of: <?php echo $name ?>"},
        dataType:'json',
        error:err=>{
            console.log(err)
        },
        succuess:function(resp){
            if(resp == 1){
                console.log("Log successfully saved")
            }else{
                console.log("Log has failed to save.")
            }
        }
    })
})
</script>
