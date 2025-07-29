<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザ登録</title>
</head>

<body>
  <form action="user_register_act.php" method="POST">
    <fieldset>
      <legend>ユーザ登録</legend>
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
        <button>ユーザ登録します！</button>
      </div>
      <a href="login.php">登録済みならログイン画面へ</a>
    </fieldset>
  </form>

</body>

</html>