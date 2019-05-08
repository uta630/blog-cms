<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プロフィール編集ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

$userData = getUser($_SESSION['user_id']);

debug('取得したユーザ情報:'.print_r($userData,true));

if(!empty($_POST)){
    $name = $_POST['name'];
    $email = $_POST['email'];

    validRequired($name, 'name');
    validRequired($email, 'email');
    validEmail($email, 'email');

    if(empty($err_msg)){
        validNameDup($name);
        validEmailDup($email);

        if(empty($err_msg)){
            try {
                $dbh = dbConnect();
                $sql = 'UPDATE users SET username = :username, email = :email';
                $data = array(':username' => $name, 'email' => $email);
                $stmt = queryPost($dbh, $sql, $data);

                if($stmt){
                    debug('マイページへ遷移します。');
                    header('Location:mypage.php');
                } else {
                    debug('ダメでした');
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
        <h2 class="c-admin__title">プロフィール編集</h2>

        <p class="c-form__msg c-form__msg--alert">
            <?php
                if(!empty($err_msg['empty'])){
                    echo $err_msg['empty'];
                } elseif(!empty($err_msg['common'])){
                    echo $err_msg['common'];
                }
            ?>
        </p>

        <div class="c-myself">
            <form method="post" class="c-form">
                <div class="c-myself__image">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <input type="file" name="myicon" class="c-form__thumb c-myself__thumb-input js-post-image">
                    <img src="/images/default.jpg" alt="" class="c-myself__thumb">
                </div>

                <div class="c-myself__contents">
                    <label for="name" class="c-form__label">
                        名前
                        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['name'])) echo $err_msg['name'] ; ?></p>
                        <input type="text" name="name" class="c-form__input" value="<?php if(!empty($name)) echo $name ; ?>">
                    </label>
                    <label for="name" class="c-form__label">
                            メールアドレス
                        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['email'])) echo $err_msg['email'] ; ?></p>
                        <input type="email" name="email" class="c-form__input" value="<?php if(!empty($email)) echo $email ; ?>">
                    </label>
                </div>

                <div class="c-form__btnArea">
                    <input type="submit" value="更新" class="c-form__submit c-myself__link c-btn c-btn--green">
                    
                    <a href="/admin/mypage.php" class="c-myself__link c-btn">戻る</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('./common/footer.php'); ?>