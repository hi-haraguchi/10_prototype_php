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
            max-width: 600px; /* Align with form width */
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
            max-width: 600px; /* Align with form width */
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            width: 100%;
            max-width: 600px; /* Align with form width */
            margin: 20px 0;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Increased width for the form */
            display: flex;
            flex-direction: column;
            gap: 15px; /* Space between form fields */
            margin-top: 20px; /* Space from hr */
        }

        /* Navigation links at the top of the form */
        .form-nav-links {
            display: flex;
            gap: 20px; /* Space between links */
            margin-bottom: 20px;
            justify-content: center; /* Center the links */
            width: 100%;
        }

        .form-nav-links a {
            background-color: #e9ecef;
            color: #007bff;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .form-nav-links a:hover {
            background-color: #007bff;
            color: white;
        }

        form div {
            display: flex;
            align-items: center; /* Vertically align label and input/select */
            margin-bottom: 10px;
        }

        label {
            flex-basis: 200px; /* Increased width for labels to accommodate longer text */
            text-align: right; /* Align label text to the right */
            margin-right: 15px; /* Space between label and input/select */
            color: #444;
            font-weight: bold;
        }

        input[type="text"],
        select {
            flex-grow: 1; /* Input/Select takes up remaining space */
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            -webkit-appearance: none; /* Remove default select styles */
            -moz-appearance: none;
            appearance: none;
            background-color: #fff;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292%22%20height%3D%22292%22%3E%3Cpath%20fill%3D%22%23000000%22%20d%3D%22M287%2C188.76l-14.3-14.3c-2.8-2.8-6.2-4.1-9.7-4.1s-6.9%2C1.4-9.7%2C4.1l-117.3%2C117.3c-2.8%2C2.8-6.2%2C4.1-9.7%2C4.1s-6.9-1.4-9.7-4.1l-117.3-117.3c-2.8-2.8-6.2-4.1-9.7-4.1L5%2C188.76c-5.4%2C5.4-5.4%2C14.2%2C0%2C19.6s14.2%2C5.4%2C19.6%2C0l107.5-107.5l107.5%2C107.5c5.4%2C5.4%2C14.2%2C5.4%2C19.6%2C0S292.4%2C194.16%2C287%2C188.76z%22%2F%3E%3C%2Fsvg%3E'); /* Custom dropdown arrow */
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px;
            padding-right: 30px; /* Space for the custom arrow */
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        button {
            background-color: #007bff; /* Blue button */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
            width: 100%; /* Make button full width */
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <div class="header-top">
        <p><?= $mailaddress ?></p>
        <a href="logout.php">ログアウト</a>
    </div>

    <h3>触れたエンタメの入力</h3>
    <hr>    

  <form action="contents_create.php" method="POST">

      <a href="my_contents_read.php">触れたエンタメの一覧画面</a>
      <a href="all_tag_read.php">エンタメを探す</a>

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
  </form>

</body>

</html>