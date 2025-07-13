<?php
session_start();
include("funcs.php");
loginCheck();

$pdo = db_conn();
$user_id = $_SESSION['user_id'];

// URLパラメータ取得
$id = $_GET['id'] ?? null;
$date = $_GET['date'] ?? null;
$mood = 0;
$comment = "";

// id優先で既存データを取得
if ($id) {
  $stmt = $pdo->prepare("SELECT * FROM diary WHERE id = :id AND user_id = :user_id");
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $date = $row['date'];
    $mood = $row['mood'];
    $comment = $row['comment'];
    $id = $row['id'];
  } else {
    echo "対象データが見つかりませんでした。";
    exit();
  }
} elseif ($date) {
  $stmt = $pdo->prepare("SELECT * FROM diary WHERE user_id = :user_id AND date = :date");
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $mood = $row['mood'];
    $comment = $row['comment'];
    $id = $row['id'];
  }
} else {
  $date = date('Y-m-d'); // どちらもなければ今日の日付
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>編集：<?= $date ?></title>
  <style>
    body {
  background: #1c1c3c;
  color: white;
  font-family: 'Helvetica Neue', sans-serif;
  text-align: center;
  padding: 40px 20px;
}

form {
  max-width: 350px;
  margin: 0 auto;
}

.hearts {
  font-size: 2rem;
  cursor: pointer;
  user-select: none;
  margin-bottom: 20px;
}

textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border-radius: 8px;
  border: none;
  resize: vertical;
  background: rgba(255,255,255,0.1);
  color: white;
}

input[type="submit"] {
  background: #b39ddb;
  color: #2c3e50;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  width: 100%;
  max-width: 300px;
}

a.back {
  margin-top: 20px;
  display: block;
  color: #f8bbd0;
  text-decoration: none;
}
  </style>
</head>
<body>
  <h2>📝 <?= $date ?> の気分編集</h2>

  <form action="update_mood.php" method="post">
    <div class="hearts">
      <span class="heart" data-value="1">🤍</span>
      <span class="heart" data-value="2">🤍</span>
      <span class="heart" data-value="3">🤍</span>
    </div>
    <input type="hidden" name="mood" id="mood" value="<?= $mood ?>">
    <br>
    <textarea name="comment" rows="3" placeholder="今日のひとこと"><?= htmlspecialchars($comment) ?></textarea>
    <input type="hidden" name="id" value="<?= htmlspecialchars($id ?? '') ?>">
    <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
    <br>
    <input type="submit" value="更新">
  </form>

  <a href="calendar.php" class="back">← カレンダーへ戻る</a>

  <script>
    const hearts = document.querySelectorAll('.heart');
    const moodInput = document.getElementById('mood');
    let currentMood = <?= $mood ?>;

    function updateHearts(mood) {
      hearts.forEach((heart, index) => {
        heart.textContent = index < mood ? '💗' : '🤍';
      });
      moodInput.value = mood;
    }

    updateHearts(currentMood);

    hearts.forEach((heart, index) => {
      heart.addEventListener('click', () => {
        const clickedMood = index + 1;
        if (currentMood === clickedMood) {
          currentMood = clickedMood - 1;
        } else {
          currentMood = clickedMood;
        }
        updateHearts(currentMood);
      });
    });
  </script>
</body>
</html>
