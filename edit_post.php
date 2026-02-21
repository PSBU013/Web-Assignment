<?php
session_start();
include 'dbconnect.php';

// SECURITY CHECK

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user'){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit();
}

$post_id = (int) $_GET['id'];

$message = "";


// Fetch Post

$stmt = $conn->prepare(
    "SELECT content FROM posts WHERE id=? AND user_id=?"
);
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows === 0){
    die("Post not found or not yours.");
}

$post = $result->fetch_assoc();
$current_content = $post['content'];


// Update Post

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $new_content = trim($_POST['content']);

    if($new_content === ""){
        $message = "Post cannot be empty.";
    }
    else{

        $stmt = $conn->prepare(
            "UPDATE posts SET content=? WHERE id=? AND user_id=?"
        );
        $stmt->bind_param("sii", $new_content, $post_id, $user_id);

        if($stmt->execute()){
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Post</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container vh-100 d-flex justify-content-center align-items-center">

<div class="card shadow p-4" style="width:450px">

<h4 class="mb-3 text-center">Edit Post</h4>

<?php if($message): ?>
<div class="alert alert-danger">
    <?= $message ?>
</div>
<?php endif; ?>

<form method="POST">

<textarea name="content"
maxlength="256"
class="form-control mb-3"
rows="4"
required><?= htmlspecialchars($current_content) ?></textarea>

<button class="btn btn-success w-100 mb-2">
    Save Changes
</button>

<a href="dashboard.php" class="btn btn-secondary w-100">
    Cancel
</a>

</form>

</div>
</div>

</body>
</html>