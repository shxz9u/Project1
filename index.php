<?php
session_start();
date_default_timezone_set('Asia/Seoul');
include 'db.php';

$sql = "SELECT * FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
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

        .post-card {
            border: 1px solid #e1e1e1;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fff;
        }

        .post-card:hover {
            background-color: #f9f9f9;
        }

        .post-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .post-meta {
            font-size: 0.9rem;
            color: #888;
        }

        .post-excerpt {
            margin-top: 10px;
            color: #333;
        }

        .btn-primary {
            font-size: 0.9rem;
        }

        .pagination {
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
                            <a class="btn btn-primary me-2" href="create_post.php">글쓰기</a>
                        </li>
                        <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] === 'true'): ?>
                            <li class="nav-item">
                                <a class="btn btn-danger" href="admin.php">관리자 페이지</a>
                            </li>
                        <?php endif; ?>
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

    <div class="container my-5">
        <h1 class="mb-4">자유게시판</h1>

        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-12 mb-4">
                    <div class="post-card">
                        <h5 class="post-title">
                            <a href="post_view.php?id=<?= $post['post_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($post['title']) ?></a>
                        </h5>
                        <div class="post-meta">
                            <span>작성자: <?= htmlspecialchars($post['username']) ?></span> |
                            <span>조회수: <?= $post['views'] ?></span> |
                            <span>작성일: <?= date('Y-m-d H:i', strtotime($post['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <span class="page-link">이전</span>
                </li>
                <li class="page-item active" aria-current="page">
                    <span class="page-link">1</span>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">다음</a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <footer class="bg-light py-3 mt-5">
        <div class="container text-center">
            <p class="mb-0">Designed by Bootstrap</p>
            <p>Layer7 Project1</p>
        </div>
    </footer>

</body>

</html>