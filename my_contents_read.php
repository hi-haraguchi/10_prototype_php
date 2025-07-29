<?php
session_start();
include("functions.php");
check_session_id();

$mailaddress = $_SESSION["mailaddress"];
$user_id = $_SESSION["user_id"];

$pdo = connect_to_db();


$sql = '
        SELECT
            c.contents_id,
            c.title,
            c.kind,
            f.feelings_id,
            f.experienced_year,
            f.experienced_month,
            f.experienced_day,
            f.part,
            f.feelings,
            f.tag
        FROM
            contents_table AS c
        LEFT JOIN
            feelings_table AS f ON c.contents_id = f.contents_id
        WHERE
            c.user_id = :user_id
        ORDER BY
            c.contents_id, f.experienced_year, f.experienced_month, f.experienced_day;
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT); // user_id は通常数値型

    try {
        $status = $stmt->execute();
    } catch (PDOException $e) {
        echo json_encode(["sql error" => "{$e->getMessage()}"]);
        exit();
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得したデータをcontentsごとに整理する
    $organized_contents = [];
    foreach ($results as $record) {
        $contents_id = $record['contents_id'];

        if (!isset($organized_contents[$contents_id])) {
            // 新しいcontentの場合、基本情報を追加
            $organized_contents[$contents_id] = [
                'title' => htmlspecialchars($record['title'], ENT_QUOTES, 'UTF-8'),
                'kind' => htmlspecialchars($record['kind'], ENT_QUOTES, 'UTF-8'),
                'feelings' => [] // feelingsを格納する配列
            ];
        }

        // feelings_id が null でない場合は feeling 情報を追加
        // これは LEFT JOIN で feelings が見つからなかった場合に feelings_id が null になるため
        if ($record['feelings_id'] !== null) {
            $organized_contents[$contents_id]['feelings'][] = [
                'experienced_year' => htmlspecialchars($record['experienced_year'], ENT_QUOTES, 'UTF-8'),
                'experienced_month' => ($record['experienced_month'] !== null) ? htmlspecialchars($record['experienced_month'], ENT_QUOTES, 'UTF-8') : '---',
                'experienced_day' => ($record['experienced_day'] !== null) ? htmlspecialchars($record['experienced_day'], ENT_QUOTES, 'UTF-8') : '---',
                'part' => ($record['part'] !== null) ? htmlspecialchars($record['part'], ENT_QUOTES, 'UTF-8') : '---',
                'feeling_text' => htmlspecialchars($record['feelings'], ENT_QUOTES, 'UTF-8'),
                'tag' => ($record['tag'] !== null) ? htmlspecialchars($record['tag'], ENT_QUOTES, 'UTF-8') : '---'
            ];
        }
    }


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>触れたエンタメ一覧</title>
</head>

<body>
  <fieldset>
    <legend>触れたエンタメ一覧</legend>
    <?= $mailaddress ?>
    <a href="contents_input.php">エンタメ入力画面</a>
    <a href="logout.php">logout</a>


     <?php if (empty($organized_contents)): ?>
        <p>登録されたコンテンツはありません。</p>
    <?php else: ?>
        <?php foreach ($organized_contents as $contents_id => $content_data): ?>
            <div class="content-item">
                <h2 class="content-title"><?= $content_data['title'] ?> (<?= $content_data['kind'] ?>)</h2>
                <?php if (empty($content_data['feelings'])): ?>
                    <p class="no-feelings">まだ感情が登録されていません。</p>
                <?php else: ?>
                    <ul class="feeling-list">
                        <?php foreach ($content_data['feelings'] as $feeling): ?>
                            <li class="feeling-item">
                                <span><?= $feeling['experienced_year'] ?>年</span>
                                <span><?= $feeling['experienced_month'] ?>月</span>
                                <span><?= $feeling['experienced_day'] ?>日</span>
                                <span>(<?= $feeling['part'] ?>)</span>
                                <span><?= $feeling['feeling_text'] ?></span>
                                <span>タグ: <?= $feeling['tag'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>



  </fieldset>
</body>

</html>