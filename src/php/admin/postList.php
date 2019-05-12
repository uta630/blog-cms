<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　投稿一覧ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
require('auth.php');

$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1 ;
if(!is_numeric($currentPageNum)){
    error_log('エラー発生:指定ページに不正なアクセスがありました。');
    header('Location:mypage.php');
}

$listSpan = 10;
$currentMinNum = (($currentPageNum - 1) * $listSpan);
$dbPostData = getPostList($currentMinNum, $listSpan);

if(empty($dbPostData['data'])){
    error_log('エラー発生:データがありませんでした。');
    header('Location:mypage.php');
}

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin c-admin--wide">
        <h2 class="c-admin__title">投稿一覧</h2>

        <?php pagination($currentPageNum, $dbPostData['total_page']); ?>

        <?php if(!empty($_SESSION['msg_success'])){ ?>
            <p><?php echo $_SESSION['msg_success']; ?></p>
        <?php 
                $_SESSION['msg_success'] = '' ;
            }
        ?>

        <div class="c-catalog">
            <?php foreach($dbPostData['data'] as $key => $val): ?>
            <a href="/admin/post.php?p_id=<?php echo sanitize($val['id']); ?>" class="c-catalog__link">
                <?php echo date('Y/m/d',  strtotime($val['create_date'])); ?>
                <?php if($val['status'] === 'publish'){ ?> <i class="c-catalog__link--icon">公開済み</i> <?php } ?>
                <?php echo sanitize($val['title']); ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="c-catalog__btn">
            <a href="/admin/mypage.php" class="c-btn">マイページへ戻る</a>
        </div>
    </div>
</div>

<?php include('./common/footer.php'); ?>