<?php
session_start();
require_once ('DBConnection.php');

class Actions extends DBConnection
{
    function __construct()
    {
        parent::__construct();
    }
    function __destruct()
    {
        parent::__destruct();
    }

    function save_log($data = array(), $ip_address = '', $user_agent = '')
    {
        // Data array parameters
        // user_id = user unique id
        // action_made = action made by the user

        if (count($data) > 0) {
            extract($data);
            $sql = "INSERT INTO `logs` (`user_id`, `action_made`, `ip_address`, `user_agent`) 
                VALUES ('{$user_id}', '{$action_made}', '{$ip_address}', '{$user_agent}')";
            $save = $this->conn->query($sql);
            if (!$save) {
                die ($sql . " <br> ERROR:" . $this->conn->error);
            }
        }
        return true;
    }



    // Inside the login method of your Actions class
    function login()
    {
        extract($_POST);
        $sql = "SELECT * FROM users WHERE username = '{$username}' AND `password` = '" . md5($password) . "' ";
        $qry = $this->conn->query($sql)->fetch_array();
        if (!$qry) {
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        } else {
            // Check the user's access level
            $access_level = $qry['access_level'];
            if ($access_level == 'Administrator' || $access_level == 'Engineer' || $access_level == 'Operator') {
                $resp['status'] = "success";
                $resp['msg'] = "Login successfully.";
                // Store user information in session
                foreach ($qry as $k => $v) {
                    if (!is_numeric($k))
                        $_SESSION[$k] = $v;
                }
                $log['user_id'] = $qry['id'];
                $log['action_made'] = "Logged in the system.";
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                // audit log
                $this->save_log($log, $ip_address, $user_agent);
            } else {
                $resp['status'] = "failed";
                $resp['msg'] = "Access denied. You don't have permission to access this system.";
            }
        }
        return json_encode($resp);
    }


    function logout()
    {
        $log['user_id'] = $_SESSION['id'];
        $log['action_made'] = "Logged out.";
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        session_destroy();

        // audit log
        $this->save_log($log, $ip_address, $user_agent);
        header("location:./");
    }
    
    function save_user()
{
    extract($_POST);
    $data = "";
    // Exclude 'id' and 'password' from being updated
    $fields_to_exclude = array('id', 'password');
    foreach ($_POST as $k => $v) {
        if (!in_array($k, $fields_to_exclude) && !empty($v)) {
            if (!empty($data))
                $data .= ", ";
            $data .= " `{$k}` = '{$v}' ";
        }
    }
    
    // Check if password is provided
    $password_hash = '';
    if (!empty($password)) {
        // Convert password to MD5 hash
        $password_hash = md5($password);
        $data .= ", `password` = '{$password_hash}' ";
    }
    
    if (empty($id)) {
        $sql = "INSERT INTO `users` SET {$data}";
    } else {
        $sql = "UPDATE `users` SET {$data} WHERE id = '{$id}'";
    }
    
    $save = $this->conn->query($sql);
    if ($save) {
        $resp['status'] = 'success';
        $log['user_id'] = $_SESSION['id'];
        $user_id = empty($id) ? $this->conn->insert_id : $id;
        if (empty($id)) {
            $resp['msg'] = "New User successfully added.";
            $log['action_made'] = " added [id={$user_id}] {$name} into the user list.";
        } else {
            $resp['msg'] = "User successfully updated.";
            $log['action_made'] = " updated the details of [id={$user_id}] user.";
        }

        // Get IP address and user agent
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        // Audit log with IP address and user agent
        $this->save_log($log, $ip_address, $user_agent);
    } else {
        $resp['status'] = 'failed';
        $resp['msg'] = "Error saving user details. Error: " . $this->conn->error;
        $resp['sql'] = $sql;
    }
    return json_encode($resp);
}


    function delete_user()
    {
        extract($_POST);
        $mem = $this->conn->query("SELECT * FROM users where id = '{$id}'")->fetch_array();
        $delete = $this->conn->query("DELETE FROM users where id = '{$id}'");
        if ($delete) {
            $resp['status'] = 'success';
            $resp['msg'] = 'User successfully deleted.';
            $log['user_id'] = $_SESSION['id'];
            $log['action_made'] = " deleted [id={$mem['id']}] {$mem['name']} from user list.";
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = $resp['msg'];

            // audit log
            $this->save_log($log, $ip_address, $user_agent);
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Failed to delete user.';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
}
$a = isset ($_GET['a']) ? $_GET['a'] : '';
$action = new Actions();
switch ($a) {
    case 'login':
        echo $action->login();
        break;
    case 'logout':
        echo $action->logout();
        break;
    case 'save_user':
        echo $action->save_user();
        break;
    case 'delete_user':
        echo $action->delete_user();
        break;
    case 'save_log':
        $log['user_id'] = $_SESSION['id'];
        $log['action_made'] = $_POST['action_made'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        echo $action->save_log($log, $ip_address, $user_agent);
        break;
    default:
        // default action here
        echo "No Action given";
        break;
}