<?php
require('admin/function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　お問い合わせページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

$dbCategory = getCategory();
debug('カテゴリデータ:'.print_r($dbCategory, true));

if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));
    // inputチェック
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $subject = $_POST['subject'];
    $comment = $_POST['comment'];

    validRequired($name, 'empty');
    validRequired($email, 'empty');
    validRequired($subject, 'empty');
    validRequired($comment, 'empty');

    if(empty($err_msg)){
        debug('未入力チェックOK。');
        validMaxLength($name, 'name');
        validEmail($email, 'email');
        validMaxLength($subject, 'subject');

        if(empty($err_msg)){
            debug('バリデーションOK。');

            try {
                // サポートセンターに問い合わせ内容を通知
                $notice_from = 'usuitkc.630@gmail.com';
                $notice_to = 'usuitkc.630@gmail.com';
                $notice_subject = 'お問い合わせがありました。';
                $notice_comment = <<<EOF
サイトよりお問い合わせを受け付けました。
内容は以下になります。

お名前:{$name}
メールアドレス:{$email}
件名:{$subject}
本文:
{$comment}

////////////////////////////////////////
uta-CMS カスタマーセンター
URL  http://192.168.100.108:10005/
E-mail uta@uta.com
////////////////////////////////////////
EOF;
                sendMail($notice_from, $notice_to, $notice_subject, $notice_comment);

                // 差出人に確認メールを通知
                $check_from = 'usuitkc.630@gmail.com';
                $check_to = $email;
                $check_subject = 'お問い合わせありがとうございます';
                $check_comment = <<<EOF
このたびはお問い合わせをいただき、誠にありがとうございます。
以下でお問い合わせ内容をいただきました。

お名前:{$name}
メールアドレス:{$email}
件名:{$subject}
本文:
{$comment}

担当者よりご連絡させていただきます。
今しばらくお待ちくださいますようお願い申し上げます。

////////////////////////////////////////
uta-CMS カスタマーセンター
URL  http://192.168.100.108:10005/
E-mail uta@uta.com
////////////////////////////////////////
EOF;
                sendMail($check_from, $check_to, $check_subject, $check_comment);
                
                if(empty($err_msg)){
                    $_POST['name'] = '';
                    $_POST['email'] = '';
                    $_POST['subject'] = '';
                    $_POST['comment'] = '';

                    $suc_msg['common'] = 'お問い合わせを受け付けました。';
                    debug('お問い合わせの処理を完了。');
                }
            } catch(Exceprion $e) {
                error_log('エラー発生:'.$e->getMessage());
                $err_msg['common'] = ERR_MSG;
            }
        }
    }
}

?>

<?php include('./common/head.php'); ?>

<?php include('./common/header.php'); ?>

<div class="c-main">
    <div class="c-primary c-contact">
        <h2 class="c-contact__title">お問い合わせ</h2>

        <p class="c-form__msg c-form__msg--alert">
            <?php
                if(!empty($err_msg['empty'])){
                    echo $err_msg['empty'];
                } else if(!empty($err_msg['common'])){
                    echo $err_msg['common'];
                } else if(!empty($suc_msg['common'])){
                    echo $suc_msg['common'];
                }
            ?>
        </p>

        <form method="post" class="c-contact">
            <label for="name" class="c-contact__label">
                名前
                <input type="text" name="name" id="name" class="c-contact__input c-input" value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ; ?>">
            </label>
            
            <label for="email" class="c-contact__label">
                メールアドレス
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['email'])) echo $err_msg['email'] ; ?></p>
                <input type="text" name="email" id="email" class="c-contact__input c-input" value="<?php if(!empty($_POST['email'])) echo $_POST['email'] ; ?>">
            </label>

            <label for="subject" class="c-contact__label">
                件名
                <input type="text" name="subject" id="subject" class="c-contact__input c-input" value="<?php if(!empty($_POST['subject'])) echo $_POST['subject'] ; ?>">
            </label>

            <label for="comment" class="c-contact__label">
                本文
                <textarea name="comment" id="comment" class="c-contact__input c-contact__textarea c-input"><?php if(!empty($_POST['comment'])) echo $_POST['comment'] ; ?></textarea>
            </label>

            <div class="c-form__btnArea">
                <input type="submit" value="送信" class="c-contact__submit c-btn c-btn--blue">

                <a href="/" class="c-form__btn c-btn">戻る</a>
            </div>
        </form>
    </div>

    <?php include('./common/sidebar.php'); ?>
</div>

<?php include('./common/footer.php'); ?>