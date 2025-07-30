<?php
include('functions.php');

if (
  !isset($_POST['mailaddress']) || $_POST['mailaddress'] === '' ||
  !isset($_POST['password']) || $_POST['password'] === '' ||
  !isset($_POST['interval_printbook']) || $_POST['interval_printbook'] === '' ||
  !isset($_POST['interval_manga']) || $_POST['interval_manga'] === '' ||
  !isset($_POST['interval_movie']) || $_POST['interval_movie'] === '' ||
  !isset($_POST['interval_music']) || $_POST['interval_music'] === '' ||
  !isset($_POST['interval_podcast']) || $_POST['interval_podcast'] === ''||
  !isset($_POST['limit_search']) || $_POST['limit_search'] === ''
) {
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

$mailaddress = $_POST["mailaddress"];
$password = $_POST["password"];
$interval_printbook = $_POST["interval_printbook"];
$interval_manga = $_POST["interval_manga"];
$interval_movie = $_POST["interval_movie"];
$interval_music = $_POST["interval_music"];
$interval_podcast = $_POST["interval_podcast"];
$limit_search = $_POST["limit_search"];

$pdo = connect_to_db();

$sql = 'SELECT COUNT(*) FROM users_table WHERE mailaddress=:mailaddress';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':mailaddress', $mailaddress, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

if ($stmt->fetchColumn() > 0) {
  echo "<p>すでに登録されているユーザです．</p>";
  echo '<a href="login.php">login</a>';
  exit();
}

$sql = 'INSERT INTO users_table(user_id, mailaddress, password, interval_printbook, interval_manga, interval_movie, interval_music, interval_podcast, limit_search) VALUES(NULL, :mailaddress, :password, :interval_printbook, :interval_manga, :interval_movie, :interval_music, :interval_podcast, :limit_search)';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':mailaddress', $mailaddress, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$stmt->bindValue(':interval_printbook',$interval_printbook,($interval_printbook === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);
$stmt->bindValue(':interval_manga',$interval_manga,($interval_manga === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);
$stmt->bindValue(':interval_movie',$interval_movie,($interval_movie === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);
$stmt->bindValue(':interval_music',$interval_music,($interval_music === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);
$stmt->bindValue(':interval_podcast',$interval_podcast,($interval_podcast === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);
$stmt->bindValue(':limit_search',$limit_search,($limit_search === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);


try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:login.php");
exit();
