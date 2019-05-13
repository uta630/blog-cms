<?php
require('admin/function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　TOPページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1 ;
if(!is_numeric($currentPageNum)){
    error_log('エラー発生:指定ページに不正なアクセスがありました。');
    header('Location:index.php');
}
$listSpan = 10;
$currentMinNum = (($currentPageNum - 1) * $listSpan);
$dbPostData = getPostList($currentMinNum, $listSpan);
$dbCategory = getCategory();
if(empty($dbPostData['data'])){
    error_log('エラー発生:データがありませんでした。');
    header('Location:index.php');
}
?>
<?php include('./common/head.php'); ?>

<div class="c-hero">
    <h1 class="c-hero__title">SITE TITLE</h1>
</div>

<div class="c-main">
    <div class="c-primary">
        <?php
            foreach($dbPostData['data'] as $key => $val):
                if($val['status'] === 'publish'){
        ?>
        <div class="c-post">
            <div class="c-post__heading">
                <p class="c-post__date"><?php echo date('Y/m/d',  strtotime($val['create_date'])); ?> <i class="c-post__category"><?php echo $dbCategory[$val['category']-1]['catname'] ;?></i></p>
                <h2 class="c-post__title"><?php echo sanitize($val['title']); ?></h2>
            </div>

            <div class="c-post__contents">
                <p class="c-post__text">
                    <?php echo mb_substr(sanitize($val['text']), 0, 86); ?>…
                    <a href="/article.php?p_id=<?php echo $val['id']; ?>" class="c-post__more">続きを読む</a>
                </p>
            </div>
        </div>
        <?php
            }   
            endforeach;
        ?>
    </div>

    <?php include('./common/sidebar.php'); ?>
</div>

<?php include('./common/footer.php'); ?>