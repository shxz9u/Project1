<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("잘못된 접근입니다.");
}

$post_id = $_GET['id'];

$sql = "SELECT * FROM posts WHERE post_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post || $post['user_id'] != $_SESSION['user_id']) {
    die("권한이 없습니다.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $update_sql = "UPDATE posts SET title = ?, content = ? WHERE post_id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([$title, $content, $post_id]);

    echo "<script>
        alert('게시글이 수정되었습니다.');
        window.location.href = 'post_view.php?id=$post_id';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>게시글 수정</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .post-container {
            background: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .post-container h2 {
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="post-container">
        <h2>게시글 수정</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">제목</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">내용</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning w-100">수정 완료</button>
        </form>
    </div>
</body>

</html>