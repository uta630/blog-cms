<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　パスワード再発行認証キー入力ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

// 認証キーの確認
if(empty($_SESSION['auth_key'])){
    header('Location:passRemind.php');
}

if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));

    $auth_key = $_POST['token'];
    validRequired($auth_key, 'token');
    
    if(empty($err_msg)){
        debug('未入力チェックOK。');
        
        validLength($auth_key, 'token');
        validHarf($auth_key, 'token');
        
        if(empty($err_msg)){
            debug('バリデーションOK。');

            if($auth_key !== $_SESSION['auth_key']){
                $err_msg['common'] = ERR_MSG_AUTH_KEY;
            } else if(time() > $_SESSION['auth_key_limit']){
                $err_msg['common'] = ERR_MSG_AUTH_LIMIT;
            }
            
            if(empty($err_msg)){
                debug('認証OK。');

                // 変更するパスワードを生成
                $changePassCode = makeRandKey();
                debug('再発行パスワード:'.$changePassCode);

                try {
                    // pass書き換え(update)
                    $dbh = dbConnect();
                    $sql = 'UPDATE users SET pass = :pass WHERE email = :email AND delete_flg = 0';
                    $data = array(':email' => $_SESSION['auth_email'], ':pass' => password_hash($changePassCode, PASSWORD_DEFAULT));
                    $stmt = queryPost($dbh, $sql, $data);

                    // 内容をメール送信
                    if($stmt){
                        debug('クエリ成功。');

                        $from = 'usuitkc.630@gmail.com';
                        $to = $_SESSION['auth_email'];
                        $subject = '【パスワードについて】再発行完了しました';
                        $comment = <<<EOF
本メールアドレス宛にパスワードの再発行を致しました。
下記のURLにて再発行パスワードをご入力頂き、ログインください。

ログインページ：
http://localhost:8888/admin/changepass.php
再発行パスワード：{$changePassCode}
※ログイン後、パスワードのご変更をお願い致します

////////////////////////////////////////
uta-CMS カスタマーセンター
URL  http://192.168.100.108:10005/
E-mail uta@uta.com
////////////////////////////////////////
EOF;
                        sendMail($from, $to, $subject, $comment);
                        session_unset();
                        debug('セッションの中身:'.print_r($_SESSION, true));
                        header('Location:signin.php');
                    } else {
                        debug('クエリに失敗しました。');
                        $err_msg['common'] = MSG07;
                      }
                } catch(Exception $e) {
                    error_log('エラー発生:'.$e->getMessage());
                    $err_msg['common'] = ERR_MSG;
                }
            }
        }
    }
}
?>
<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">パスワード再発行認証</h2>

        <p class="c-form__msg c-form__msg--alert">
            <?php
                if(!empty($err_msg['common'])){
                    echo $err_msg['common'];
                }
            ?>
        </p>

        <form method="post" class="c-form">
            <label for="token" class="c-form__label">
                認証キー
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['token'])) echo $err_msg['token'] ; ?></p>
                <input type="text" name="token" id="token" class="c-form__input" value="<?php echo getFormData('token'); ?>">
            </label>

            <p class="c-form__text">ご指定のメールアドレスお送りした【パスワード再発行認証】メール内にある「認証キー」をご入力ください。</p>

            <div class="c-form__btnArea">
                <input type="submit" value="再発行" class="c-form__submit c-btn c-btn--blue">

                <a href="/admin/passRemind.php" class="c-form__btn c-btn">戻る</a>
            </div>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>