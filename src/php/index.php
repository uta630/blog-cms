<?php
require('admin/function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　TOPページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

/* テキスト検索 */
$search = (!empty($_GET['search'])) ? $_GET['search'] : '' ;

/* ページャー */
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1 ;
if(!is_numeric($currentPageNum)){
    error_log('エラー発生:指定ページに不正なアクセスがありました。');
    header('Location:index.php');
}
$listSpan = 6;
$currentMinNum = (($currentPageNum - 1) * $listSpan);
/* 記事 */
$categoryType = (!empty($_GET['cat'])) ? $_GET['cat'] : '' ;
$dbPostData = getPublishPostList($currentMinNum, $listSpan, $categoryType, $search);
/* カテゴリ */
$dbCategory = getCategory();
/* デバッグ */
if(empty($dbPostData['data']) && $dbPostData['noindex'] == false){
    error_log('エラー発生:データがありませんでした。');
    header('Location:index.php');
}
?>
<?php include('./common/head.php'); ?>

<?php include('./common/header.php'); ?>

<div class="c-main">
    <div class="c-primary">
        <?php if($dbPostData['noindex']){ ?>
        <p class="c-post--alert">
            <?php echo ERR_MSG_SEARCH ; ?>
            <a href="/">TOPへ</a>戻る。
        </p>
        <?php } ?>
        <?php
            foreach($dbPostData['data'] as $key => $val):
        ?>
        <div class="c-post">
            <div class="c-post__heading">
                <p class="c-post__date"><?php echo date('Y/m/d',  strtotime($val['create_date'])); ?> <i class="c-post__category"><?php echo $dbCategory[$val['category']-1]['catname'] ;?></i></p>
                <h2 class="c-post__title"><?php echo sanitize($val['title']); ?></h2>
            </div>

            <div class="c-post__contents">
                <p class="c-post__text">
                    <?php echo mb_substr(sanitize($val['text']), 0, 126); ?>…
                    <a href="/article.php?p_id=<?php echo $val['id']; ?>" class="c-post__more">続きを読む</a>
                </p>
            </div>
        </div>
        <?php
            endforeach;
        ?>

        <?php pagination($currentPageNum, $dbPostData['total_page']); ?>
    </div>

    <?php include('./common/sidebar.php'); ?>
</div>

<?php include('./common/footer.php'); ?>