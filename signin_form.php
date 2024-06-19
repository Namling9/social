<?php
include('includes/database.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' and password='$password'");

    $row = mysqli_fetch_assoc($result);
    $count = mysqli_num_rows($result);

    if ($count == 0) {
        echo '<script>
        alert("Invalid email or password");
        window.location.href = "index.php";
        </script>';
    } else if ($count > 0) {
        session_start();
        $_SESSION['user_id'] = $row['user_id'];
        $user_id = $row['user_id'];

        if ($email == 'namlinglimbu9@gmail.com' && $password == 'namling') {
            // Update the role to admin in the database
            mysqli_query($conn, "UPDATE user SET role = 'admin' WHERE user_id = '$user_id'");
            
            $_SESSION['role'] = 'admin';
            echo '<script>
            alert("Admin successfully logged in");
            window.location.href = "admin.php";
            </script>';
        } else {
            $_SESSION['role'] = $row['role'];  // Assign the role from the database
            echo '<script>
            alert("Successfully logged in");
            window.location.href = "home.php";
            </script>';
        }
    }
}
?>
