<?php
session_start();
include("functions.php");
check_session_id();

$user_id = $_SESSION["user_id"]; 

$pdo = connect_to_db(); 

$sql = 'SELECT title, auther FROM contents_table WHERE selected10 = "☆" AND user_id = :user_id ORDER BY title ASC';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT); 

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // 結果を連想配列で取得

$output = "";
$counter = 1; // 1, 2, 3... の番号を表示するためのカウンター
foreach ($result as $record) {
    $title = htmlspecialchars($record["title"], ENT_QUOTES, 'UTF-8');
    $auther = htmlspecialchars($record["auther"], ENT_QUOTES, 'UTF-8');

    $output .= "
        <p>{$counter}. {$title} {$auther}</p>
    ";
    $counter++; // カウンターをインクリメント
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>自分を構成するエンタメ要素一覧</title>
</head>

<body>
    <fieldset>
        <legend>自分を構成するエンタメ要素一覧（１０個まで登録可）</legend>
        <a href="my_contents_read.php">元のエンタメ一覧画面へ</a>
        <a href="logout.php">logout</a>
        <hr>

        <?php if (empty($result)): // $resultが空の場合（お気に入りがない場合） ?>
            <p>お気に入りのコンテンツはまだ登録されていません。</p>
        <?php else: ?>
            <?= $output ?>
        <?php endif; ?>

    </fieldset>
</body>


</html>