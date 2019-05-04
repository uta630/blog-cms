<?php
require('function.php');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

if(!empty($_POST)){
    // 定数 : エラー文言
    define('ERR_MSG_EMPTY', '空の項目があります。');
    define('ERR_MSG_ACCOUNT', '入力情報に誤りがあります。');

    // 変数 : エラー文言用
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    // 変数 : エラー文言用
    $err_msg = array();

    // 空チェック
    if(empty($name) || empty($pass)){
        $err_msg['empty'] = ERR_MSG_EMPTY;
    }

    if(empty($err_msg)){
        // 名前とパスワードが一致しているか
        $err_msg['account'] = ERR_MSG_ACCOUNT;

        if(!empty($err_msg)){
            // DB接続
        }
    }
}

?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">ログイン</h2>

        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['empty'])) echo $err_msg['empty'] ;?></p>

        <form method="post" class="c-form">
            <label for="name" class="c-form__label">
                名前
                <input type="text" name="name" class="c-form__input" value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ;?>">
            </label>

            <label for="password" class="c-form__label">
                パスワード
                <input type="password" name="pass" class="c-form__input" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass'] ;?>">
            </label>

            <input type="submit" value="ログイン" class="c-form__submit c-btn c-btn--blue">

            <a href="/admin/signup.php" class="c-form__link">登録する</a>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>