<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin c-admin--wide">
        <h2 class="c-admin__title">マイページ</h2>

        <div class="c-myself">
            <div class="c-myself__image">
                <img src="/images/default.jpg" alt="" class="c-myself__thumb">
            </div>
            <div class="c-myself__contents">
                <p class="c-myself__text">名前：xxx xxxxx</p>
                <p class="c-myself__text">メールアドレス：xxxx@xxxxxx.xx.xx</p>
            </div>

            <a href="/admin/myself.php" class="c-myself__link c-btn c-btn--green">編集</a>
        </div>
        
        <div class="c-panel">
            <h3 class="c-panel__title"><i class="c-panel__title--inner">ブログ</i></h3>

            <div class="c-panel__items">
                <a href="/" class="c-panel__link">サイトへ</a>
                <a href="/admin/postList.php" class="c-panel__link">投稿一覧</a>
                <a href="/admin/post.php" class="c-panel__link">新規投稿</a>
            </div>
        </div>

        <div class="c-panel">
            <h3 class="c-panel__title"><i class="c-panel__title--inner">設定</i></h3>

            <div class="c-panel__items">
                <a href="/admin/changepass.php" class="c-panel__link">パスワード</a>
                <a href="/admin/unregister.php" class="c-panel__link">退会</a>
                <a href="/admin/signout.php" class="c-panel__link">ログアウト</a>
            </div>
        </div>
    </div>
</div>

<?php include('./common/footer.php'); ?>