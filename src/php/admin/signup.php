<?php
require('function.php');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

if(!empty($_POST)){
    // 定数 : エラー文言
    define('ERR_MSG_EMPTY', '空の項目があります。');
    define('ERR_MSG_PASS', 'パスワードが一致しません。');
    define('ERR_MSG_PASS_FORMAT', 'パスワードの形式が間違っています。');
    define('ERR_MSG_PASS_LEN', 'パスワードは6文字以上で入力してください。');
    define('ERR_MSG_EMAIL', 'メールの形式が違います。');
    define('ERR_MSG_ACCOUNT', '入力情報に誤りがあります。');

    // 変数 : エラー文言用
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $pass    = $_POST['pass'];
    $re_pass = $_POST['re_pass'];
    // 変数 : エラー文言用
    $err_msg = array();

    // 空チェック
    if(empty($name) || empty($email) || empty($pass) || empty($re_pass)){
        $err_msg['empty'] = ERR_MSG_EMPTY;
    }

    if(empty($err_msg)){
        // パスワード一致チェック
        if($pass !== $re_pass){
            $err_msg['pass'] = ERR_MSG_PASS;
        } else if(!preg_match("/^[a-zA-Z0-9]+$/", $pass)){
            $err_msg['pass'] = ERR_MSG_PASS_FORMAT;
        } elseif(mb_strlen($pass) < 6) {
            $err_msg['pass'] = ERR_MSG_PASS_LEN;
        }

        // メール形式チェック
        if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
            $err_msg['email'] = ERR_MSG_EMAIL;
        }
        
        if(empty($err_msg)){
            //DBへの接続準備
            $dsn     = "mysql:dbname=blog;host=localhost;charset=utf8";
            $dbname  = "root";
            $dbpass  = "root";
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            );
            $dbh = new PDO($dsn, $dbname, $dbpass, $options);
            $stmt = $dbh->prepare('INSERT INTO users (username,email,pass,create_date) VALUES(:username,:email,:pass,:create_date)');
            $stmt->execute(
                array(
                    ':username' => $name,
                    ':email' => $email,
                    ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                    ':create_date' => date('Y-m-d H:i:s')
                )
            );
            // 情報を登録してページ遷移
            header("Location:mypage.php");
        }
    }
}

?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">登録</h2>

        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['empty'])) echo $err_msg['empty'] ; ?></p>

        <form method="post" class="c-form">
            <label for="name" class="c-form__label">
                名前
                <input type="text" name="name" class="c-form__input" value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ; ?>">
            </label>

            <label for="email" class="c-form__label">
                メールアドレス
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['email'])) echo $err_msg['email'] ; ?></p>
                <input type="email" name="email" class="c-form__input" value="<?php if(!empty($_POST['email'])) echo $_POST['email'] ; ?>">
            </label>

            <label for="password" class="c-form__label">
                パスワード
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass'] ; ?></p>
                <input type="password" name="pass" class="c-form__input">
            </label>

            <label for="password" class="c-form__label">
                パスワード再入力
                <input type="password" name="re_pass" class="c-form__input">
            </label>

            <input type="submit" value="登録" class="c-form__submit c-btn c-btn--blue">

            <a href="/admin/signin.php" class="c-form__link">ログイン</a>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>