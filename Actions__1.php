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
        if (isset($data['query'])) { // Check if $query is set in $data array
            $query = $data['query']; // Assign $query from $data array
        } else {
            $query = ''; // Set default value if $query is not provided
        }

        if (count($data) > 0) {
            extract($data);
            $sql = "INSERT INTO `logs` (`user_id`, `action_made`, `ip_address`, `user_agent`, `query`) 
                VALUES ('{$user_id}', '{$action_made}', '{$ip_address}', '{$user_agent}', '{$query}')";
            $save = $this->conn->query($sql);
            if (!$save) {
                die($sql . " <br> ERROR:" . $this->conn->error);
            }
        }
        return true;
    }



    // Inside the login method of your Actions class
    function login()
    {
        extract($_POST);
        $sql = "SELECT * FROM users WHERE username = '{$username}'";
        $qry = $this->conn->query($sql)->fetch_array();
        if (!$qry) {
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        } else {
            // Make API call
            $api_url = 'https://apiclose.bri.co.id/gateway/apiActiveDirectory/1.0/ADAuthentication2';
            $api_data = array(
                'userLogin' => $username,
                'password' => $password, // Password is included in API request data
                'channelId' => 'Test Channel',
                'userAgent' => 'Firefox',
                'ipAddress' => $_SERVER['REMOTE_ADDR']
            );
            $api_response = json_decode($this->make_api_call_with_auth($api_url, $api_data), true);

            // Check API response
            if ($api_response['responseCode'] == '01') {
                // If response code is '01' (success), check if address matches
                if ($api_response['responseMessage'] == $qry['address']) {
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

                        // Audit log
                        $this->save_log($log, $ip_address, $user_agent);
                    } else {
                        $resp['status'] = "failed";
                        $resp['msg'] = "Access denied. You don't have permission to access this system.";
                    }
                } else {
                    $resp['status'] = "failed";
                    $resp['msg'] = "Invalid address.";
                }
            } else {
                // Include the actual response code in the error message
                $resp['status'] = "failed";
                $resp['msg'] = "API authentication failed with response code: " . $api_response['responseCode'];
            }

        }
        return json_encode($resp);
    }



    // Function to make API call with basic authentication
    function make_api_call_with_auth($url, $data)
    {
        $username = "contactCenter";
        $password = "C0nt4ctC3nter!14017";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode("$username:$password")
            )
        );
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
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
                // $log['action_made'] = " added [id={$user_id}] {$name} into the user list.";
                $log['action_made'] = "Added new user: {$name} into the user list.";
            } else {
                $resp['msg'] = "User successfully updated.";
                $log['action_made'] = "Updated the details of: {$name} account.";
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
            $log['action_made'] = "Deleted User: {$mem['name']} from user list.";
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

    function filter_logs()
    {
        extract($_GET);
        $where_clause = '';

        // Construct the WHERE clause based on filter parameters
        if (!empty($filter_date)) {
            $where_clause .= "AND DATE(l.date_created) = '{$filter_date}' ";
        }
        if (!empty($filter_username)) {
            $where_clause .= "AND u.username LIKE '%{$filter_username}%' ";
        }

        // Execute the filtered query
        $qry = $this->conn->query("SELECT l.*, u.username 
                                FROM `logs` l 
                                INNER JOIN users u ON l.user_id = u.id 
                                WHERE 1 {$where_clause}
                                ORDER BY unix_timestamp(l.`date_created`) DESC");

        // Process the query result and return JSON response
        $data = array();
        $i = 1;
        while ($row = $qry->fetch_assoc()) {
            $data[] = array(
                'id' => $i++,
                'date_time' => date("M d, Y H:i", strtotime($row['date_created'])),
                'username' => $row['username'],
                'ip_address' => $row['ip_address'],
                'user_agent' => $row['user_agent'],
                'action_made' => $row['action_made'],
                'query' => $row['query']
            );
        }

        return json_encode($data);
    }

    function update_last_active($user_id)
    {
        $stmt = $this->conn->prepare('UPDATE users SET last_active = NOW() WHERE id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
    }

    function count_active_users()
    {
        // Count users whose last_active time is within the last 15 minutes
        $count_query = "SELECT COUNT(*) FROM users WHERE last_active >= NOW() - INTERVAL 15 MINUTE";
        $result = $this->conn->query($count_query);
        if ($result) {
            $count = $result->fetch_row()[0];
            return $count;
        } else {
            return 0; // Return 0 if there's an error in the query
        }
    }

    function count_registered_users()
    {
        $count_query = "SELECT COUNT(*) FROM users";
        $result = $this->conn->query($count_query);
        if ($result) {
            $count = $result->fetch_row()[0];
            return $count;
        } else {
            return 0; // Return 0 if there's an error in the query
        }
    }

    function update_status_sms($username_update, $phone_number, $rekening)
    {
        // Check if username_update is empty
        if (empty($username_update)) {
            // If empty, set it to the username session id
            $username_update = $_SESSION['username'];
        }

        // Update status_sms to '0' based on phone_number or rekening
        $sql = "UPDATE data_registration SET date_update = NOW(), status_sms = '0', username_update = ? WHERE status_sms = 1 ";

        if (!empty($phone_number) && !empty($rekening)) {
            // Both phone number and account number are provided
            $sql .= "AND (phone_number = ? AND rekening = ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sss', $username_update, $phone_number, $rekening);
        } elseif (!empty($phone_number)) {
            // Only phone number is provided
            $sql .= "AND phone_number = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $username_update, $phone_number);
        } elseif (!empty($rekening)) {
            // Only account number is provided
            $sql .= "AND rekening = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $username_update, $rekening);
        }

        $result = $stmt->execute();

        if ($result) {
            return json_encode(['status' => 'success', 'message' => 'Status SMS updated successfully.']);
        } else {
            return json_encode(['status' => 'failed', 'message' => 'Failed to update status SMS.']);
        }
    }


    function update_status_email($username_update, $phone_number, $rekening)
    {
        // Check if username_update is empty
        if (empty($username_update)) {
            // If empty, set it to the username session id
            $username_update = $_SESSION['username'];
        }

        // Update status_email to '0' based on phone_number or rekening
        $sql = "UPDATE data_registration SET date_update = NOW(), status_email = '0', username_update = ? WHERE status_email = 1 ";

        if (!empty($phone_number) && !empty($rekening)) {
            // Both phone number and account number are provided
            $sql .= "AND (phone_number = ? AND rekening = ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sss', $username_update, $phone_number, $rekening);
        } elseif (!empty($phone_number)) {
            // Only phone number is provided
            $sql .= "AND phone_number = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $username_update, $phone_number);
        } elseif (!empty($rekening)) {
            // Only account number is provided
            $sql .= "AND rekening = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $username_update, $rekening);
        }

        $result = $stmt->execute();

        if ($result) {
            return json_encode(['status' => 'success', 'message' => 'Status Email updated successfully.']);
        } else {
            return json_encode(['status' => 'failed', 'message' => 'Failed to update status Email.']);
        }
    }


    function update_status_wa($username_update, $phone_number, $rekening)
    {
        // Check if username_update is empty
        if (empty($username_update)) {
            // If empty, set it to the username session id
            $username_update = $_SESSION['username'];
        }

        // Update status_wa to '0' based on phone_number or rekening
        $sql = "UPDATE data_registration SET date_update = NOW(), status_wa = '0', username_update = ? WHERE status_wa = 1 ";

        if (!empty($phone_number) && !empty($rekening)) {
            // Both phone number and account number are provided
            $sql .= "AND (phone_number = ? AND rekening = ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sss', $username_update, $phone_number, $rekening);
        } elseif (!empty($phone_number)) {
            // Only phone number is provided
            $sql .= "AND phone_number = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $username_update, $phone_number);
        } elseif (!empty($rekening)) {
            // Only account number is provided
            $sql .= "AND rekening = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $username_update, $rekening);
        }

        $result = $stmt->execute();

        if ($result) {
            return json_encode(['status' => 'success', 'message' => 'Status WhatsApp updated successfully.']);
        } else {
            return json_encode(['status' => 'failed', 'message' => 'Failed to update status WhatsApp.']);
        }
    }



    function search_sms_notification($phone, $account, $email)
    {
        // Construct SQL query based on search parameters
        $whereClause = '';
        $conditions = [];
        if (!empty($phone)) {
            $conditions[] = "phone_number = '$phone'";
        }
        if (!empty($account)) {
            // Replace "X" characters in account with wildcard character
            $accountForSearch = str_replace('*', '%', $account);
            $conditions[] = "rekening LIKE '%$accountForSearch%'";
        }
        if (!empty($email)) {
            $conditions[] = "email = '$email'";
        }
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }


        $sql = "SELECT * FROM data_registration $whereClause ORDER BY date_reg ASC";

        // Execute the query
        $result = $this->conn2->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return json_encode(['status' => 'success', 'data' => $data]);
        } else {
            print ("Not works");
            return json_encode(['status' => 'failed', 'message' => 'Failed to retrieve SMS notification data.']);
        }
    }


    function search_sms_notification_bulk($phone, $account)
    {
        // Construct SQL query based on search parameters
        $whereClause = '';
        $conditions = [];
        if (!empty($phone)) {
            $conditions[] = "phone_number = '$phone'";
        }
        if (!empty($account)) {
            // Replace "X" characters in account with wildcard character
            $accountForSearch = str_replace('*', '%', $account);
            $conditions[] = "rekening LIKE '%$accountForSearch%'";
        }
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }


        $sql = "SELECT * FROM data_registration $whereClause ORDER BY date_reg ASC";

        // Execute the query
        $result = $this->conn->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return json_encode(['status' => 'success', 'data' => $data]);
        } else {
            print ("Not works");
            return json_encode(['status' => 'failed', 'message' => 'Failed to retrieve SMS notification data.']);
        }
    }


    function search_email_notification_bulk($email, $account, )
    {
        // Construct SQL query based on search parameters
        $whereClause = '';
        $conditions = [];
        if (!empty($account)) {
            // Replace "X" characters in account with wildcard character
            $accountForSearch = str_replace('*', '%', $account);
            $conditions[] = "rekening LIKE '%$accountForSearch%'";
        }
        if (!empty($email)) {
            $conditions[] = "email = '$email'";
        }
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }


        $sql = "SELECT * FROM data_registration $whereClause ORDER BY date_reg ASC";

        // Execute the query
        $result = $this->conn->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return json_encode(['status' => 'success', 'data' => $data]);
        } else {
            print ("Not works");
            return json_encode(['status' => 'failed', 'message' => 'Failed to retrieve SMS notification data.']);
        }
    }


}



$a = isset($_GET['a']) ? $_GET['a'] : '';
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
        $log['query'] = $_POST['query'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        echo $action->save_log($log, $ip_address, $user_agent);
        break;
    case 'filter_logs':
        echo $action->filter_logs();
        break;
    case 'update_last_active':
        echo $action->update_last_active($_SESSION['id']);
        break;
    case 'count_active_users':
        echo $action->count_active_users();
        break;
    case 'count_registered_users':
        echo $action->count_registered_users();
        break;
    case 'search_sms_notification':
        // Assuming you have a method in your Actions class to handle the search
        if (isset($_GET['phone']) || isset($_GET['account']) || isset($_GET['email'])) {
            // Extract search parameters
            $phone = isset($_GET['phone']) ? $_GET['phone'] : '';
            $account = isset($_GET['account']) ? $_GET['account'] : '';
            $email = isset($_GET['email']) ? $_GET['email'] : '';

            // Call the method to search SMS notification data
            echo $action->search_sms_notification($phone, $account, $email);
        } else {
            // If search parameters are not provided, return an error message
            echo json_encode(['status' => 'failed', 'message' => 'Search parameters are missing.']);
        }
        break;
    case 'search_sms_notification_bulk':
        // Assuming you have a method in your Actions class to handle the search
        if (isset($_GET['phone']) || isset($_GET['account']) || isset($_GET['email'])) {
            // Extract search parameters
            $phone = isset($_GET['phone']) ? $_GET['phone'] : '';
            $account = isset($_GET['account']) ? $_GET['account'] : '';
            $email = isset($_GET['email']) ? $_GET['email'] : '';

            // Call the method to search SMS notification data
            echo $action->search_sms_notification_bulk($phone, $account, $email);
        } else {
            // If search parameters are not provided, return an error message
            echo json_encode(['status' => 'failed', 'message' => 'Search parameters are missing.']);
        }
        break;
    case 'search_email_notification_bulk':
        // Assuming you have a method in your Actions class to handle the search
        if (isset($_GET['phone']) || isset($_GET['account']) || isset($_GET['email'])) {
            // Extract search parameters
            $phone = isset($_GET['phone']) ? $_GET['phone'] : '';
            $account = isset($_GET['account']) ? $_GET['account'] : '';
            $email = isset($_GET['email']) ? $_GET['email'] : '';

            // Call the method to search SMS notification data
            echo $action->search_email_notification_bulk($email, $account);
        } else {
            // If search parameters are not provided, return an error message
            echo json_encode(['status' => 'failed', 'message' => 'Search parameters are missing.']);
        }
        break;
    case 'update_status_sms':
        if (isset($_POST['username_update'], $_POST['phone_number'], $_POST['rekening'])) {
            $username_update = $_POST['username_update'];
            $phone_number = $_POST['phone_number'];
            $rekening = $_POST['rekening'];
            echo $action->update_status_sms($username_update, $phone_number, $rekening);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Missing parameters']);
        }
        break;
    case 'update_status_email':
        if (isset($_POST['username_update'], $_POST['phone_number'], $_POST['rekening'])) {
            $username_update = $_POST['username_update'];
            $phone_number = $_POST['phone_number'];
            $rekening = $_POST['rekening'];
            echo $action->update_status_email($username_update, $phone_number, $rekening);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Missing parameters']);
        }
        break;
    case 'update_status_wa':
        if (isset($_POST['username_update'], $_POST['phone_number'], $_POST['rekening'])) {
            $username_update = $_POST['username_update'];
            $phone_number = $_POST['phone_number'];
            $rekening = $_POST['rekening'];
            echo $action->update_status_wa($username_update, $phone_number, $rekening);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Missing parameters']);
        }
        break;
    default:
        // default action here
        echo "No Action given";
        break;
}
