<?php
session_start();

include("functions.php");
check_session_id();

$contents_id = $_GET["contents_id"];
// $title = $_GET["title"];
// $auther = $_GET["auther"];


$pdo = connect_to_db();

$sql = 'SELECT * FROM contents_table WHERE contents_id=:contents_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':contents_id', $contents_id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$record = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>感想の追加画面</title>
</head>

<body>
  <form action="feelings_create.php" method="POST">
    <fieldset>
      <legend>感想の入力・追加画面</legend>
      <a href="my_contents_read.php">一覧画面</a>

      <?= $record["title"] ?>

      <?= $record["auther"] ?>

      <input type="hidden" name="contents_id" value="<?= $record["contents_id"] ?>">

      <div>
        <label for="part">巻数やページ数、どの部分が印象に残ったか入力してください。</label>
        <input type="text" name="part" id="part">
      </div>

      <p>エンタメに触れた日を入力してください。（月と日は空欄でも可）</p>

      <div>
        <input type="number" name="experienced_year" id="experienced_year">
        <label for="experienced_year	">年</label>
      </div>
      
      <div>
        <input type="number" name="experienced_month" id="experienced_month">
        <label for="experienced_month">月</label>
      </div>
      
      <div>
        <input type="number" name="experienced_day" id="experienced_day">
        <label for="experienced_day">日</label>
      </div>

      <div>
        <label for="feelings">感想を１つ入力してください。</label>
        <input type="text" name="feelings" id="feelings">
      </div>

      <div>
        <label for="tag">どんなときのこのエンタメに触れたいですか？</label>
        <input type="text" name="tag" id="tag">
      </div>      



      <div>
        <button>感想を追加します！</button>
      </div>




    </fieldset>
  </form>

</body>

</html>