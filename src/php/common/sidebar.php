<div class="c-sidebar">
    <div class="c-sidebar__section c-prof">
        <img src="/images/default.jpg" alt="" class="c-prof__thumb">
        <p class="c-prof__name">うた＠ウェブカツ!!</p>
        <p class="c-prof__intro">雑魚ーダー/ 名ばかりのフロントエンドエンジニア / 2018.12.11 ~ #ウェブカツ 奮闘中 / 目指すは「会社員 x フリーランス」 / 頑張る</p>
    </div>

    <div class="c-sidebar__section">
        <form method="get" class="c-search" action="/">
            <input type="text" placeholder="search..." name="search" class="c-search__input" value="<?php if(!empty($search)) echo $search ; ?>">
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
                    <a href="/?cat=<?php echo $cat['id']; ?>" class="c-list__link"><?php echo $cat['catname']; ?></a>
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
                <a href="/" class="c-list__link">TOP</a>
                <a href="/contact.php" class="c-list__link">お問い合わせ</a>
                <a href="https://twitter.com/____uta_____" class="c-list__link">Twitter</a>
            </div>
        </div>
    </div>
</div>