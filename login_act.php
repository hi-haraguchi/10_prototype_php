<?php
session_start();
include('functions.php');

$mailaddress = $_POST['mailaddress'];
$password = $_POST['password'];

$pdo = connect_to_db();

$sql = 'SELECT * FROM users_table WHERE mailaddress=:mailaddress AND password=:password AND deleted_at IS NULL';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':mailaddress', $mailaddress, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  echo "<p>ログイン情報に誤りがあります</p>";
  echo "<a href=login.php>ログイン</a>";
  exit();
} else {
  $_SESSION = array();
  $_SESSION['session_id'] = session_id();
  $_SESSION['mailaddress'] = $user['mailaddress'];
  $_SESSION['user_id'] = $user['user_id'];
  header("Location:my_contents_read.php");
  exit();
}
