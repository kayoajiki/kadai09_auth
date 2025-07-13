<?php
session_start();
include("funcs.php");
include("db.php");
loginCheck();

$pdo = db_conn();
$user_id = $_SESSION['user_id'];
$mood = $_POST['mood'] ?? 0;
$comment = $_POST['comment'] ?? '';
$date = $_POST['date'] ?? date('Y-m-d');
$id = $_POST['id'] ?? null;

if ($id) {
  // idがある場合は既存レコードを更新
  $stmt = $pdo->prepare("UPDATE diary SET mood = :mood, comment = :comment, date = :date WHERE id = :id AND user_id = :user_id");
  $stmt->bindValue(':mood', $mood, PDO::PARAM_INT);
  $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
} else {
  // idがない場合は insert（過去日などの初回入力）
  $stmt = $pdo->prepare("INSERT INTO diary (user_id, mood, comment, date) VALUES (:user_id, :mood, :comment, :date)");
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindValue(':mood', $mood, PDO::PARAM_INT);
  $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
}

$status = $stmt->execute();

if ($status) {
  header("Location: calendar.php");
  exit();
} else {
  echo "登録に失敗しました";
}
