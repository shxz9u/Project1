<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id WHERE posts.post_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if (!$post) {
        echo "게시글을 찾을 수 없습니다.";
        exit;
    }

    $update_sql = "UPDATE posts SET views = views + 1 WHERE post_id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([$post_id]);
} else {
    echo "잘못된 접근입니다.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($post['title']) ? htmlspecialchars($post['title']) : '게시글' ?> - 게시글</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }

        nav.navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        nav.navbar .container-fluid {
            padding-top: 0;
            padding-bottom: 0;
        }

        body {
            padding-top: 70px;
        }

        .post-header {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .post-meta {
            font-size: 0.9rem;
            color: #888;
        }

        .post-content {
            margin-top: 20px;
            color: #333;
            line-height: 1.6;
        }

        .btn-custom {
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-warning {
            background-color: #f39c12;
            border-color: #e67e22;
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #c0392b;
        }

        .btn-custom:hover {
            opacity: 0.9;
        }

        .card-footer {
            background-color: transparent;
            border-top: none;
            padding: 0;
        }

        .alert {
            font-size: 1rem;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">게시판</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link"><?= htmlspecialchars($_SESSION['username']) ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">로그아웃</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="create_post.php">글쓰기</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">로그인</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sign_up.php">회원가입</a>
                        </li>
                    <?php endif; ?>
                </ul>
                </ div>
            </div>
    </nav>

    <div class="container my-5" style="padding-top:70px;">
        <?php if (isset($post)): ?>
            <div class="post-header">
                <h1 class="mb-4"><?= $post['title'] ?></h1>
                <div class="post-meta mb-3">
                    <span>작성자: <?= $post['username'] ?></span> |
                    <span>조회수: <?= $post['views'] ?></span> |
                    <span>작성일: <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?></span>
                </div>
                <div class="post-content">
                    <p><?= nl2br($post['content']) ?></p>
                </div>

            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <strong>오류!</strong> 게시글을 불러오는 데 실패했습니다.
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
            <a class="btn btn-warning btn-custom" href="edit_post.php?id=<?= $post['post_id'] ?>">수정</a>
            <a class="btn btn-danger btn-custom" href="delete_post.php?id=<?= $post['post_id'] ?>" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>