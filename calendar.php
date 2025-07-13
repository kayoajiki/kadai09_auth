<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("funcs.php");
loginCheck();

$pdo = db_conn();
$user_id = $_SESSION["user_id"];

// 今日の年月
$year = date("Y");
$month = date("m");
$firstDay = date("Y-m-01");
$lastDay = date("Y-m-t");

// DBから今月の mood & comment を取得
$stmt = $pdo->prepare("SELECT id, date, mood, comment FROM diary WHERE user_id = :user_id AND date BETWEEN :start AND :end");
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':start', $firstDay, PDO::PARAM_STR);
$stmt->bindValue(':end', $lastDay, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 日付 => mood / comment マップ作成
$dayMap = [];
foreach ($results as $row) {
  $dayMap[$row["date"]] = [
    "id" => $row["id"],
    "mood" => $row["mood"],
    "comment" => trim($row["comment"])
  ];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= $year ?>年<?= $month ?>月の気分カレンダー</title>
  <style>
    body {
      background: #1c1c3c;
      color: white;
      font-family: sans-serif;
      padding: 40px;
      text-align: center;
    }
    table {
      margin: 0 auto;
      border-collapse: collapse;
    }
    th, td {
      width: 60px;
      height: 60px;
      border: 1px solid #444;
      text-align: center;
      vertical-align: middle;
    }
    td a {
      text-decoration: none;
      color: white;
      display: block;
      height: 100%;
      width: 100%;
    }
    .has-comment {
      background-color: #2a2a55;
    }
  </style>
</head>
<body>
  <h2>📅 <?= $year ?>年<?= $month ?>月の気分</h2>
  <table>
    <tr>
      <th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th>
    </tr>
    <?php
    $firstWeekday = date('w', strtotime($firstDay)); // 0〜6（日〜土）
    $daysInMonth = date('t'); // 今月の日数
    $day = 1;
    $cell = 0;

    echo "<tr>";
    // 空セル
    for (; $cell < $firstWeekday; $cell++) {
      echo "<td></td>";
    }

    // 日付セル
    for (; $day <= $daysInMonth; $day++, $cell++) {
      $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
      $info = isset($dayMap[$date]) ? $dayMap[$date] : null;

      $mood = $info ? str_repeat("💗", $info["mood"]) : "";
      $hasComment = $info && $info["mood"] == 0 && !empty($info["comment"]);

      $url = $info && isset($info["id"]) ? "edit.php?id={$info["id"]}" : "edit.php?date=$date";

      $class = $hasComment ? ' class="has-comment"' : '';

      echo "<td{$class}><a href='$url'>$day<br>$mood";
      if ($hasComment) echo "<br>💬";
      echo "</a></td>";

      if ($cell % 7 == 6) echo "</tr><tr>"; // 改行
    }

    // 残りの空セル
    while ($cell % 7 != 0) {
      echo "<td></td>";
      $cell++;
    }
    echo "</tr>";
    ?>
  </table>
  <br>
  <a href="home.php" style="color:#f8bbd0;">← 今日の気分を記録する</a>
</body>
</html>


