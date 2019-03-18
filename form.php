<?php

include './config.php';

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function pwd8() {
    $chars1 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars2 = 'abcdefghijklmnopqrstuvwxyz';
    $hash = $chars1[mt_rand(0,25)];
    for($i = 0;$i < 3;$i++) $hash .= $chars2[mt_rand(0,25)];
    $hash .= mt_rand(1000,9999);
    return $hash;
}

$name = $user = $email = $domain = $nameErr = $userErr = $emailErr = $domainErr = "";

if($_SERVER["REQUEST_METHOD"] != "POST"){ ?>
<!DOCTYPE HTML>
<!--
     ____ _  __ __  __ __ __ ____
    /  _// |/ // / / // //_// __ \
   _/ / /    // /_/ // ,<  / /_/ /
  /___//_/|_/ \____//_/|_| \____/

-->
<html lang="zh-CN" data-form="<?php echo $LOCALURL.'/form.php'; ?>">
    <head>
        <meta charset="UTF-8">
        <link href="static/css/form.css" rel="stylesheet"> 
    </head>
    <body>
        <div id="container">
        <?php show_form2('','','','',$a1_domain,'','',''); ?>
        </div>
        <script src="static/js/jquery.js"></script>
        <script src="static/js/clipboard.js"></script>
        <script src="static/js/form.js"></script>
    </body>
</html><?php
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($_POST["name"])){
            $nameErr = "显示名称是必需的";
        }else{
            $name = test_input($_POST["name"]);
        }
        if(empty($_POST["user"])){
            $userErr = "用户名是必需的 ";
        }else{
            $user = test_input($_POST["user"]);
            if(!preg_match("/^[a-zA-Z0-9._-]{3,16}$/",$user)) $userErr = "用户名只能由3位以上数字,字母,下划线和点构成 "; 
        }
        if(empty($_POST["email"])){
            $emailErr = "邮箱是必需的";
        }else{
            $email = test_input($_POST["email"]);
            if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",$email)) $emailErr = "邮箱格式不正确"; 
        }
        if(empty($_POST["domain"])){
            $domainErr = "域名是必需的";
        }else{
            $domain = test_input($_POST["domain"]);    
            if(!in_array($domain,$a1_domain)) $domainErr = "非法域名";
        }

    if(($nameErr || $emailErr || $userErr || $domainErr) == 0 && ($name && $email && $user && $domain) == 1) $do_create = 1;

    if($do_create){
        $user .= '@'.$domain;
        $tempwd = pwd8();
        $result = Shell_Exec ('powershell.exe -executionpolicy bypass -NoProfile -File ".\\create\\a1.ps1" -d "'.$name.'" -u "'.$user.'" -p "'.$tempwd.'"');
            if(strstr($result,'Fin')){ ?>
                <div class="finish">操作成功完成</div>
                <textarea id="result" name="result" readonly="true"><?php echo "用户名：".$user."\n临时密码：".$tempwd; ?></textarea>
                <button class="button copy" type="button" name="copy" data-clipboard-target="#result">复制</button><?php
                $userinfo = $user.','.$name.','.$email;
                $file = fopen("logs.text","a+");
                $str = fwrite($file,$userinfo."\n");
                fclose($file);
            }elseif(strstr($result,'Auth')){
                echo '<div class="finish2">操作失败</div><span>身份验证失败，请联系站点管理员。</span>';
            }elseif(strstr($result,'Unknown')){
                echo '<div class="finish2">操作失败</div><span>无法识别订阅，请联系站点管理员。</span>';
            }else{
                echo '<div class="finish2">操作失败</div><span>账号创建失败，请更换用户名再试。</span>';
            }
    }else show_form2($name,$nameErr,$user,$userErr,$a1_domain,$domainErr,$email,$emailErr);
}

function show_form2($name,$nameErr,$user,$userErr,$domain_list,$domainErr,$email,$emailErr){ ?>
        <div id="loading" style="display:none"><p>提交中，请稍候...</p></div>
        <form id="form2">
            <div class="name">
                显示名称：<input autocomplete="off" type="text" name="name" value="<?php echo $name;?>">
                <span class="error">*</span>
            </div>
            <div class="error error2"><?php echo $nameErr;?></div>
            <div class="user">
                用户名：<input autocomplete="off" type="text" name="user" value="<?php echo $user;?>">@
                <select name="domain"><?php
                    foreach ($domain_list as $domain) echo '<option title="'.$domain.'" value="'.$domain.'">'.$domain.'</option>'; ?>
                </select>
                <span class="error">*</span>
            </div>
            <div class="error error2"><?php echo $userErr; echo $domainErr;?></div>
            <div class="email">
                电子邮箱：<input type="text" name="email" value="<?php echo $email;?>">
                <span class="error">*</span>
            </div>
            <div class="error error2"><?php echo $emailErr;?></div>
            <button class="button sub2" type="button" name="submit">提交</button>
        </form><?php
}