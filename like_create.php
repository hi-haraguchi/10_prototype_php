<?php

// var_dump($_GET);
// exit;

session_start();
include("functions.php");
check_session_id(); 

if (!isset($_GET['contents_id']) || $_GET['contents_id'] === '') {
    exit('paramError: contents_id が指定されていません。');
}

$contents_id = $_GET['contents_id']; 
$user_id = $_SESSION['user_id'];


$pdo = connect_to_db();

try {
    // 現在の selected10 の値を取得
    $sql_select = 'SELECT selected10 FROM contents_table WHERE contents_id = :contents_id AND user_id = :user_id';
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(':contents_id', $contents_id, PDO::PARAM_INT);
    $stmt_select->bindValue(':user_id', $user_id, PDO::PARAM_INT); // ユーザー自身のコンテンツか確認
    $stmt_select->execute();
    $record = $stmt_select->fetch(PDO::FETCH_ASSOC);

    // 該当するコンテンツが見つからない場合
    if (!$record) {
        exit('Error: 指定されたコンテンツが見つからないか、アクセス権がありません。');
    }

    $current_selected10 = $record['selected10'];

    // 4. selected10 の値を切り替える
    $new_selected10 = '';
    if ($current_selected10 === '・') {
        $new_selected10 = '☆';
    } else {
        $new_selected10 = '・';
    }

    // 5. selected10 の値を更新
    $sql_update = 'UPDATE contents_table SET selected10 = :selected10 WHERE contents_id = :contents_id AND user_id = :user_id';
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindValue(':selected10', $new_selected10, PDO::PARAM_STR);
    $stmt_update->bindValue(':contents_id', $contents_id, PDO::PARAM_INT);
    $stmt_update->bindValue(':user_id', $user_id, PDO::PARAM_INT); // ユーザー自身のコンテンツか確認
    $status = $stmt_update->execute();

    if ($status === false) {
        // SQL実行に失敗した場合の処理
        $error = $stmt_update->errorInfo();
        exit('SQL Error:' . $error[2]);
    } else {
        // 6. 更新成功後、元のページにリダイレクト
        // 必要に応じて、リダイレクト先を調整してください
        header("Location: my_contents_read.php");
        exit();
    }

} catch (PDOException $e) {
    // データベース接続またはクエリ実行時のエラー
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}
?>

