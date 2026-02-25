<?php
include "dbconnect.php";

/* ---------- Fetch latest posts with author name ---------- */
$stmt = $conn->prepare("
    SELECT 
        posts.id,
        posts.title,
        posts.content,
        posts.created_at,
        users.username
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
");

$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f6fa;
            font-family:Arial;
        }

        .navbar{
            background:#2563eb;
        }

        .navbar-brand, .nav-link{
            color:white !important;
        }

        .post-card{
            border:none;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(0,0,0,0.08);
            transition:0.2s;
        }

        .post-card:hover{
            transform:translateY(-4px);
        }

        .footer{
            margin-top:50px;
            padding:20px;
            text-align:center;
            background:#e5e7eb;
        }
    </style>
</head>

<body>

<!-- ===== Navbar ===== -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">My Blog</a>

        <div class="ms-auto">
            <a class="nav-link d-inline" href="login.php">Login</a>
            <a class="nav-link d-inline" href="register.php">Register</a>
        </div>
    </div>
</nav>


<!-- ===== Header ===== -->
<div class="container text-center mt-4">
    <h2>Latest Posts</h2>
    <p class="text-muted">Recent articles from our writers</p>
</div>


<!-- ===== Posts Grid ===== -->
<div class="container mt-4">

    <div class="row">

        <?php if($posts->num_rows == 0): ?>
            <p class="text-center text-muted">No posts available yet.</p>
        <?php else: ?>

            <?php while($p = $posts->fetch_assoc()): ?>

                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card post-card h-100">

                        <div class="card-body">

                            <h5 class="card-title">
                                <?= htmlspecialchars($p['title']) ?>
                            </h5>

                            <small class="text-muted">
                                By <?= htmlspecialchars($p['username']) ?>
                                • <?= date("d M Y", strtotime($p['created_at'])) ?>
                            </small>

                            <p class="mt-2">
                                <?= htmlspecialchars(substr($p['content'],0,120)) ?>...
                            </p>

                            <a href="dashboard.php?id=<?= $p['id'] ?>" 
                               class="btn btn-primary btn-sm">
                                Read More
                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>

</div>


<!-- ===== Footer ===== -->
<div class="footer">
    © <?= date("Y") ?> My Personal Blog | PHP + MySQL
</div>

</body>
</html>