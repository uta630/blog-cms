<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin">
        <h2 class="c-admin__title">プロフィール編集</h2>

        <div class="c-myself">
            <form method="post" class="c-form">
                <div class="c-myself__image">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <input type="file" name="myicon" class="c-form__thumb c-myself__thumb-input">
                    <img src="/images/default.jpg" alt="" class="c-myself__thumb">
                </div>

                <div class="c-myself__contents">
                    <label for="name" class="c-form__label">
                        名前
                        <input type="text" name="name" class="c-form__input">
                    </label>
                    <label for="name" class="c-form__label">
                            メールアドレス
                        <input type="email" name="email" class="c-form__input">
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