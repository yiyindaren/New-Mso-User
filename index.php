<!DOCTYPE HTML>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>MSO 365 Application</title>
    <style>.error {color: #FF0000;}</style>
  </head>
  <body><?php
    $nameErr = $emailErr = $userErr = "";
    $name = $email = $user = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
      if(empty($_POST["name"])){
        $nameErr = "显示名称是必需的";
      }else{
        $name = test_input($_POST["name"]);
      }
      if(empty($_POST["user"])){
        $userErr = "用户名是必需的";
      }else{
        $user = test_input($_POST["user"]);
        if(!preg_match("/^[a-zA-Z0-9._-]{3,16}$/",$user)) $userErr = "用户名只能由数字,字母,下划线和点构成"; 
      }
      if(empty($_POST["email"])){
        $emailErr = "邮箱是必需的";
      }else{
        $email = test_input($_POST["email"]);
        if(!preg_match("/([\w\-]+\@qq\.com)/",$email)) $emailErr = "邮箱格式不正确"; 
      }
    }

    function test_input($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
?>
    <h2>MSO 365 Application</h2>
    <div>注意：此为测试账户，许可证为 A1+E3，用多久看微软心情，请不要存储重要资料。若一个月内账户未活动，将回收。</div><br>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
      显示名称: <input type="text" name="name" value="<?php echo $name;?>">
      <span class="error">* <?php echo $nameErr;?></span><br><br>
      用户名: <input type="text" name="user" value="<?php echo $user;?>">@test.com
      <span class="error">* <?php echo $userErr;?></span><br><br>
      QQ邮箱: <input type="text" name="email" value="<?php echo $email;?>">
      <span class="error">* <?php echo $emailErr;?></span><br><br>
      <input type="submit" name="submit" value="Submit"> 
    </form><?php
    function pwd8() {
      $chars1 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $chars2 = 'abcdefghijklmnopqrstuvwxyz';
      $hash = $chars1[mt_rand(0,25)];
      for($i = 0;$i < 3;$i++){
        $hash .= $chars2[mt_rand(0,25)];
      }
      $hash .= mt_rand(1000,9999);
      return $hash;
    }
    $user .= '@test.com';
    $tempwd = pwd8();
    if($nameErr || $emailErr || $userErr){
      echo 'ERROR<br>';
    }elseif($name && $email && $user){
      $result = Shell_Exec ('powershell.exe -executionpolicy bypass -NoProfile -File ".\run.ps1" -d "'.$name.'" -u "'.$user.'" -p "'.$tempwd.'"');
      echo $result.'<br>';
      if(strstr($result,'Fin')){
        echo '用户名：'.$user.'<br>';
        echo '临时密码：'.$tempwd;
        $userinfo = $user.'|'.$name.'|'.$email;
        $file = fopen("users.text","a+");
        $str = fwrite($file,$userinfo."\n");
        fclose($file);
      }
    }
?>
  </body>
</html>