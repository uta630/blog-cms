<?php
require('admin/function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　記事詳細ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// 投稿データの取得
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '' ;
$dbFormData = (!empty($p_id)) ? showPost($p_id) : '' ;
$dbCategory = getCategory();
debug('記事ID:'.$p_id);
debug('フォーム用DBデータ:'.print_r($dbFormData, true));
debug('カテゴリデータ:'.print_r($dbCategory, true));
if(empty($p_id )){
    error_log('エラー発生:指定ページに不正なアクセスがありました。');
    header('Location:index.php');
}
?>

<?php include('./common/head.php'); ?>

<div class="c-hero">
    <h1 class="c-hero__title">SITE TITLE</h1>
</div>

<div class="c-main">
    <div class="c-primary">
        <div class="c-post">
            <div class="c-post__heading">
                <p class="c-post__date"><?php echo $dbFormData['create_date']; ?> <i class="c-post__category"><?php echo $dbCategory[$dbFormData['category']-1]['catname']; ?></i></p>
                <h2 class="c-post__title"><?php echo $dbFormData['title']; ?></h2>
            </div>

            <div class="c-post__contents">
                <div class="c-post__images">
                    <div class="js-bxslider">
                        <img src="/images/default0.png" alt="" class="c-post__thumb">
                        <img src="/images/default1.png" alt="" class="c-post__thumb">
                        <img src="/images/default2.png" alt="" class="c-post__thumb">
                    </div>
                    <i class="c-post__icon c-post__icon--prev"></i>
                    <i class="c-post__icon c-post__icon--next"></i>
                </div>

                <p class="c-post__text"><?php echo $dbFormData['text']; ?></p>
            </div>
        </div>
    </div>

    <?php include('./common/sidebar.php'); ?>
</div>

<?php include('./common/footer.php'); ?>