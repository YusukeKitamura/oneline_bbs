<?php

  //ステップ１　DB接続
  $dsn      = 'mysql:dbname=oneline_bbs;host=localhost';
  //接続するためのユーザー情報
  $user     = 'root';
  $password = '';

  try {
    //DB接続オブジェクトを作成
    $dbh      = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->query('SET NAMES utf8');

    if(isset($_POST) && !empty($_POST)){
      //SQL文作成(INSERT文)
      $nickname = htmlspecialchars($_POST['nickname']);
      $comment  = htmlspecialchars($_POST['comment']);
      $created  = new DateTime();
      $created->setTimeZone(new DateTimeZone('+08:00'));

      $sql  = 'INSERT INTO `posts` (`nickname`,`comment`,`created`) 
               VALUES ("'.$nickname.'","'.$comment.'","'.$created->format('Y-m-d H:i:s').'")';

      //INSERT文実行
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
    }

    //SQL文作成(SELECT文)
    $sql  = 'SELECT * FROM `posts` WHERE 1 ORDER BY `id` DESC';
    //SELECT文実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    echo '<table border=1>';

    while (1) {
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);  //1レコード取り出し
      // var_dump($rec);
      if ($rec==false) {
        break;
      }

      $posts[]=$rec;
    }

    //データベースから切断
    $dbh = null;

  } catch (Exception $e) {
    echo 'ただいま障害により大変ご迷惑を被っております。';
    echo $e;
  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">

</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">

              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
        <form action="bbs.php" method="post">
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control"
                       id="validate-text" placeholder="nickname" required>

              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
            
          </div>

          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
      
          <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
        </form>
      </div>

      <div class="col-md-8 content-margin-top">

        <div class="timeline-centered">


        <article class="timeline-entry">

            <div class="timeline-entry-inner">

                <div class="timeline-icon bg-success">
                    <i class="entypo-feather"></i>
                    <i class="fa fa-cogs"></i>
                </div>

            </div>

        </article>

        <article class="timeline-entry begin">

            <div class="timeline-entry-inner">

                <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                    <i class="entypo-flight"></i> +
                </div>

            </div>

        </article>

      </div>

    </div>
  </div>
<!--     <form action="bbs.php" method="post">
      <input type="text" name="nickname" placeholder="nickname" required>
      <textarea type="text" name="comment" placeholder="comment" required></textarea>
      <button type="submit" >つぶやく</button>
    </form> -->


<?php
foreach ($posts as $post) { ?>
    <h2><a href="#"><?php echo $post['nickname'];?></a> <span><?php echo $post['created'];?></span></h2>
    <p><?php echo $post['comment'];?></p>
<?php
}
?>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>

</body>
</html>