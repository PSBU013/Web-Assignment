<?php
session_start();
include 'dbconnect.php';

$message = "";

// run only after form submit
if($_SERVER["REQUEST_METHOD"] === "POST"){

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT id, username, password, role 
         FROM users 
         WHERE username=? OR email=?"
    );

    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            // create session
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            // redirect by role
            if($user['role'] === 'admin'){
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        }
    }

    $message = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow p-4" style="width:400px">

        <h3 class="text-center mb-4">Login</h3>

        <?php if($message): ?>
            <div class="alert alert-danger">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <input class="form-control mb-3"
                   type="text"
                   name="username"
                   placeholder="Username or Email"
                   required>

            <input class="form-control mb-3"
                   type="password"
                   name="password"
                   placeholder="Password"
                   required>

            <button class="btn btn-primary w-100">
                Login
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="register.php">Create account</a>
        </div>

    </div>
</div>

</body>
</html>