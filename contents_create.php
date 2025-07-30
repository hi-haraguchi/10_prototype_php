<?php
session_start();
include("functions.php");
check_session_id();

$mailaddress = $_SESSION["mailaddress"];
$user_id = $_SESSION["user_id"];

if (
  !isset($_POST['title']) || $_POST['title'] === '' ||
  !isset($_POST['auther']) || $_POST['auther'] === '' ||  
  !isset($_POST['kind']) || $_POST['kind'] === ''
) {
  exit('paramError');
}

$title = $_POST['title'];
$auther = $_POST['auther'];
$kind = $_POST['kind'];

$pdo = connect_to_db();


// かぶりをチェック


$sql = 'SELECT COUNT(*) FROM contents_table WHERE title=:title AND auther=:auther AND kind=:kind AND user_id=:user_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':auther', $auther, PDO::PARAM_STR);
$stmt->bindValue(':kind', $kind, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

if ($stmt->fetchColumn() > 0) {
  echo "<p>すでに登録されているコンテンツです．</p>";
  echo '<a href="contents_input.php">コンテンツの入力画面へ</a>';
  exit();
}


// コンテンツ登録

$sql = 'INSERT INTO contents_table(contents_id, user_id, title, kind, auther) VALUES(NULL, :user_id, :title, :kind, :auther)';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':kind', $kind, PDO::PARAM_STR);
$stmt->bindValue(':auther', $auther, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:contents_input.php");
exit();
