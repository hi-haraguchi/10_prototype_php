<?php
session_start();
include("functions.php");
check_session_id();

$user_id = $_SESSION["user_id"];
$mailaddress = $_SESSION["mailaddress"];

$pdo = connect_to_db();

// SQLクエリ: kindカラムも取得するように変更
$sql = 'SELECT title, auther, kind FROM contents_table WHERE selected10 = "☆" AND user_id = :user_id ORDER BY title ASC';

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
    $auther = htmlspecialchars($record["auther"] ?? '', ENT_QUOTES, 'UTF-8'); // autherがない場合も考慮

    // kindの値を日本語に変換
    $display_kind = htmlspecialchars($record['kind'] ?? '', ENT_QUOTES, 'UTF-8'); // まずはデフォルトでエスケープ
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

    $output .= "
        <p>{$counter}. {$title} {$auther} ({$display_kind})</p>
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
  <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5; /* Light grey background */
            color: #333;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center content horizontally */
            min-height: 100vh; /* Full viewport height */
        }

        /* Header containing mailaddress and logout */
        .header-top {
            display: flex;
            justify-content: flex-end; /* Pushes items to the right end */
            align-items: center;
            gap: 20px; /* Space between items */
            margin-bottom: 20px;
            width: 100%; /* Take full width to allow right alignment */
            max-width: 800px; /* Adjust max-width to suit content */
            padding: 0 10px; /* Small padding */
            box-sizing: border-box;
        }

        .header-top p {
            margin: 0;
            font-size: 1em;
            color: #555;
            font-weight: bold;
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
            width: 100%;
            max-width: 800px; /* Align with content width */
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            width: 100%;
            max-width: 800px; /* Align with content width */
            margin: 20px 0;
        }

        /* Navigation link for "元のエンタメ一覧画面へ" */
        .nav-link {
            display: block; /* Make it a block element */
            text-align: center;
            background-color: #e9ecef;
            color: #007bff;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 30px; /* Space below the link */
            transition: background-color 0.3s ease, color 0.3s ease;
            width: 100%;
            max-width: 300px; /* Limit width for the button-like link */
        }

        .nav-link:hover {
            background-color: #007bff;
            color: white;
        }

        /* Container for the list of contents */
        .content-list-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px; /* Max width for the content area */
            box-sizing: border-box;
        }

        .content-list-container p {
            font-size: 1.1em;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px dashed #eee; /* Subtle separator for each item */
            color: #444;
        }

        .content-list-container p:last-child {
            border-bottom: none; /* No border for the last item */
            margin-bottom: 0;
        }

        /* Message for no content */
        .no-content-message {
            text-align: center;
            font-size: 1.2em;
            color: #888;
            margin-top: 50px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px; /* Smaller width for message box */
        }
    </style>


</head>

<body>
    <div class="header-top">
        <p><?= $mailaddress ?></p>
        <a href="logout.php">ログアウト</a>
    </div>

        <h3>自分を構成するエンタメ要素一覧（１０個まで登録可）</h3>
        <hr>

        <a href="my_contents_read.php">元のエンタメ一覧画面へ</a>

        <?php if (empty($result)): // $resultが空の場合（お気に入りがない場合） ?>
            <p>お気に入りのコンテンツはまだ登録されていません。</p>
        <?php else: ?>
            <?= $output ?>
        <?php endif; ?>

</body>


</html>