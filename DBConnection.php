<?php

class DBConnection {
    public $conn; // Connection for the first database
    public $conn2; // Connection for the second database
    
    function __construct(){
        // Connect to the first database
        $this->conn = new mysqli("localhost", "root", "", "audit_trailing");
        if ($this->conn->connect_error) {
            die("Connection to first database failed: " . $this->conn->connect_error);
        }
        
        // Connect to the second database
        $this->conn2 = new mysqli("localhost", "root", "", "audit_trailing");
        if ($this->conn2->connect_error) {
            die("Connection to second database failed: " . $this->conn2->connect_error);
        }
    }
    
    function __destruct(){
        // Close connections when the object is destroyed
        $this->conn->close();
        // Uncomment the following line if you have a second connection
        $this->conn2->close();
    }
}

// Create an instance of the DBConnection class
$db = new DBConnection();

// Access the connections using $db->conn1 and $db->conn2
$conn = $db->conn;
// Uncomment the following line if you have a second connection
$conn2 = $db->conn2;

// Now you can use $conn1 and $conn2 to perform database operations
// For example:
// $result1 = $conn1->query("SELECT * FROM table_name");
// $result2 = $conn2->query("SELECT * FROM table_name");

?>
