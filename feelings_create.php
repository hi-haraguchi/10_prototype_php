<?php

// var_dump($_POST);
// exit();


session_start();
include("functions.php");
check_session_id();

if (
  !isset($_POST['contents_id']) || $_POST['contents_id'] === '' ||
  !isset($_POST['experienced_year']) || $_POST['experienced_year'] === '' ||
  !isset($_POST['feelings']) || $_POST['feelings'] === ''
) {
  exit('paramError');
}

$contents_id = $_POST["contents_id"];
$part = $_POST["part"];
$experienced_year = $_POST["experienced_year"];
$experienced_month = $_POST["experienced_month"];
$experienced_day = $_POST["experienced_day"];
$feelings = $_POST["feelings"];
$tag = $_POST["tag"];

$pdo = connect_to_db();

$sql = 'INSERT INTO feelings_table(feelings_id, contents_id, part, experienced_year, experienced_month, experienced_day, feelings, tag, created_at) VALUES(NULL, :contents_id, :part, :experienced_year, :experienced_month, :experienced_day, :feelings, :tag, now())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':contents_id', $contents_id, PDO::PARAM_STR);
$stmt->bindValue(':part',$part,($part === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);
$stmt->bindValue(':experienced_year', $experienced_year, PDO::PARAM_INT);
$stmt->bindValue(':experienced_month',$experienced_month,($experienced_month === null) ? PDO::PARAM_NULL : PDO::PARAM_INT);
$stmt->bindValue(':experienced_day',$experienced_day,($experienced_day === null) ? PDO::PARAM_NULL : PDO::PARAM_INT);
$stmt->bindValue(':feelings', $feelings, PDO::PARAM_STR);
$stmt->bindValue(':tag',$tag,($tag === null) ? PDO::PARAM_NULL : PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:my_contents_read.php");
exit();
