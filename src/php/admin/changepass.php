<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード変更ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

$userData = getUser($_SESSION['user_id']);
debug('取得したユーザ情報:'.print_r($userData,true));

if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST送信:'.print_r($userData,true));

    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $re_new_pass = $_POST['re_new_pass'];

    validRequired($old_pass, 'old_pass');
    validRequired($new_pass, 'new_pass');
    validRequired($re_new_pass, 're_new_pass');

    if(empty($err_msg)){
        debug('未入力チェックOK。');

        validMatch($new_pass, $re_new_pass, 'new_pass');
        validHarf($new_pass, 'new_pass');
        validMinLength($new_pass, 'new_pass');
        validMaxLength($new_pass, 'new_pass');

        if(!password_verify($old_pass, $userData['pass'])){
            $err_msg['old_pass'] = ERR_MSG_PASS_BEFORE;
        }

        if(empty($err_msg)){
            debug('バリデーションOK。');

            try {
                $dbh = dbConnect();
                $sql = 'UPDATE users SET pass = :pass WHERE id = :id';
                $data = array(':id' => $_SESSION['user_id'], ':pass' => password_hash($new_pass, PASSWORD_DEFAULT));
                $stmt = queryPost($dbh, $sql, $data);

                if($stmt){
                    $username = 'usuitkc'; // 変更者の名前
                    $from = 'qwertyu@qwertyu.com';
                    $to = 'usuitkc.630@gmail.com'; // 変更者のアドレス
                    $subject = 'パスワード変更通知|blog';
                    $comment = <<<EOT
{$username} さん
パスワードが変更されました。

---------------------
カスタマーセンター
Email : {$from}
---------------------
EOT;
                    sendMail($from, $to, $subject, $comment);
                    header('Location:mypage.php');
                }
            } catch(Exception $e) {
                error_log('エラー発生:'.$e->getMessage());
                $err_msg['common'] = ERR_MSG;
            }
        }
    }
}
?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">パスワード変更</h2>

        <form method="post" class="c-form">
            <label for="old_pass" class="c-form__label">
                現在のパスワード
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['old_pass'])) echo $err_msg['old_pass'] ; ?></p>
                <input type="password" name="old_pass" id="old_pass" class="c-form__input" value="<?php if(!empty($old_pass)) echo $old_pass ; ?>">
            </label>

            <label for="new_pass" class="c-form__label">
                新しいパスワード
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['new_pass'])) echo $err_msg['new_pass'] ; ?></p>
                <input type="password" name="new_pass" id="new_pass" class="c-form__input">
            </label>

            <label for="re_new_pass" class="c-form__label">
                新しいパスワード
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['re_new_pass'])) echo $err_msg['re_new_pass'] ; ?></p>
                <input type="password" name="re_new_pass" id="re_new_pass" class="c-form__input">
            </label>

            <a href="/admin/passRemind.php" class="c-form__link">パスワードをお忘れの方</a>

            <div class="c-form__btnArea">
                <input type="submit" value="変更" class="c-form__submit c-btn c-btn--blue">

                <a href="/admin/mypage.php" class="c-form__btn c-btn">戻る</a>
            </div>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>