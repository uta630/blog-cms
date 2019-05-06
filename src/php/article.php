<?php include('./common/head.php'); ?>

<div class="c-hero">
    <h1 class="c-hero__title">SITE TITLE</h1>
</div>

<div class="c-main">
    <div class="c-primary">
        <div class="c-post">
            <div class="c-post__heading">
                <p class="c-post__date">2019/12/31 <i class="c-post__category">カテゴリ１</i></p>
                <h2 class="c-post__title">ブログのタイトルがここに入ります。</h2>
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

                <p class="c-post__text">
                    ブログ本文がここに入ります。
                </p>
            </div>
        </div>
    </div>

    <?php include('./common/sidebar.php'); ?>
</div>

<?php include('./common/footer.php'); ?>