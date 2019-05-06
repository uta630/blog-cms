<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin c-admin--wide">
        <h2 class="c-admin__title">新規投稿</h2>

        <form method="post" class="c-form">
            <label for="name" class="c-form__label">
                タイトル
                <input type="text" name="name" id="name" class="c-form__input">
            </label>
            
            <label for="comment" class="c-form__label">
                本文
                <textarea name="comment" id="comment" class="c-form__input c-form__textarea c-input"></textarea>
            </label>
            
            <div for="comment" class="c-form__label">
                カテゴリ
                <div class="c-form__category">
                    <label for="cat1"><input type="radio" name="cat" id="cat1">ブログ</label>
                    <label for="cat2"><input type="radio" name="cat" id="cat2">お知らせ</label>
                    <label for="cat3"><input type="radio" name="cat" id="cat3">html</label>
                    <label for="cat4"><input type="radio" name="cat" id="cat4">php</label>
                </div>
            </div>

            <div class="c-form__label">
                画像選択
                <div class="c-form__images">
                    <label class="c-form__thumb-wrap">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                        <input type="file" name="pic1" class="c-form__thumb js-post-image">
                        <img src="" class="c-form__thumb--prev">
                    </label>
                    <label class="c-form__thumb-wrap">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                        <input type="file" name="pic1" class="c-form__thumb js-post-image">
                        <img src="" class="c-form__thumb--prev">
                    </label>
                    <label class="c-form__thumb-wrap">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                        <input type="file" name="pic1" class="c-form__thumb js-post-image">
                        <img src="" class="c-form__thumb--prev">
                    </label>
                </div>
            </div>

            <div class="c-form__btnArea">
                <input type="submit" value="投稿する" class="c-form__submit c-btn c-btn--blue">

                <a href="/admin/mypage.php" class="c-form__btn c-btn">戻る</a>
            </div>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>