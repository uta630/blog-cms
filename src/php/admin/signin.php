<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">ログイン</h2>

        <form method="post" class="c-form">
            <label for="name" class="c-form__label">
                名前
                <input type="text" name="name" id="name" class="c-form__input">
            </label>

            <label for="password" class="c-form__label">
                パスワード
                <input type="password" name="pass" id="password" class="c-form__input">
            </label>

            <input type="submit" value="ログイン" class="c-form__submit c-btn c-btn--blue">

            <a href="/admin/signup.php" class="c-form__link">登録する</a>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>