<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行メール送信ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));
    
    $email = $_POST['email'];
    validRequired($email, 'email');

    if(empty($err_msg)){
        debug('未入力チェックOK。');
        validEmail($email, 'email');
        
        if(empty($err_msg)){
            debug('バリデーションOK。');

            try {
                $dbh = dbConnect();
                $sql = 'SELECT count(*) FROM users WHERE id = :id AND delete_flg = 0';
                $data = array(':id' => $_SESSION['user_id']);

                $stmt = queryPost($dbh, $sql, $data);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if($stmt){
                    debug('クエリ成功。DB登録あり。');

                    $auth_key = makeRandKey(); // 認証キー生成

                    $from = 'usuitkc.630@gmail.com';
                    $to = $email;
                    $subject = '【お問い合わせを受け付けました】パスワード再発行の認証';
                    $comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。

パスワード再発行認証キー入力ページ
http://localhost:8888/admin/passRemindRecieve.php

認証キー:{$auth_key}
認証の有効期限は30分です。

////////////////////////////////////////
uta-CMS カスタマーセンター
URL  http://192.168.100.108:10005/
E-mail uta@uta.com
////////////////////////////////////////
EOT;
                    sendMail($from, $to, $subject, $comment);

                    // 認証に必要なセッションへ保存
                    $_SESSION['auth_key']       = $auth_key;
                    $_SESSION['auth_email']     = $email;
                    $_SESSION['auth_key_limit'] = time()+(60*30); // 有効期限
                    debug('セッション変数の中身:'.print_r($_SESSION, true));
                    header('Location:passRemindRecieve.php');
                } else {
                    debug('クエリ失敗に失敗したかDB登録のないEmailが入力されました。');
                    $err_msg['comon'] = ERR_MSG;
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
        <h2 class="c-admin__title">パスワードをお忘れの方</h2>

        <p class="c-form__msg c-form__msg--alert">
            <?php
                if(!empty($err_msg['common'])){
                    echo $err_msg['common'];
                }
            ?>
        </p>

        <form method="post" class="c-form">
            <label for="email" class="c-form__label">
                メールアドレス
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['email'])) echo $err_msg['email'] ; ?></p>
                <input type="email" name="email" id="email" class="c-form__input">
            </label>

            <p class="c-form__text">※入力いただいたメールアドレスへ再登録用のパスワードを発行してお送りします。お手数ですがメールをご確認の上、パスワードを再設定してください。</p>

            <div class="c-form__btnArea">
                <input type="submit" value="送信" class="c-form__submit c-btn c-btn--blue">

                <a href="/admin/changepass.php" class="c-form__btn c-btn">戻る</a>
            </div>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>