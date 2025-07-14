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
    die("삭제 권한이 없습니다.");
}

$delete_sql = "DELETE FROM posts WHERE post_id = ?";
$delete_stmt = $pdo->prepare($delete_sql);
$delete_stmt->execute([$post_id]);

echo "<script>
        alert('게시글이 삭제되었습니다.');
        window.location.href = 'index.php';
    </script>";
