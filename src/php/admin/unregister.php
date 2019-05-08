<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　退会ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

$userData = getUser($_SESSION['user_id']);
debug('取得したユーザ情報:'.print_r($userData,true));

if(!empty($_POST)){
    debug('POST送信があります。');

    $name = $_POST['name'];
    $pass = $_POST['pass'];

    validRequired($name, 'name');
    validRequired($pass, 'pass');

    if(empty($err_msg)){
        validHarf($pass, 'pass');
        validMinLength($pass, 'pass');
        validMaxLength($pass, 'pass');
        
        if($userData['username'] !== $name){
            $err_msg['name'] = ERR_MSG_NAME_DIFF;
        }
        if(!password_verify($pass, $userData['pass'])){
            $err_msg['pass'] = ERR_MSG_PASS_DIFF;
        }
        
        if(empty($err_msg)){
            try {
                $dbh = dbConnect();
                $sql = 'UPDATE users SET delete_flg = 1 WHERE id = :id';
                $data = array('id' => $_SESSION['user_id']);
                $stmt = queryPost($dbh, $sql, $data);
                if($stmt){
                    session_destroy();
                    debug('セッション変数の中身'.print_r($_SESSION, true));
                    debug('トップページへ遷移します。');
                    header('Location:signup.php');
                } else {
                    debug('クエリ失敗');
                    $err_msg['common'] = ERR_MSG;
                }
            } catch(Exception $e) {
                error_log('エラー発生:'.$e->getMessage());
                $err_msg['common'] = ERR_MSG;
            }
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">退会</h2>

        <form method="post" class="c-form">
            <label for="name" class="c-form__label">
                名前
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['name'])) echo $err_msg['name'] ; ?></p>
                <input type="text" name="name" id="name" class="c-form__input" value="<?php if(!empty($name)) echo $name ; ?>">
            </label>

            <label for="password" class="c-form__label">
                パスワード
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass'] ; ?></p>
                <input type="password" name="pass" id="password" class="c-form__input" value="<?php if(!empty($pass)) echo $pass ; ?>">
            </label>

            <p class="c-form__text c-form__text--alert">※アカウントを削除するとコンテンツは使用できなくなります。</p>

            <div class="c-form__btnArea">
                <input type="submit" value="退会" class="c-form__submit c-btn c-btn--red">

                <a href="/admin/mypage.php" class="c-form__btn c-btn">戻る</a>
            </div>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>