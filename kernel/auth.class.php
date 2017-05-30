<?php
  class Auth 
  {
      function login($username,$password) //Вход.
      {
          $password = md5($password);
          $query = "SELECT * FROM `".USERS_TABLE."` WHERE `username`='{$username}' AND `password`='{$password}'";
          $result = mysql_query($query) or die(mysql_error());
          $USER = mysql_fetch_array($result,1); 
          if(!empty($USER)) 
          { 
              $_SESSION = array_merge($_SESSION,$USER);
              $query = "UPDATE `".USERS_TABLE."` SET `sid`='".SID."' WHERE `id`='".$USER['id']."'";
              mysql_query($query) or die(mysql_error());
              return true;
          }
          else 
          {
              return false;
          }
      }
      function logout() // Выход
      {
          session_unregister('id'); 
          die(header('Location: '.$_SERVER['PHP_SELF']));
      }
      function check_user($uid) //Проверка залогинности пользователя.
      {   
          $query = "SELECT `sid` FROM `".USERS_TABLE."` WHERE `id`='{$uid}'";
          $result = mysql_query($query) or die(mysql_error());
          $sid = mysql_result($result,0);
          return $sid==SID ? true : false;
      }
      function userLogged()  //Если пользователь авторизирован...
      {
         if(isset($_SESSION['id'])) 
         { 
             define('USER_LOGGED',true);
             define('USER_ID', $_SESSION['id']); 
             define('USER_NAME', $_SESSION['username']);
             define('USER_PASS', $_SESSION['password']);
             define('USER_PRIVILEGE', $_SESSION['privilege']);
         }
         else 
         {
             define('USER_LOGGED',false);
         } 
      }    
  }    
      
?>