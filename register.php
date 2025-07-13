<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("funcs.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $pw = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $pdo = db_conn();
  $stmt = $pdo->prepare("INSERT INTO users(name,email,password) VALUES(:name,:email,:password)");
  $stmt->bindValue(':name', $name);
  $stmt->bindValue(':email', $email);
  $stmt->bindValue(':password', $pw);
  $stmt->execute();

  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ユーザー登録</title>
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

  .register-box {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(255,255,255,0.1);
    text-align: center;
    width: 300px;
  }

  .register-box h2 {
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
  <div class="register-box">
    <h2>🌙 ユーザー登録</h2>
    <form method="post">
      <input type="text" name="name" placeholder="お名前" required><br>
      <input type="email" name="email" placeholder="メールアドレス" required><br>
      <input type="password" name="password" placeholder="パスワード" required><br>
      <input type="submit" value="登録">
    </form>
    <a href="login.php">← ログインはこちら</a>
  </div>
</body>
</html>

