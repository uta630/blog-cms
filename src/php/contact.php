<?php include('./common/head.php'); ?>

<?php include('./common/header.php'); ?>

<div class="c-main">
    <div class="c-primary c-contact">
        <h2 class="c-contact__title">お問い合わせ</h2>

        <form method="post" class="c-contact">
            <label for="name" class="c-contact__label">
                名前
                <input type="text" name="name" id="name" class="c-contact__input c-input">
            </label>
            
            <label for="email" class="c-contact__label">
                メールアドレス
                <input type="text" name="email" id="email" class="c-contact__input c-input">
            </label>

            <label for="subject" class="c-contact__label">
                件名
                <input type="text" name="subject" id="subject" class="c-contact__input c-input">
            </label>

            <label for="comment" class="c-contact__label">
                本文
                <textarea name="comment" id="comment" class="c-contact__input c-contact__textarea c-input"></textarea>
            </label>

            <input type="submit" value="送信" class="c-contact__submit c-btn c-btn--blue">
        </form>
    </div>

    <?php include('./common/sidebar.php'); ?>
</div>

<?php include('./common/footer.php'); ?>