<?php
include 'dbconnect.php';

$message = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // check duplicates
    $stmt = $conn->prepare(
        "SELECT id FROM users WHERE username=? OR email=?"
    );
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $message = "Username or email already exists";
    }
    else{
        $stmt = $conn->prepare(
            "INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)"
        );
        $stmt->bind_param("ssss", $username, $email, $hashed, $role);

        if($stmt->execute()){
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow p-4" style="width:400px">

        <h3 class="text-center mb-4">Register</h3>

        <?php if($message): ?>
            <div class="alert alert-danger">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <input class="form-control mb-3" type="text" name="username" placeholder="Username" required>

            <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>

            <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>

            <select class="form-select mb-3" name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button class="btn btn-primary w-100">Register</button>

        </form>

        <div class="text-center mt-3">
            <a href="login.php">Already have account?</a>
        </div>

    </div>
</div>

</body>
</html>