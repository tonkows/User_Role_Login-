<?php 

require_once 'connection.php';

session_start();

if (isset($_POST['btn_login'])) {
    $email = $_POST['txt_email']; // textbox name 
    $password = $_POST['txt_password']; // password
    $role = $_POST['txt_role']; // select option role

    if (empty($email)) {
        $errorMsg[] = "Please enter email";
    } else if (empty($password)) {
        $errorMsg[] = "Please enter password";
    } else if (empty($role)) {
        $errorMsg[] = "Please select role";
    } else if ($email && $password && $role) {
        try {
            $select_stmt = $db->prepare("SELECT email, password, role FROM masterlogin WHERE email = ? AND password = ? AND role = ?");
            $select_stmt->bind_param("sss", $email, $password, $role);
            $select_stmt->execute();
            $result = $select_stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $dbemail = $row['email'];
                $dbpassword = $row['password'];
                $dbrole = $row['role'];

                if ($email == $dbemail && $password == $dbpassword && $role == $dbrole) {
                    switch($dbrole) {
                        case 'admin':
                            $_SESSION['admin_login'] = $email;
                            $_SESSION['success'] = "Admin... Successfully Login...";
                            header("location: admin/admin_home.php");
                            exit;
                        case 'employee':
                            $_SESSION['employee_login'] = $email;
                            $_SESSION['success'] = "Employee... Successfully Login...";
                            header("location: employee/employee_home.php");
                            exit;
                        case 'user':
                            $_SESSION['user_login'] = $email;
                            $_SESSION['success'] = "User... Successfully Login...";
                            header("location: user/user_home.php");
                            exit;
                        default:
                            $_SESSION['error'] = "Wrong email or password or role";
                            header("location: index.php");
                            exit;
                    }
                } else {
                    $_SESSION['error'] = "Wrong email or password or role";
                    header("location: index.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = "Wrong email or password or role";
                header("location: index.php");
                exit;
            }
        } catch(mysqli_sql_exception $e) {
            echo $e->getMessage();
        }
    }
}
?>
