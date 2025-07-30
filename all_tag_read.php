<?php
session_start();
include("functions.php");
check_session_id();

$user_id = $_SESSION["user_id"];

$pdo = connect_to_db();

$sql = '
    SELECT
        c.contents_id,
        c.title,
        c.auther,
        f.part,
        f.feelings,
        f.tag
    FROM
        contents_table AS c
    JOIN
        feelings_table AS f ON c.contents_id = f.contents_id
    WHERE
        f.tag IS NOT NULL -- tagがNULLでないものに絞る (タグごとの表示なので)
    ORDER BY
        f.tag, c.title, f.part; -- タグ、タイトル、パートでソート
';

$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$organized_by_tag = [];
foreach ($results as $record) {
    $tag = htmlspecialchars($record['tag'], ENT_QUOTES, 'UTF-8');

    // まだこのタグのセクションがなければ作成
    if (!isset($organized_by_tag[$tag])) {
        $organized_by_tag[$tag] = [];
    }

    // 各アイテムの情報を格納
    $organized_by_tag[$tag][] = [
        'title' => htmlspecialchars($record['title'], ENT_QUOTES, 'UTF-8'),
        'auther' => htmlspecialchars($record['auther'], ENT_QUOTES, 'UTF-8'),
        'part' => ($record['part'] !== null) ? htmlspecialchars($record['part'], ENT_QUOTES, 'UTF-8') : '---',
        // feelingsも表示したい場合は追加
        // 'feelings' => htmlspecialchars($record['feelings'], ENT_QUOTES, 'UTF-8')
    ];
}

?>



<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>エンタメを探す</title>
      <style>
        body { font-family: sans-serif; margin: 20px; }
        .tag-section { margin-bottom: 30px; border: 1px solid #eee; padding: 15px; border-radius: 8px; background-color: #fcfcfc; }
        .tag-heading { font-size: 1.8em; color: #0056b3; border-bottom: 2px solid #0056b3; padding-bottom: 5px; margin-top: 0; }
        .content-entry { margin-bottom: 10px; padding-left: 10px; border-left: 3px solid #ccc; }
        .content-details { font-size: 1.1em; margin-bottom: 3px; }
        .no-entries { color: #888; font-style: italic; }
    </style>
</head>

<body>
  <fieldset>
    <legend>エンタメを探す</legend>
    <a href="contents_input.php">エンタメ入力画面</a>
    <a href="logout.php">logout</a>
<?php if (empty($organized_by_tag)): ?>
        <p class="no-entries">登録されたタグ付きコンテンツはありません。</p>
    <?php else: ?>
        <?php foreach ($organized_by_tag as $tag => $contents_and_feelings): ?>
            <div class="tag-section">
                <h2 class="tag-heading"><?= $tag ?></h2>
                <?php foreach ($contents_and_feelings as $item): ?>
                    <div class="content-entry">
                        <p class="content-details">
                            <strong><?= $item['title'] ?></strong>
                            <?php if (!empty($item['auther'])): ?>
                                (<?= $item['auther'] ?>)
                            <?php endif; ?>
                            <?php if (!empty($item['part']) && $item['part'] !== '---'): ?>
                                [<?= $item['part'] ?>]
                            <?php endif; ?>
                        </p>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </fieldset>
</body>

</html>