<?php
/* ログ出力 */
error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

/* セッション */
session_start();
session_regenerate_id();

/* デバッグ */
$debug_flg = true;
function debug($value){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ:'.$value);
    }
}

/* ログイン認証 */
function isLogin(){
    if(!empty($_SESSION['login_date'])){
        debug('ログイン済みユーザーです。');
        if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
            debug('ログイン有効期限オーバーです。');
            session_destroy();
            return false;
        }else{
            debug('ログイン有効期限以内です。');
            return true;
        }
        }else{
            debug('未ログインユーザーです。');
            return false;
        }
    }

function debugLogStart(){
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
    debug('セッションID:'.session_id());
    debug('セッション変数の中身:'.print_r($_SESSION, true));
    debug('現在日時タイムスタンプ:'.time());
    if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
        debug('ログイン期限日時タイムスタンプ:'.( $_SESSION['login_date'] + $_SESSION['login_limit'] ));
    }
}

/* 処理系 */
// エラーメッセージ
define('ERR_MSG_EMPTY', '空の項目があります');
define('ERR_MSG_PASS', 'パスワードが一致しません');
define('ERR_MSG_HARF_FORMAT', '半角英数字のみご利用いただけます');
define('ERR_MSG_MINLEN', '6文字以上で入力してください');
define('ERR_MSG_MAXLEN', '256文字以内で入力してください');
define('ERR_MSG_EMAIL', 'メールの形式が違います');
define('ERR_MSG_ACCOUNT', '入力情報に誤りがあります');

define('ERR_MSG_EMAIL_DUP', '入力されたEmailは既に登録されています');
define('ERR_MSG_NAME_DUP', '入力された名前は既に登録されています');
define('ERR_MSG_NAME_DIFF', '名前が違います。');
define('ERR_MSG_PASS_DIFF', 'パスワードが違います');
define('ERR_MSG_SELECT', '正しくありません');

define('ERR_MSG','エラーが発生しました。しばらく経ってからやり直してください。');

$err_msg = array();
// バリデーション
function validRequired($value, $key){
    if(empty($value)){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_EMPTY;
    }
}
function validEmail($value, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $value)){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_EMAIL;
    }
}
function validMatch($value1, $value2, $key){
    if($value1 !== $value2){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_PASS;
    }
}
function validMinLength($value, $key, $min = 6){
    if(mb_strlen($value) < $min){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_MINLEN;
    }
}
function validMaxLength($value, $key, $max = 256){
    if(mb_strlen($value) > $max){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_MAXLEN;
    }
}
function validHarf($value, $key){
    if(!preg_match("/^[a-zA-Z0-9]+$/", $value)){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_HARF_FORMAT;
    }
}
function validEmailDup($email){
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!empty(array_shift($result))){
            $err_msg['email'] = ERR_MSG_EMAIL_DUP;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}
function validNameDup($name){
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE username = :username AND delete_flg = 0';
        $data = array(':username' => $name);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!empty(array_shift($result))){
            $err_msg['name'] = ERR_MSG_NAME_DUP;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}
function validSelect($value, $key){
    if(!preg_match("/^[0-9]+$/", $value)){
        global $err_msg;
        $err_msg[$key] = ERR_MSG_SELECT;
    }
}

function dbConnect(){
    $dsn     = "mysql:dbname=blog;host=localhost;charset=utf8";
    $dbname  = "root";
    $dbpass  = "root";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    );
    $dbh = new PDO($dsn, $dbname, $dbpass, $options);
    return $dbh;
}
function queryPost($dbh, $sql, $data){
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}

/* メール送信 */
function sendMail($from, $to, $subject, $comment){
    if(!empty($to) && !empty($subject) && !empty($comment)){
        mb_language('Japanese');
        mb_internal_encoding('UTF-8');

        $result = mb_send_mail($to, $subject, $comment, 'From: '.$from);

        if($result){
            debug('メールを送信しました。');
        } else {
            debug('【エラー発生】メールの送信に失敗しました。');
        }
    }
}

/* ユーザ情報取得 */
function getUser($userID){
    debug('ユーザ情報を取得します。');
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users WHERE id = :userID AND delete_flg = 0';
        $data = array(':userID' => $userID);
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
    }
}

/* 投稿全体の情報取得 */
function getPostList($currentMinNum = 1, $span = 20){
    debug('投稿全体の情報を取得します。');
    
    try {
        // DBの記事idを取得する
        $dbh = dbConnect();
        $sql = 'SELECT id FROM post';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        
        $result['total'] = $stmt->rowCount();
        $result['total_page'] = ceil($result['total'] / $span);
        if(!$stmt){
            return false;
        }

        // 取得したidから表示したい分だけ拾って出力する
        $sql = 'SELECT * FROM post';
        $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
        $data = array();
        debug('SQL：'.$sql);
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            $result['data'] = $stmt->fetchAll();
            return $result;
        } else {
            return false;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
    }
}

/* 投稿全体の公開記事だけ情報取得 */
function getPublishPostList($currentMinNum = 1, $span = 20, $category = '', $search = ''){
    debug('投稿全体の情報を取得します。');
    
    try {
        // DBの記事idを取得する
        $dbh = dbConnect();
        $sql = 'SELECT id FROM post WHERE status = :status AND type = :type';
        $data = array(':status' => 'publish', ':type' => 'post');
        if(!empty($category)){
            $sql = $sql.' AND category = :category';
            $data = array_merge($data, array(':category' => $category));
        } else if(!empty($search)){
            $sql = $sql.' AND title LIKE "%'.$search.'%"';
        }
        debug('SQL:'.$sql);

        $stmt = queryPost($dbh, $sql, $data);
        $result['total'] = $stmt->rowCount();
        $result['total_page'] = ceil($result['total'] / $span);

        if(!$stmt){
            return false;
        }

        // 取得したidから表示したい分だけ拾って出力する
        $sql = 'SELECT * FROM post WHERE status = :status AND type = :type';
        $data = array(':status' => 'publish', ':type' => 'post');
        if(!empty($category)){
            $sql = $sql.' AND category = :category';
            $data = array_merge($data, array(':category' => $category));
        } else if(!empty($search)){
            $sql = $sql.' AND title LIKE "%'.$search.'%"';
        }
        $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
        $stmt = queryPost($dbh, $sql, $data);
        debug('SQL:'.$sql);
        
        if($stmt){
            $result['data'] = $stmt->fetchAll();
            return $result;
        } else {
            return false;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
    }
}

/* 投稿情報取得 */
function getPost($userID, $postID){
    debug('投稿情報を取得します。');
    debug('ユーザID:'.$userID);
    debug('投稿ID:'.$postID);
    
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM post WHERE user_id = :userID AND id = :postID AND delete_flg = 0';
        $data = array(':userID' => $userID, 'postID' => $postID);
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
    }
}
/* 投稿情報表示 */
function showPost($postID){
    debug('投稿情報を取得します。');
    debug('投稿ID:'.$postID);
    
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM post WHERE id = :postID AND delete_flg = 0';
        $data = array('postID' => $postID);
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
    }
}

/* カテゴリ取得 */
function getCategory(){
    debug('カテゴリー情報を取得します。');

    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM category';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch(Exception $e) {
        error_log('エラー発生:'.$e->getMessage());
    }
}

/* 画像処理 */
function uploadImg($file, $key){
    debug('画像アップロード処理開始');
    debug('FILE情報:'.print_r($file, true));

    if(isset($file['error']) && is_int($file['error'])){
        try {
            // バリデーション
            switch($file['error']){
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('ファイルが選択されていません。');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('ファイルサイズが大きすぎます。');
                default:
                    throw new RuntimeException('その他のエラーが発生しました。');
            }

            // MIMEタイプチェック
            $type = @exif_imagetype($file['tmp_name']);
            if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){
                throw new RuntimeException('画像形式が未対応です。');
            }

            // ファイル名の重複回避
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
            if(!move_uploaded_file($file['tmp_name'], $path)){
                throw new RuntimeException('ファイル保存時にエラーが発生しました。');
            }

            // 保存したファイルパスのパーミッション(権限)変更
            chmod($path, 0644);

            debug('ファイルは正常にアップロードされました。');
            debug('ファイルパス:'.$path);

            return $path;
        } catch(RuntimeException $e) {
            debug($e->getMessage());
            global $err_msg;
            $err_msg[$key] = $e->getMessage();
        }
    }
}

// フォームの入力保持
function getFormData($str, $flg = false){
    if($flg){
        $method = $_GET;
    } else {
        $method = $_POST;
    }
    global $dbFormData;
    // ユーザーデータがある場合
    if(!empty($dbFormData)){
        //フォームのエラーがある場合
        if(!empty($err_msg[$str])){
            //POSTにデータがある場合
            if(isset($method[$str])){
                return sanitize($method[$str]);
            } else {
                //ない場合（基本ありえない）はDBの情報を表示
                return sanitize($dbFormData[$str]);
            }
        } else {
            //POSTにデータがあり、DBの情報と違う場合
            if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
                return sanitize($method[$str]);
            } else {
                return sanitize($dbFormData[$str]);
            }
        }
    } else {
        if(isset($method[$str])){
            return sanitize($method[$str]);
        }
    }
}
/* サニタイズ */
function sanitize($value){
    return htmlspecialchars($value, ENT_QUOTES);
}

/* ページャー */
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
    // 判定
    if( $currentPageNum == $totalPageNum && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum - 4;
        $maxPageNum = $currentPageNum;
    }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum - 3;
        $maxPageNum = $currentPageNum + 1;
    }elseif( $currentPageNum == 2 && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum - 1;
        $maxPageNum = $currentPageNum + 3;
    }elseif( $currentPageNum == 1 && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
    }elseif($totalPageNum < $pageColNum){
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
    }else{
        $minPageNum = $currentPageNum - 2;
        $maxPageNum = $currentPageNum + 2;
    }
    
    // 出力
    echo '<div class="c-pager">';
        if($currentPageNum != 1){
            echo '<a href="?p=1'.$link.'" class="c-pager__link">&lt;</a>';
        }
        for($i = $minPageNum; $i <= $maxPageNum; $i++){
            if($currentPageNum == $i){
                echo '<span href="?p='.$i.$link.'" class="c-pager__link is-active">'.$i.'</span>';
            } else {
                echo '<a href="?p='.$i.$link.'" class="c-pager__link">'.$i.'</a>';
            }
        }
        if($currentPageNum != $maxPageNum && $maxPageNum > 1){
            echo '<a href="?p='.$maxPageNum.$link.'" class="c-pager__link">&gt;</a>';
        }
    echo '</div>';
}