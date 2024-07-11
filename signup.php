<?php
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Name = $_POST['Name'];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $CPassword = $_POST["CPassword"];
    
    if (empty($Name) || empty($Email) || empty($Password) || empty($CPassword)) {
        $response['error'] = "Please fill out all fields";
    } elseif ($Password != $CPassword) {
        $response['error'] = "Passwords do not match";
    } else {
        $db = new mysqli('localhost', 'root', '', 'sep');
        if ($db->connect_error) {
            $response['error'] = "Connection failed: " . $db->connect_error;
        } else {
            // Check if name already exists
            $check_name_query = "SELECT * FROM signup WHERE Name = ?";
            $check_name_stmt = $db->prepare($check_name_query);
            $check_name_stmt->bind_param("s", $Name);
            $check_name_stmt->execute();
            $check_name_stmt->store_result();
            
            // Check if email already exists
            $check_email_query = "SELECT * FROM signup WHERE Email = ?";
            $check_email_stmt = $db->prepare($check_email_query);
            $check_email_stmt->bind_param("s", $Email);
            $check_email_stmt->execute();
            $check_email_stmt->store_result();
            
            if ($check_name_stmt->num_rows > 0 && $check_email_stmt->num_rows > 0) {
                $response['error'] = "Both email and name already exist";
            } elseif ($check_name_stmt->num_rows > 0) {
                $response['error'] = "Name already exists";
            } elseif ($check_email_stmt->num_rows > 0) {
                $response['error'] = "Email already exists";
            } else {
                // Insert the user if email and name don't exist
                $insert_query = "INSERT INTO signup(Name,Email,Password) VALUES (?,?,?)";
                $insert_stmt = $db->prepare($insert_query);
                $insert_stmt->bind_param("sss", $Name, $Email, $Password);
                $insert_stmt->execute();
                
                if ($insert_stmt->error) {
                    $response['error'] = $insert_stmt->error;
                } else {
                    $response['success'] = true;
                }
            }
            
            $db->close();
        }
    }
    
    echo json_encode($response);
}
?>
