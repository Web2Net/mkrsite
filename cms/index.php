<?php
  session_start();
//var_dump($GLOBALS); 
  include ('../kernel/cfg.php');
  include ('../kernel/mysql.class.php');
  include('../kernel/auth.class.php');
  

      $auth = new Auth();
      $auth->userLogged();
      if (isset($_POST['login'])) 
      {
          if(get_magic_quotes_gpc()) 
          { 
              $_POST['user']=stripslashes($_POST['user']);
              $_POST['pass']=stripslashes($_POST['pass']);
          }
          $user = mysql_real_escape_string($_POST['user']);
          $pass = mysql_real_escape_string($_POST['pass']);
          if($auth->login($user,$pass)) 
          {
              header('Refresh: 0');
              //die('Good!');
          }
          else 
          {
              header('Refresh: 0;');
              //die('Fuck!');
          }
         
      }
      if(isset($_GET['logout'])) 
      {
          $auth->logout();
      }
      if(USER_LOGGED) 
      {
          if(!$auth->check_user(USER_ID)) 
          {
               $auth->logout();   
          }
          include_once ("logic.php"); 
      }
      else 
      { 
          include "tpl/auth_form.tpl.htm";
      }

  
  
?>
