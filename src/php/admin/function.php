<?php
// デバッグ

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

/* 処理系 */
// エラーメッセージ
define('ERR_MSG_EMPTY', '空の項目があります');
define('ERR_MSG_PASS', 'パスワードが一致しません');
define('ERR_MSG_HARF_FORMAT', '半角英数字のみご利用いただけます');
define('ERR_MSG_MINLEN', '6文字以上で入力してください');
define('ERR_MSG_MAXLEN', '256文字以内で入力してください');
define('ERR_MSG_EMAIL', 'メールの形式が違います');
define('ERR_MSG_ACCOUNT', '入力情報に誤りがあります');

define('ERR_MSG','エラーが発生しました。しばらく経ってからやり直してください。');

$err_msg = array();
// バリデーション
function validRequired($value, $key){
    if(empty($value)){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_EMPTY;
    }
}
function validEmail($value, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $value)){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_EMAIL;
    }
}
function validMatch($value1, $value2, $key){
    if($value1 !== $value2){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_PASS;
    }
}
function validMinLength($value, $key, $min = 6){
    if(mb_strlen($value) < $min){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_MINLEN;
    }
}
function validMaxLength($value, $key, $max = 256){
    if(mb_strlen($value) > $max){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_MAXLEN;
    }
}
function validHarf($value, $key){
    if(!preg_match("/^[a-zA-Z0-9]+$/", $value)){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_HARF_FORMAT;
    }
}

function dbConnect(){
    $dsn     = "mysql:dbname=blog;host=localhost;charset=utf8";
    $dbname  = "root";
    $dbpass  = "root";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    );
    $dbh = new PDO($dsn, $dbname, $dbpass, $options);
    return $dbh;
}
function queryPost($dbh, $sql, $data){
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}