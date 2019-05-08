<?php
/* ログイン認証 */
if(!empty($_SESSION['login_date'])){
    debug('ログイン済みのユーザです。');

    if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
        debug('ログイン有効期限オーバーです。');
        session_destroy();
        header('Location:signin.php');
    } else {
        debug('ログイン有効期限以内です。');
        $_SESSION['login_date'] = time();

        if(basename($_SERVER['PHP_SELF'] === 'signin.php')){
            debug('マイページに遷移します。');
            header('Location:mypage.php');
        }
    }
} else {
    debug('未ログインのユーザです。');
    if(basename($_SERVER['PHP_SELF']) !== 'signin.php'){
        header('Location:signin.php');
    }
}