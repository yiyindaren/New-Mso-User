<?php include './config.php'; ?>
<!DOCTYPE html>
<!--
     ____ _  __ __  __ __ __ ____
    /  _// |/ // / / // //_// __ \
   _/ / /    // /_/ // ,<  / /_/ /
  /___//_/|_/ \____//_/|_| \____/

-->
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <meta name="format-detection" content="telphone=no,email=no">
    <meta name="keywords" content="MSO, Office, 365, 犬, 狗子, 小白-白">
    <meta name="description" content="MSO 账号兑换平台 - Moedog 保留所有权利">
    <title>MSO 365 Application - Powered by Moedog</title>
    <link href="static/css/main.css" rel="stylesheet">
    <link rel="shortcut icon" href="static/favicon.ico">
  </head>
  <body>
    <div id="home-slider">
        <div class="container">
            <div class="main-slider">
                <div class="slide-text">
                    <h1>在开始之前...</h1>
                    <p>请注意：如果您不知道此站点是干什么的，请点击浏览器的关闭按钮。</p>
                    <p>如果您准备好开始，请点击下面的“开始”按钮。</p>
                    <a href="javascript:;" class="btn start" data-form="<?php echo $LOCALURL.'/form.php'; ?>">开始</a>
                    <a href="javascript:;" class="btn about">关于</a>
                </div>
                <img src="static/images/hill.png" class="slider-hill">
                <img src="static/images/house.png" class="slider-house">
                <img src="static/images/sun.png" class="slider-sun">
                <img src="static/images/birds1.png" class="slider-birds1">
                <img src="static/images/birds2.png" class="slider-birds2">
            </div>
        </div>
    </div>
    <script type="text/javascript" src="static/js/jquery.js"></script>
    <script type="text/javascript" src="static/js/main.min.js"></script>
  </body>
</html>