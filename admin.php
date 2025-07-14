<?php
session_start();
include 'db.php';

if (!isset($_COOKIE['is_admin']) || $_COOKIE['is_admin'] !== 'true') {
    die("이 페이지는 관리자만 접근할 수 있습니다.");
}

$sql = "SELECT posts.post_id, posts.title, posts.created_at, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.user_id 
        ORDER BY posts.created_at DESC";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>관리자 게시글 관리</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 2rem;
            background-color: #f8f9fa;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <h1 class="mb-4">관리자 - 게시글 관리</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= $post['post_id'] ?></td>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars($post['username']) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($post['created_at'])) ?></td>
                    <td>
                        <a href="delete_post.php?id=<?= $post['post_id'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('정말 삭제하시겠습니까?');">
                            삭제
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>