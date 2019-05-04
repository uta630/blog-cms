<?php
require('function.php');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

if(!empty($_POST)){
    // 定数 : エラー文言
    define('ERR_MSG_EMPTY', '空の項目があります。');
    define('ERR_MSG_PASS_FORMAT', 'パスワードの形式が間違っています。');
    define('ERR_MSG_PASS_LEN', 'パスワードは6文字以上で入力してください。');
    define('ERR_MSG_ACCOUNT', '名前またはパスワードが間違っています。');

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
        if(mb_strlen($pass) < 6){
            $err_msg['pass'] = ERR_MSG_PASS_LEN;
        } elseif(!preg_match("/^[a-zA-Z0-9]+$/", $pass)) {
            $err_msg['pass'] = ERR_MSG_PASS_FORMAT;
        }

        if(empty($err_msg)){
            //DBへの接続準備
            $dns     = "mysql:dbname=blog;host=localhost;charset=utf8";
            $dbname  = "root";
            $dbpass  = "root";
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            );
            $dbh  = new PDO($dns, $dbname, $dbpass, $options);
            $stmt = $dbh->prepare('SELECT * FROM users WHERE username = :username AND pass = :pass');
            $stmt->execute(array(':username' => $name, ':pass' => $pass));

            $result = 0;
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result){
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['name']  = $name;
                header("Location:mypage.php");
            } else {
                $err_msg['account'] = ERR_MSG_ACCOUNT;
            }
        }
    }
}

?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">ログイン</h2>

        <p class="c-form__msg c-form__msg--alert">
            <?php if(!empty($err_msg['empty'])) echo $err_msg['empty'] ;?>
            <?php if(!empty($err_msg['account'])) echo $err_msg['account'] ;?>
        </p>

        <form method="post" class="c-form">
            <label for="name" class="c-form__label">
                名前
                <input type="text" name="name" class="c-form__input" value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ;?>">
            </label>

            <label for="pass" class="c-form__label">
                パスワード
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass'] ; ?></p>
                <input type="password" name="pass" class="c-form__input" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass'] ;?>">
            </label>

            <input type="submit" value="ログイン" class="c-form__submit c-btn c-btn--blue">

            <a href="/admin/signup.php" class="c-form__link">登録する</a>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>