<?php
require('function.php');

error_reporting(E_ALL);
ini_set('display_errors', 'on');

if(!empty($_POST)){
    $name = $_POST['name'];
    $pass = $_POST['pass'];

    validRequired($name, 'empty');
    validRequired($pass, 'empty');
        
    if(empty($err_msg)){
        validHarf($pass, 'pass');
        validMinLength($pass, 'pass');
        validMaxLength($pass, 'pass');

        if(empty($err_msg)){
            $dbh = dbConnect();
            $sql = 'SELECT * FROM users WHERE username = :username AND pass = :pass';
            $data = array(':username' => $name, ':pass' => $pass);
            $stmt = queryPost($dbh, $sql, $data);

            $result = 0;
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result){
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