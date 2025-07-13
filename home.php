<?php
// セッション開始（重複防止）
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Moon Diary - 今日の気分</title>
  <style>
    body {
      background: linear-gradient(#2e003e, #1a1a40);
      font-family: 'Helvetica Neue', sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      text-align: center;
      background: rgba(255,255,255,0.05);
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(255,255,255,0.1);
    }
    h2 {
      font-size: 24px;
      margin-bottom: 20px;
    }
    .hearts {
      display: flex;
      justify-content: center;
      gap: 15px;
      font-size: 48px;
      margin-bottom: 20px;
      cursor: pointer;
    }
    textarea {
      width: 100%;
      height: 100px;
      border: none;
      border-radius: 10px;
      padding: 10px;
      font-size: 16px;
      resize: none;
      background-color: rgba(255,255,255,0.1);
      color: #fff;
    }
    textarea::placeholder {
      color: #aaa;
    }
    .submit-button {
      margin-top: 20px;
      background-color: #d1c4e9;
      color: #2c3e50;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .submit-button:hover {
      background-color: #e8e0f8;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🌙 今日の気分を記録しよう</h2>
    <form action="save_mood.php" method="post">
      <div class="hearts">
        <span class="heart" data-value="1">🤍</span>
        <span class="heart" data-value="2">🤍</span>
        <span class="heart" data-value="3">🤍</span>
      </div>
      <input type="hidden" name="mood" id="mood" value="0">
      <textarea name="comment" placeholder="今日のひとことをどうぞ…" required></textarea>
      <br>
      <input type="submit" value="登録" class="submit-button">
    </form>
  </div>

  <script>
    const hearts = document.querySelectorAll('.heart');
    const moodInput = document.getElementById('mood');

    hearts.forEach((heart, index) => {
      heart.addEventListener('click', () => {
        const current = parseInt(moodInput.value);
        const clicked = index + 1;

        // 同じハートをもう一度クリックしたら、そこから右側をOFFに
        if (current === clicked) {
          moodInput.value = clicked - 1;
        } else {
          moodInput.value = clicked;
        }

        // ハートの表示を更新
        const mood = parseInt(moodInput.value);
        hearts.forEach((h, i) => {
          h.textContent = i < mood ? '💗' : '🤍';
        });
      });
    });
  </script>
</body>
</html>

