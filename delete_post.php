<?php
session_start();
include 'dbconnect.php';

// login

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}


if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit();
}

$post_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'];


// Delete

if($role === "admin"){

    // admin → delete any post
    $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $post_id);

} else {

    // user → delete only own post
    $stmt = $conn->prepare(
        "DELETE FROM posts WHERE id=? AND user_id=?"
    );
    $stmt->bind_param("ii", $post_id, $user_id);
}

$stmt->execute();

// Redirect

if($role === "admin"){
    header("Location: admin_dashboard.php");
} else {
    header("Location: dashboard.php");
}

exit();
?>