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
            c.auther,
            c.kind,
            c.user_id,
            c.selected10,
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

        $display_kind = htmlspecialchars($record['kind'], ENT_QUOTES, 'UTF-8');
        switch ($record['kind']) {
            case 'printbook':
                $display_kind = '本';
                break;
            case 'manga':
                $display_kind = 'マンガ';
                break;
            case 'movie':
                $display_kind = '映画';
                break;
            case 'music':
                $display_kind = '音楽';
                break;
            case 'podcast':
                $display_kind = 'ポッドキャスト';
                break;
            case 'others':
                $display_kind = 'その他';
                break;                
        }

            // 新しいcontentの場合、基本情報を追加
            $organized_contents[$contents_id] = [
                'title' => htmlspecialchars($record['title'], ENT_QUOTES, 'UTF-8'),
                'auther' => htmlspecialchars($record['auther'], ENT_QUOTES, 'UTF-8'),
                'kind' => $display_kind,
                'contents_id' => $contents_id, 
                'user_id' => htmlspecialchars($record['user_id'], ENT_QUOTES, 'UTF-8'),
                'selected10' => htmlspecialchars($record['selected10'], ENT_QUOTES, 'UTF-8'),
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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5; /* Light grey background */
            color: #333;
            box-sizing: border-box;
        }

        .header-right-align {
            display: flex; /* Flexboxを有効にする */
            justify-content: flex-end; /* アイテムをコンテナの右端に寄せる */
            align-items: center; /* アイテムを垂直方向の中央に揃える */
            gap: 20px; /* アイテム間のスペース */
            margin-bottom: 20px; /* 下部との余白 */
            padding: 0 10px; /* 左右のパディング */
        }

        /* Header containing mailaddress and logout */
        .header-top {
            display: flex;
            justify-content: space-between; /* Pushes items to ends */
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px; /* Some padding for alignment */
        }

        .header-top p {
            margin: 0; /* Remove default paragraph margin */
            font-size: 1em;
            color: #555;
            font-weight: bold;
        }

        .header-top .links {
            display: flex;
            gap: 15px; /* Space between logout and other links */
        }

        .header-top a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.95em;
            transition: color 0.3s ease;
        }

        .header-top a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        h3 {
            font-size: 1.8em;
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            text-align: center;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 30px 0;
        }

        /* Navigation links below the hr */
        .nav-links {
            display: flex;
            gap: 20px; /* Space between navigation links */
            margin-bottom: 30px;
            justify-content: center; /* Center the navigation links */
        }

        .nav-links a {
            background-color: #e9ecef;
            color: #007bff;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #007bff;
            color: white;
        }


        /* Content List Items */
        .content-item {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative; /* For positioning child elements */
        }

        .content-title {
            font-size: 1.8em;
            color: #2c3e50; /* Darker title color */
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #f0f0f0;
        }

        .content-actions {
            display: flex;
            gap: 15px; /* Space between action links */
            margin-bottom: 15px;
        }

        .content-actions a {
            display: inline-block;
            background-color: #6c757d; /* Gray for actions */
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .content-actions a:hover {
            background-color: #5a6268;
        }

        /* Specific style for the like/selected10 button */
        .like-button {
            background-color: #ffc107; /* Yellow for like button */
            color: #333;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1em; /* Slightly larger for emphasis */
            margin-left: auto; /* Pushes the like button to the right within its flex container */
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .like-button:hover {
            background-color: #e0a800;
            transform: translateY(-1px);
        }


        .feeling-list {
            list-style: none;
            padding: 0;
            margin-top: 20px;
            border-top: 1px dashed #e0e0e0;
            padding-top: 20px;
        }

        .feeling-item {
            background-color: #fefefe;
            border-left: 4px solid #007bff; /* More prominent border */
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 6px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.03);
            display: flex; /* Use flexbox for feeling details */
            flex-wrap: wrap; /* Allow wrapping on small screens */
            gap: 10px; /* Space between feeling spans */
            align-items: center;
        }

        .feeling-item span {
            color: #34495e; /* Darker text for feeling details */
            font-size: 0.95em;
        }

        .feeling-item span:first-of-type { /* Style for year */
            font-weight: bold;
        }
        .feeling-item span:last-of-type { /* Style for tag */
            font-style: italic;
            color: #666;
        }


        .no-feelings {
            color: #888;
            font-style: italic;
            margin-top: 20px;
            padding: 10px;
            background-color: #fdfdff;
            border-radius: 5px;
            border: 1px dashed #e0e0e0;
            text-align: center;
        }

        /* No contents message */
        .no-contents {
            text-align: center;
            font-size: 1.2em;
            color: #888;
            margin-top: 50px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>
    <div class="header-right-align">
        <p><?= htmlspecialchars($mailaddress, ENT_QUOTES, 'UTF-8') ?></p>
        <a href="logout.php">ログアウト</a>
    </div>

    <h3>触れたエンタメ一覧</h3>
    <hr>
    
    <div class="header-right-align">
    <a href="contents_input.php">エンタメ入力画面</a>
    <a href="makeup_whoiam.php">自分を構成するエンタメ一覧</a>
    </div>

    <?php if (empty($organized_contents)): ?>
        <p>登録されたコンテンツはありません。</p>
    <?php else: ?>
        <?php foreach ($organized_contents as $contents_id => $content_data): ?>
            <div class="content-item">
                <h2 class="content-title"><?= $content_data['title'] ?> <?= $content_data['auther'] ?>(<?= $content_data['kind'] ?>)</h2>

                <a href="feelings_input.php?
                user_id=<?= htmlspecialchars($content_data['user_id'], ENT_QUOTES, 'UTF-8') ?>
                &contents_id=<?= htmlspecialchars($content_data['contents_id'], ENT_QUOTES, 'UTF-8') ?>
                &title=<?= htmlspecialchars($content_data['title'], ENT_QUOTES, 'UTF-8') ?>
                &auther=<?= htmlspecialchars($content_data['auther'], ENT_QUOTES, 'UTF-8') ?>
                ">感想の追加</a>

                <a href="like_create.php?contents_id=<?= htmlspecialchars($content_data['contents_id'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($content_data['selected10'], ENT_QUOTES, 'UTF-8') ?>
                </a>

                <?php if (empty($content_data['feelings'])): ?>
                    <p class="no-feelings">まだ感想が登録されていません。</p>
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


</body>

</html>