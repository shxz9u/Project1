<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("로그인이 필요합니다.");
}

if (empty($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
    exit("유효하지 않은 게시글 ID입니다.");
}

$post_id = (int)$_POST['post_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT user_id FROM posts WHERE post_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    exit("게시글이 존재하지 않습니다.");
}

$sql = "DELETE FROM posts WHERE post_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id]);

header("Location: index.php");
exit;
