<?php
session_start();
include 'dbconnect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$query = "
SELECT posts.*, users.username
FROM posts
JOIN users ON posts.user_id = users.id
ORDER BY created_at DESC
";

$posts = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Admin Panel</title>
</head>

<body class="bg-light">

<div class="container py-5">

<h3>
Admin Dashboard
<a href="logout.php" class="btn btn-danger btn-sm float-end">Logout</a>
</h3>

<table class="table table-bordered mt-4">
<tr>
<th>User</th>
<th>Content</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php while($row = $posts->fetch_assoc()): ?>
<tr>
<td><?= $row['username'] ?></td>
<td><?= htmlspecialchars($row['content']) ?></td>
<td><?= $row['created_at'] ?></td>
<td>
<a href="delete_post.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>