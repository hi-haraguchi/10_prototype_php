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
    <title>感想の入力・追加画面</title>
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

        h3 {
            font-size: 1.8em;
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            text-align: center;
            width: 100%;
            max-width: 1080px; /* Align with form width */
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            width: 100%;
            max-width: 1080px; /* Align with form width */
            margin: 20px 0;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1080px; /* Consistent form width */
            display: flex;
            flex-direction: column;
            gap: 15px; /* Space between form fields */
            margin-top: 20px; /* Space from hr */
        }

        .form-nav-link { /* Style for the single navigation link */
            display: block; /* Make it block to take full width */
            text-align: center; /* Center the text */
            background-color: #e9ecef;
            color: #007bff;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px; /* Space below the link */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .form-nav-link:hover {
            background-color: #007bff;
            color: white;
        }

        /* Styles for displaying title and author */
        .content-display {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e0e0e0;
        }

        form > div { /* Direct children divs of the form */
            display: flex;
            align-items: center; /* Vertically align label and input */
            margin-bottom: 10px; /* Space between form field groups */
        }

        /* Specific style for date inputs to avoid label text wrapping too much */
        .date-input-group {
            display: flex;
            align-items: center;
            gap: 5px; /* Space between number input and "年", "月", "日" labels */
            flex-grow: 1; /* Allow this group to take up space */
        }

        .date-input-group input[type="number"] {
            width: 70px; /* Fixed width for number inputs */
            text-align: center;
            padding: 8px 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .date-input-group input[type="number"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        label {
            flex-basis: 480px; /* Increased width for labels to accommodate longer text */
            text-align: right; /* Align label text to the right */
            margin-right: 15px; /* Space between label and input */
            color: #444;
            font-weight: bold;
        }

        /* Special label for "年", "月", "日" */
        .date-unit-label {
            margin-right: 10px; /* Space after "年", "月", "日" */
            color: #555;
            font-weight: normal; /* Not bold */
        }


        input[type="text"] {
            flex-grow: 1; /* Input takes up remaining space */
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        p {
            color: #555;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        button {
            background-color: #28a745; /* Green button for adding feelings */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px; /* More space above the button */
            width: 100%; /* Make button full width */
        }

        button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <?php
    // Assuming these variables are passed from the previous page
    // and properly sanitized before use in the HTML.
    // For demonstration, let's set some dummy values if they aren't defined.
    $mailaddress = $mailaddress ?? 'user@example.com';
    $record = $record ?? ['title' => '作品タイトル', 'auther' => '作者名', 'contents_id' => '123'];
    ?>

    <h3>感想の入力・追加画面</h3>
    <hr>

    <form action="feelings_create.php" method="POST">

        <a href="my_contents_read.php" class="form-nav-link">一覧画面へ戻る</a>

        <p class="content-display">
            <strong><?= htmlspecialchars($record["title"], ENT_QUOTES, 'UTF-8') ?></strong>
            <?= htmlspecialchars($record["auther"], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <input type="hidden" name="contents_id" value="<?= htmlspecialchars($record["contents_id"], ENT_QUOTES, 'UTF-8') ?>">

        <div>
            <label for="part">巻数やページ数、どの部分が印象に残ったか入力してください。</label>
            <input type="text" name="part" id="part">
        </div>

        <p>エンタメに触れた日を入力してください。（月と日は空欄でも可）</p>

        <div>
            <label></label> <div class="date-input-group">
                <input type="number" name="experienced_year" id="experienced_year" placeholder="例: 2023">
                <label for="experienced_year" class="date-unit-label">年</label>

                <input type="number" name="experienced_month" id="experienced_month" placeholder="例: 12">
                <label for="experienced_month" class="date-unit-label">月</label>

                <input type="number" name="experienced_day" id="experienced_day" placeholder="例: 25">
                <label for="experienced_day" class="date-unit-label">日</label>
            </div>
        </div>

        <div>
            <label for="feelings">感想を１つ入力してください。</label>
            <input type="text" name="feelings" id="feelings">
        </div>

        <div>
            <label for="tag">どんなときのこのエンタメに触れたいですか？<br>このエンタメでどんな気持ちになりますか？</label>
            <input type="text" name="tag" id="tag">
        </div>

        <div>
            <button>感想を追加します！</button>
        </div>

    </form>

</body>
</html>