<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">登録</h2>

        <form method="post" class="c-form">
            <label for="name" class="c-form__label">
                名前
                <input type="text" name="name" id="name" class="c-form__input">
            </label>

            <label for="email" class="c-form__label">
                メールアドレス
                <input type="email" name="email" id="email" class="c-form__input">
            </label>

            <label for="password" class="c-form__label">
                パスワード
                <input type="password" name="pass" id="password" class="c-form__input">
            </label>

            <label for="password" class="c-form__label">
                パスワード再入力
                <input type="password" name="re_pass" id="password" class="c-form__input">
            </label>

            <input type="submit" value="登録" class="c-form__submit c-btn c-btn--blue">

            <a href="/admin/signin.php" class="c-form__link">ログイン</a>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>