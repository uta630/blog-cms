<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　記事登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
require('auth.php');

// 投稿データの取得
$userID = $_SESSION['user_id'];
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '' ;
$dbFormData = (!empty($p_id)) ? getPost($userID, $p_id) : '' ;
$dbCategoryData = getCategory();
$edit_flg = (empty($dbFormData)) ? false : true ;
debug('記事ID:'.$p_id);
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
    $status   = $_POST['status'];

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
                $sql = 'UPDATE post
                    SET user_id = :user_id, title = :title, text = :text, category = :category, status = :status, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3
                    WHERE user_id = :user_id AND id = :p_id';
                $data = array(
                    ':p_id' => $p_id,
                    ':user_id' => $userID,
                    ':title' => $title,
                    ':text' => $text,
                    ':category' => $category,
                    ':status' => $status,
                    ':pic1' => $pic1,
                    ':pic2' => $pic2,
                    ':pic3' => $pic3
                );
            } else {
                debug('DBに新規登録します。');
                $sql = 'INSERT INTO
                    post (user_id, title, text, category, status, pic1, pic2, pic3, create_date)
                    values (:user_id, :title, :text, :category, :status, :pic1, :pic2, :pic3, :create_date)';
                $data = array(
                    ':user_id' => $userID,
                    ':title' => $title,
                    ':text' => $text,
                    ':category' => $category,
                    ':status' => $status,
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
                $_SESSION['msg_success'] = '記事を登録しました。';
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
        <h2 class="c-admin__title"><?php echo (!$edit_flg) ? '新規投稿' : '記事編集'; ?></h2>

        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['empty'])) echo $err_msg['empty'] ; ?></p>
        <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['common'])) echo $err_msg['common'] ; ?></p>

        <form method="post" enctype="multipart/form-data" class="c-form">
            <div class="c-form__label c-form__release">
                <label>
                    <input type="radio" name="status" value="private" <?php if('private' === getFormData('status')){ echo 'checked'; } ?> class="c-form__state">
                    非公開
                </label>
                <label>
                    <input type="radio" name="status" value="publish" <?php if('publish' === getFormData('status')){ echo 'checked'; } ?> class="c-form__state">
                    公開
                </label>
            </div>

            <label for="title" class="c-form__label">
                タイトル
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['title'])) echo $err_msg['title'] ; ?></p>
                <input type="text" name="title" id="title" class="c-form__input" value="<?php echo getFormData('title'); ?>">
            </label>
            
            <label for="text" class="c-form__label">
                本文
                <p class="c-form__msg c-form__msg--alert"><?php if(!empty($err_msg['text'])) echo $err_msg['text'] ; ?></p>
                <textarea name="text" id="text" class="c-form__input c-form__textarea c-input"><?php echo getFormData('text'); ?></textarea>
            </label>
            
            <div class="c-form__label">
                カテゴリ
                <div class="c-form__category">
                    <select name="category" class="c-form__select">
                        <option value="0">選択してください</option>
                        <?php foreach($dbCategoryData as $key => $val): ?>
                        <option value="<?php echo sanitize($val['id']); ?>" <?php if(sanitize($val['id']) === $dbFormData['category']){ echo 'selected'; } ?>><?php echo sanitize($val['catname']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="c-form__label">
                画像選択
                <div class="c-form__images">
                    <div class="c-form__image">
                        <label class="c-form__thumb-wrap">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                            <input type="file" name="pic1" class="c-form__thumb js-post-image">
                            <img src="<?php echo getFormData('pic1'); ?>" class="c-form__thumb--prev">
                        </label>
                        <div class="c-form__thumb--remove js-thumb-remove">削除</div>
                    </div>
                    <div class="c-form__image">
                        <label class="c-form__thumb-wrap">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                            <input type="file" name="pic2" class="c-form__thumb js-post-image">
                            <img src="<?php echo getFormData('pic2'); ?>" class="c-form__thumb--prev">
                        </label>
                        <div class="c-form__thumb--remove js-thumb-remove">削除</div>
                    </div>
                    <div class="c-form__image">
                        <label class="c-form__thumb-wrap">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                            <input type="file" name="pic3" class="c-form__thumb js-post-image">
                            <img src="<?php echo getFormData('pic3'); ?>" class="c-form__thumb--prev">
                        </label>
                        <div class="c-form__thumb--remove js-thumb-remove">削除</div>
                    </div>
                </div>
            </div>

            <div class="c-form__btnArea">
                <input type="submit" value="投稿する" class="c-form__submit c-btn c-btn--blue">

                <a href="/admin/postList.php" class="c-form__btn c-btn">戻る</a>
            </div>
        </form>
    </div>
</div>

<?php include('./common/footer.php'); ?>