<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include("funcs.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST["email"];
  $pw = $_POST["password"];

  $pdo = db_conn();
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindValue(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch();

  if ($user && password_verify($pw, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["name"];
    header("Location: home.php");
    exit();
  } else {
    $error = "メールアドレスまたはパスワードが正しくありません。";
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Moon Diary - ログイン</title>
  <style>
  body {
    margin: 0;
    font-family: 'Helvetica Neue', sans-serif;
    background: linear-gradient(#1c1c3c, #4b3c5c);
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .login-box {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(255,255,255,0.1);
    text-align: center;
    width: 300px;
  }

  .login-box h2 {
    margin-bottom: 20px;
    font-weight: normal;
    color: #f8bbd0;
  }

  input[type="text"],
  input[type="email"],
  input[type="password"],
  input[type="submit"] {
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    margin-bottom: 15px;
    border: none;
    border-radius: 6px;
    background: rgba(255,255,255,0.1);
    color: white;
  }

  input[type="submit"] {
    background: #b39ddb;
    color: #2c3e50;
    cursor: pointer;
    transition: 0.3s;
  }

  input[type="submit"]:hover {
    background: #d1c4e9;
  }

  a {
    color: #f8bbd0;
    display: block;
    margin-top: 10px;
    text-decoration: none;
  }
</style>
</head>
<body>
  <div class="login-box">
    <h2>🌙 Moon Diary</h2>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="post">
      <input type="email" name="email" placeholder="メールアドレス" required><br>
      <input type="password" name="password" placeholder="パスワード" required><br>
      <input type="submit" value="ログイン">
    </form>
    <a href="register.php">新規登録はこちら</a>
    <div class="quote">
      今日のひとこと：<br>
      <?php
        $quotes = ["焦らず、心の声を聴いて。", "小さな一歩が、やがて奇跡に。", "あなたは、あなたのままでいい。"];
        echo $quotes[array_rand($quotes)];
      ?>
    </div>
  </div>
</body>
</html>