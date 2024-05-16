<?php 

require_once "connection.php";

session_start();

if (isset($_POST['btn_register'])) {
    $username = $_POST['txt_username'];
    $email = $_POST['txt_email'];
    $password = $_POST['txt_password'];
    $role = $_POST['txt_role'];

    if (empty($username)) {
        $errorMsg[] = "Please enter username";
    } else if (empty($email)) {
        $errorMsg[] = "Please enter email";
    } else if (empty($password)) {
        $errorMsg[] = "Please enter password";
    } else if (strlen($password) < 6) {
        $errorMsg[] = "Password must be at least 6 characters";
    } else if (empty($role)) {
        $errorMsg[] = "Please select role";
    } else {
        try {
            
            if (!$db) {
                throw new Exception('การเชื่อมต่อฐานข้อมูลล้มเหลว');
            }

            $select_stmt = $db->prepare("SELECT username, email FROM masterlogin WHERE username = ? OR email = ?");
            $select_stmt->bind_param("ss", $username, $email);
            $select_stmt->execute();
            $result = $select_stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                if ($row['username'] == $username) {
                    $errorMsg[] = "Sorry, username already exists";
                } else if ($row['email'] == $email) {
                    $errorMsg[] = "Sorry, email already exists";
                }
            } else {
                if (!isset($errorMsg)) {
                    $insert_stmt = $db->prepare("INSERT INTO masterlogin(username, email, password, role) VALUES (?, ?, ?, ?)");
                    $insert_stmt->bind_param("ssss", $username, $email, $password, $role);

                    if ($insert_stmt->execute()) {
                        $_SESSION['success'] = "Register Successfully...";
                        header("location: index.php");
                        exit;
                    }
                }
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>
