<?php
require_once ('./DBConnection.php');
if (isset ($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM users where id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
}
?>
<div class="container-fluid">
    <form action="" id="user-form">
        <input type="hidden" name="id" value="<?php echo isset ($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" name="name" class="form-control form-control-sm rounded-0"
                value="<?php echo isset ($name) ? $name : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="contact" class="control-label">Contact</label>
            <input type="text" name="contact" class="form-control form-control-sm rounded-0"
                value="<?php echo isset ($contact) ? $contact : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="address" class="control-label">Address</label>
            <textarea rows="3" name="address" class="form-control form-control-sm rounded-0"
                required><?php echo isset ($address) ? $address : "" ?></textarea>
        </div>
        <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="text" name="username" class="form-control form-control-sm rounded-0"
                value="<?php echo isset ($username) ? $username : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control form-control-sm rounded-0" required>
        </div>
        <div class="form-group">
            <label for="access_level" class="control-label">Access Level</label>
            <select name="access_level" class="form-control form-control-sm rounded-0" required>
                <option value="Administrator">Administrator</option>
                <option value="Engineer">Engineer</option>
                <option value="Operator">Operator</option>
            </select>
        </div>


    </form>

</div>
<script>
    $(function () {
        $('#user-form').submit(function (e) {
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
            _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled', true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url: './Actions.php?a=save_user',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#uni_modal button').attr('disabled', false)
                    $('#uni_modal button[type="submit"]').text('Save')
                },
                success: function (resp) {
                    if (resp.status == 'success') {
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal', function () {
                            location.reload()
                        })
                        if ("<?php echo isset ($enrollee_id) ?>" != 1)
                            _this.get(0).reset();
                    } else {
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#uni_modal button').attr('disabled', false)
                    $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>