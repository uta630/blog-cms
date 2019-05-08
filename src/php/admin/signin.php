<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログインページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');


if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    validRequired($email, 'empty');
    validRequired($pass, 'empty');
        
    validHarf($pass, 'pass');
    validMinLength($pass, 'pass');
    validMaxLength($pass, 'pass');

    if(empty($err_msg)){
        try {
            $dbh = dbConnect();
            $sql = 'SELECT pass,id  FROM users WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $email);
            $stmt = queryPost($dbh, $sql, $data);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            debug('クエリ結果の中身：'.print_r($result,true));

            if(!empty($result) && password_verify($pass, array_shift($result))){
                debug('パスワードがマッチしました。');

                $sesLimit = 60*60;
                $_SESSION['login_date'] = time();
                $_SESSION['login_limit']  = $sesLimit;
                $_SESSION['user_id'] = $result['id'];

                debug('セッション変数の中身：'.print_r($_SESSION,true));
                debug('マイページへ遷移します。');
                header("Location:mypage.php");
            } else {
                debug(' パスワードがマッチしませんでした。');

                $err_msg['account'] = ERR_MSG_ACCOUNT;
            }
        } catch(Exception $e) {
            error_log('エラー発生:'.$e->getMessage());
            $err_msg['common'] = ERR_MSG;
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
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
                メールアドレス
                <input type="text" name="email" class="c-form__input" value="<?php if(!empty($_POST['email'])) echo $_POST['email'] ;?>">
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