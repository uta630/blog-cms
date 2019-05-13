<div class="c-sidebar">
    <div class="c-sidebar__section c-prof">
        <img src="/images/default.jpg" alt="" class="c-prof__thumb">
        <p class="c-prof__name">名前</p>
        <p class="c-prof__intro">簡単な紹介文</p>
    </div>

    <div class="c-sidebar__section">
        <form method="get" class="c-search">
            <input type="text" placeholder="search..." class="c-search__input">
            <input type="submit" value="検索" class="c-search__submit">
        </form>
    </div>

    <div class="c-sidebar__section">
        <div class="c-list">
            <h4 class="c-list__title">カテゴリ一覧</h4>

            <div class="c-list__contents">
                <?php
                    foreach($dbCategory as $key => $cat):
                        if(!$cat['delete_flg']){
                ?>
                    <a href="/" class="c-list__link"><?php echo $cat['catname']; ?></a>
                <?php
                        }
                    endforeach;
                ?>
            </div>
        </div>
    </div>

    <div class="c-sidebar__section">
        <div class="c-list">
            <h4 class="c-list__title">リンク</h4>

            <div class="c-list__contents">
                <a href="/contact.php" class="c-list__link">お問い合わせ</a>
                <a href="https://twitter.com/____uta_____" class="c-list__link">Twitter</a>
            </div>
        </div>
    </div>
</div>