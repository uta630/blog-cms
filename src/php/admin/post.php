<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　商品出品登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

// 投稿データの取得
$userID = $_SESSION['user_id'];
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '' ;
$dbFormData = (!empty($p_id)) ? getPost($userID, $p_id) : '' ;
$edit_flg = (empty($dbFormData)) ? false : true ;
$dbCategoryData = getCategory();
debug('商品ID:'.$p_id);
debug('フォーム用DBデータ:'.print_r($dbFormData, true));
debug('カテゴリデータ:'.print_r($dbCategoryData, true));

// パラメータ改ざんチェック
if(!empty($p_id) && empty($dbFormData)){
    debug('GETパラメータの商品IDが違います。');
    debug('マイページへ遷移します。');
    header('Location:mypage.php');
}

// postチェック
if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報:'.print_r($_POST, true));
    debug('FILE情報:'.print_r($_FILES, true));

    $title    = $_POST['title'];
    $text     = $_POST['text'];
    $category = $_POST['category'];

    // 画像アップロード + パスを格納
    $pic1 = ( !empty($_FILES['pic1']['name']) ) ? uploadImg($_FILES['pic1'], 'pic1') : '' ;
    $pic2 = ( !empty($_FILES['pic2']['name']) ) ? uploadImg($_FILES['pic2'], 'pic2') : '' ;
    $pic3 = ( !empty($_FILES['pic3']['name']) ) ? uploadImg($_FILES['pic3'], 'pic3') : '' ;
    // 画像登録していないがすでに登録されている場合にDBのパスを入れておく
    $pic1 = ( empty($pic1) && !empty($dbFormData['pic1']) ) ? $dbFormData['pic1'] : $pic1 ;
    $pic2 = ( empty($pic2) && !empty($dbFormData['pic2']) ) ? $dbFormData['pic2'] : $pic2 ;
    $pic3 = ( empty($pic3) && !empty($dbFormData['pic3']) ) ? $dbFormData['pic3'] : $pic3 ;

    if(empty($dbFormData)){
        // 投稿バリデーション
        validRequired($title, 'empty');
        validRequired($text, 'empty');
        validSelect($category, 'category');
    } else {
        // 編集バリデーション
        if($dbFormData['title'] !== $title){
            validRequired($title, 'title');
            validMaxLength($title, 'title');
            validMinLength($title, 'title');
        }
        if($dbFormData['text'] !== $text){
            validRequired($text, 'text');
        }
        if($dbFormData['category'] !== $category){
            validSelect($category, 'category');
        }
    }

    // DB処理
    if(empty($err_msg)){
        debug('バリデーションOK。');

        try {
            $dbh = dbConnect();
            if($edit_flg){
                debug('DBを更新します。');
                $sql = 'UPDATE post SET ';
                $data = array();
            } else {
                debug('DBに新規登録します。');
                $sql = 'INSERT INTO
                    post (user_id, title, text, category, pic1, pic2, pic3, create_date)
                    values (:user_id, :title, :text, :category, :pic1, :pic2, :pic3, :create_date)';
                $data = array(
                    ':user_id' => $userID,
                    ':title' => $title,
                    ':text' => $text,
                    ':category' => $category,
                    ':pic1' => $pic1,
                    ':pic2' => $pic2,
                    ':pic3' => $pic3,
                    ':create_date' => date('Y-m-d H:i:s')
                );
            }
            debug('SQL:'.$sql);
            debug('データ:'.print_r($data, true));

            $stmt = queryPost($dbh, $sql, $data);

            if($stmt){
                debug('マイページへ遷移します。');
                header('Location:postList.php');
            }
        } catch(Exception $e) {
            error_log('エラー発生:'.$e->getMessage());
            $err_msg['common'] = ERR_MSG;
        }
    }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php include('./common/head.php'); ?>

<div class="c-admin-wrap">
    <div class="c-admin c-admin--wide">
        <h2 class="c-admin__title">新規投稿</h2>

        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['empty'])) echo $err_msg['empty'] ; ?></p>
        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['common'])) echo $err_msg['common'] ; ?></p>

        <form method="post" class="c-form">
            <label for="title" class="c-form__label">
                タイトル
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['title'])) echo $err_msg['title'] ; ?></p>
                <input type="text" name="title" id="title" class="c-form__input" value="<?php if(!empty($title)) echo $title ; ?>">
            </label>
            
            <label for="text" class="c-form__label">
                本文
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['text'])) echo $err_msg['text'] ; ?></p>
                <textarea name="text" id="text" class="c-form__input c-form__textarea c-input"><?php if(!empty($text)) echo $text ; ?></textarea>
            </label>
            
            <div class="c-form__label">
                カテゴリ
                <div class="c-form__category">
                    <select name="category">
                        <option value="0">選択してください</option>
                        <option value="1">ブログ</option>
                        <option value="2">お知らせ</option>
                        <option value="3">html</option>
                        <option value="4">php</option>
                    </select>
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
                        <input type="file" name="pic2" class="c-form__thumb js-post-image">
                        <img src="" class="c-form__thumb--prev">
                    </label>
                    <label class="c-form__thumb-wrap">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                        <input type="file" name="pic3" class="c-form__thumb js-post-image">
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