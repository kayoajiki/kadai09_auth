<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("funcs.php");

if (!isset($_SESSION['user_id'])) {
  exit('ログインしていません。<a href="login.php">ログインページへ</a>');
}

if (!isset($_POST['mood']) || !isset($_POST['comment'])) {
  exit('データが正しく送信されていません。');
}

$user_id = $_SESSION['user_id'];
$mood = (int)$_POST['mood'];
$comment = $_POST['comment'];
$date = date('Y-m-d');

try {
  $pdo = db_conn();
  $stmt = $pdo->prepare("INSERT INTO diary(user_id, mood, comment, date) VALUES (:user_id, :mood, :comment, :date)");
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindValue(':mood', $mood, PDO::PARAM_INT);
  $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->execute();

  header("Location: calendar.php");
  exit();
} catch (PDOException $e) {
  echo "データベースエラー: " . $e->getMessage();
  exit();
}
?>
