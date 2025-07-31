<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザ登録</title>
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
            max-width: 500px; /* Wider for more form fields */
            margin: 20px 0;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px; /* Adjusted width for the registration form */
            display: flex;
            flex-direction: column;
            gap: 15px; /* Space between form fields */
        }

        form div {
            display: flex;
            align-items: center; /* Vertically align label and input/select */
            margin-bottom: 10px;
        }

        label {
            flex-basis: 180px; /* Increased width for labels to accommodate longer text */
            text-align: right; /* Align label text to the right */
            margin-right: 15px; /* Space between label and input/select */
            color: #444;
            font-weight: bold;
        }

        input[type="text"],
        select { /* Apply styles to both input text and select elements */
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
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292%22%20height%3D%22292%22%3E%3Cpath%20fill%3D%22%23000000%22%20d%3D%22M287%2C188.76l-14.3-14.3c-2.8-2.8-6.2-4.1-9.7-4.1s-6.9%2C1.4-9.7%2C4.1l-117.3%2C117.3c-2.8%2C2.8-6.2%2C4.1-9.7%2C4.1s-6.9-1.4-9.7-4.1l-117.3-117.3c-2.8-2.8-6.2-4.1-9.7-4.1s-6.9%2C1.4-9.7%2C4.1L5%2C188.76c-5.4%2C5.4-5.4%2C14.2%2C0%2C19.6s14.2%2C5.4%2C19.6%2C0l107.5-107.5l107.5%2C107.5c5.4%2C5.4%2C14.2%2C5.4%2C19.6%2C0S292.4%2C194.16%2C287%2C188.76z%22%2F%3E%3C%2Fsvg%3E'); /* Custom dropdown arrow */
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

        p {
            color: #555;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        button {
            background-color: #28a745; /* Green button for registration */
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
            background-color: #218838;
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
  <h3>ユーザ登録ページ</h3>
  <hr>

  <form action="user_register_act.php" method="POST">
      <div>
        <label for="mailaddress">メールアドレス:</label>
        <input type="text" name="mailaddress" id="mailaddress">
      </div>
      <div>
        <label for="password">パスワード:</label>
        <input type="text" name="password" id="password">
      </div>

      <p>エンタメを楽しむ際の、現在の理想の頻度を教えてください。</p>

      <div>
        <label for="interval_printbook">本</label>
        <select id="interval_printbook" name="interval_printbook">
          <option value="null">設定なし</option>
          <option value="61">2か月</option>
          <option value="30">1か月</option>
          <option value="14">2週間</option>
          <option value="7">1週間</option>
        </select>
      </div>

      <div>
        <label for="interval_manga">マンガ</label>
        <select id="interval_manga" name="interval_manga">
          <option value="null">設定なし</option>
          <option value="61">2か月</option>
          <option value="30">1か月</option>
          <option value="14">2週間</option>
          <option value="7">1週間</option>
        </select>
      </div>

      <div>
        <label for="interval_movie">映画</label>
        <select id="interval_movie" name="interval_movie">
          <option value="null">設定なし</option>
          <option value="61">2か月</option>
          <option value="30">1か月</option>
          <option value="14">2週間</option>
          <option value="7">1週間</option>
        </select>
      </div>
      
      <div>
        <label for="interval_music">音楽</label>
        <select id="interval_music" name="interval_music">
          <option value="null">設定なし</option>
          <option value="61">2か月</option>
          <option value="30">1か月</option>
          <option value="14">2週間</option>
          <option value="7">1週間</option>
        </select>
      </div>

      <div>
        <label for="interval_podcast">ポッドキャスト</label>
        <select id="interval_podcast" name="interval_podcast">
          <option value="null">設定なし</option>
          <option value="61">2か月</option>
          <option value="30">1か月</option>
          <option value="14">2週間</option>
          <option value="7">1週間</option>
        </select>
      </div>

      <div>
        <label for="limit_search">１日のおすすめエンタメの検索回数</label>
        <select id="limit_search" name="limit_search">
          <option value="null">設定なし</option>
          <option value="3">３回</option>
          <option value="5">５回</option>
          <option value="10">１０回</option>
        </select>
      </div>      

      <div>
        <button>ユーザ登録します！</button>
      </div>
      <a href="login.php">登録済みならログイン画面へ</a>
  </form>

</body>

</html>