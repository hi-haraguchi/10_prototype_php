<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f0f2f5; /* Light grey background */
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh; /* Full viewport height */
            box-sizing: border-box;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.2em;
            text-align: center;
        }

        h3 {
            color: #555;
            margin-bottom: 30px;
            font-size: 1.5em;
            text-align: center;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            width: 100%;
            max-width: 400px; /* Constrain width for a cleaner look */
            margin: 20px 0;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px; /* Fixed width for the form */
            display: flex;
            flex-direction: column;
            gap: 15px; /* Space between form fields */
        }

        form div {
            display: flex;
            align-items: center; /* Vertically align label and input */
            margin-bottom: 10px;
        }

        label {
            flex-basis: 120px; /* Fixed width for labels */
            text-align: right; /* Align label text to the right */
            margin-right: 15px; /* Space between label and input */
            color: #444;
            font-weight: bold;
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

        button {
            background-color: #007bff;
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

        a {
            color: #007bff;
            text-decoration: none;
            text-align: center;
            display: block; /* Make link take full width for centering */
            margin-top: 15px;
            font-size: 0.95em;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>

<body>

  <h1>エンタメフロート　エンタメの記録と共有アプリ</h1>
  <h3>ログインページ</h3>
  <hr>
  <form action="login_act.php" method="POST">
      <div>
        <label for="mailaddress">メールアドレス:</label>
        <input type="text" name="mailaddress" id="mailaddress">
      </div>
      <div>
        <label for="password">パスワード:</label>
        <input type="text" name="password" id="password">
      </div>
      <div>
        <button>ログイン</button>
      </div>
      <a href="user_register.php">ユーザ登録がまだなら、登録画面へ</a>
  </form>

</body>

</html>