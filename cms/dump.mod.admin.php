<?php
class Dump 
{

    function admDump() 
    {
         $tpl = new Tpl;
         
         $page = Dump::dump_BD();
       
         $tpl->assign('list',$page);
         $c_cont=$tpl->get("dump");
         
         return $c_cont; 
    }

    function dump_BD()
      {
          $today_date = date("Y-m-d");
          $dump_dir = PATH_DUMP."/".$today_date;
          if(!is_dir($dump_dir))
          {
              mkdir($dump_dir, 0755); // директория, куда будем сохранять резервную копию БД 
          }
          
            $tables = "SHOW TABLES";
            $res = mysql_query($tables) or die( "Ошибка при выполнении запроса: ".mysql_error() );

            while( $table = mysql_fetch_row($res) )
            {
                $file = $dump_dir."/".$table[0].".".EXT_DUMP;
                $fp = fopen( $file, "w" );
                if ( $fp )
                {
                    $query = "TRUNCATE TABLE `".$table[0]."`;\n";
                    fwrite ($fp, $query);
                    $rows = 'SELECT * FROM `'.$table[0].'`';
                    $r = mysql_query($rows) or die("Ошибка при выполнении запроса: ".mysql_error());
                    while( $row = mysql_fetch_row($r) )
                    {
                        $query = "";
                        foreach ( $row as $field )
                        {
                            if ( is_null($field) )
                                $field = "NULL";
                            else
                                $field = "'".mysql_escape_string( $field )."'";
                            if ( $query == "" )
                                $query = $field;
                            else
                                $query = $query.', '.$field;
                        }
                        $query = "INSERT INTO `".$table[0]."` VALUES (".$query.");\n";
                        fwrite ($fp, $query);
                    }
                    fclose ($fp);
                    $dump_db[] .= "<a href='".SITE_URL."/archives/{$today_date}/{$table[0]}.".EXT_DUMP."' target='_blank'>".$table[0]."</a>";
                }
                else
                {
                    echo "X3";
                }
            }    
            return $dump_db; 
      }
}
?>