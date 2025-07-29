<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
</head>

<body>
  <form action="login_act.php" method="POST">
    <fieldset>
      <legend>ログイン</legend>
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
    </fieldset>
  </form>

</body>

</html>