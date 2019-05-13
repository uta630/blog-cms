<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

/* Ajax処理 */
if(!empty($_POST['catname']) && isset($_SESSION['user_id']) && isLogin()){
    debug('POST送信があります。');
    $catname = $_POST['catname'];
    debug('カテゴリー名:'.$catname);

    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM category WHERE catname = :catname AND delete_flg = 0';
        $data = array(':catname' => $catname);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        debug('取得したカテゴリー:'.print_r($result));

        if(!empty(array_shift($result))){
            debug('カテゴリーが重複しました。');
        } else {
            debug('カテゴリーを追加します。');
            $sql = 'INSERT INTO category (catname, create_date) VALUES (:catname, :create_date)';
            $data = array(':catname' => $catname, ':create_date' => date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);
            debug('SQL:'.$sql);
            debug('データ:'.print_r($data, true));
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
        $err_msg['common'] = MSG;
    }
}
