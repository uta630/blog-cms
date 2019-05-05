<?php
require('function.php');

if(!empty($_POST)){
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $pass    = $_POST['pass'];
    $re_pass = $_POST['re_pass'];

    // 空チェック
    validRequired($name,    'empty');
    validRequired($email,   'empty');
    validRequired($pass,    'empty');
    validRequired($re_pass, 'empty');

    if(empty($err_msg)){
        validMatch($pass, $re_pass, 'pass');
        validHarf($pass, 'pass');
        validMinLength($pass, 'pass');
        validMaxLength($pass, 'pass');
        validEmail($email, 'email');
        
        if(empty($err_msg)){
            try{
                $dbh = dbConnect();
                $sql = 'INSERT INTO users (username,email,pass,create_date) VALUES(:username,:email,:pass,:create_date)';
                $data = array(
                    ':username' => $name,
                    ':email' => $email,
                    ':pass' => $pass,
                    ':create_date' => date('Y-m-d H:i:s')
                );
                queryPost($dbh, $sql, $data);
                
                header("Location:mypage.php");
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
        <h2 class="c-admin__title">登録</h2>

        <p class="c-form__msg c-form__msg--alert">
            <?php
                if(!empty($err_msg['empty'])){
                    echo $err_msg['empty'];
                } elseif(!empty($err_msg['common'])){
                    echo $err_msg['common'];
                }
            ?>
        </p>

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