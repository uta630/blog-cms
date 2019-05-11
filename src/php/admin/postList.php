<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　投稿一覧ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
require('auth.php');

$getPostData = getPostList();
?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin c-admin--wide">
        <h2 class="c-admin__title">投稿一覧</h2>

        <div class="c-pager">
            < ... 12345 ... >
        </div>

        <div class="c-catalog">
            <?php foreach($getPostData as $key => $val): ?>
            <a href="/admin/post.php" class="c-catalog__link"><?php echo sanitize($val['create_date']); ?> <?php echo sanitize($val['title']); ?></a>
            <?php endforeach; ?>
        </div>

        <div class="c-catalog__btn">
            <a href="/admin/mypage.php" class="c-btn">マイページへ戻る</a>
        </div>
    </div>
</div>

<?php include('./common/footer.php'); ?>