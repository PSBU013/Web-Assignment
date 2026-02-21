<?php
session_start();
include 'dbconnect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user'){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// create post
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $content = trim($_POST['content']);

    if($content !== ""){
        $stmt = $conn->prepare(
            "INSERT INTO posts (user_id, content) VALUES (?,?)"
        );
        $stmt->bind_param("is", $user_id, $content);
        $stmt->execute();
    }
}


// fetch posts
$stmt = $conn->prepare(
    "SELECT * FROM posts WHERE user_id=? ORDER BY created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Dashboard</title>
</head>

<body class="bg-light">

<div class="container py-5">

<h3>
Welcome <?= $_SESSION['username']; ?>
<a href="logout.php" class="btn btn-danger btn-sm float-end">Logout</a>
</h3>

<hr>

<form method="POST">
<textarea name="content" maxlength="256"
class="form-control mb-2"
placeholder="What's on your mind?"></textarea>

<button class="btn btn-primary">Post</button>
</form>

<hr>

<?php while($row = $posts->fetch_assoc()): ?>

<div class="card mb-2 p-3">
    <p><?= htmlspecialchars($row['content']) ?></p>

    <small class="text-muted"><?= $row['created_at'] ?></small>

    <div class="mt-2">
        <a href="edit_post.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete_post.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
    </div>
</div>

<?php endwhile; ?>

</div>
</body>
</html>