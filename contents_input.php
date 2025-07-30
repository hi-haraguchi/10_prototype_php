<?php
session_start();
include("functions.php");
check_session_id();

$mailaddress = $_SESSION["mailaddress"];
$user_id = $_SESSION["user_id"];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>触れたエンタメの入力</title>
</head>

<body>
  <form action="contents_create.php" method="POST">
    <fieldset>
      <legend>触れたエンタメの入力</legend>
      <?= $mailaddress ?>
      <a href="my_contents_read.php">触れたエンタメの一覧画面</a>
      <a href="all_tag_read.php">エンタメを探す</a>
      <a href="logout.php">logout</a>
      <div>
        <label for="title">タイトルや作品名</label>
        <input type="text" name="title" id="title">
      </div>

      <div>
        <label for="auther">作者や監督、出演者など</label>
        <input type="text" name="auther" id="auther">
      </div>      

      <div>
        <label for="kind">種類</label>
        <select id="kind" name="kind">
          <option value="">選んでください</option>          
          <option value="printbook">本</option>
          <option value="manga">マンガ</option>
          <option value="movie">映画</option>
          <option value="music">音楽</option>
          <option value="podcast">ポッドキャスト</option>
          <option value="others">その他</option>          
        </select>
      </div>

      <div>
        <button>コンテンツ登録</button>
      </div>
    </fieldset>
  </form>

</body>

</html>