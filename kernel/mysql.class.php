<?php

$db = mysql_connect (DB_HOST, DB_USER, DB_PASS) or die("Подключение к базе данных не состоялось!"); 
mysql_select_db(DB_NAME,$db);

//mysql_query("SET NAMES latin1");
mysql_query("SET NAMES utf8");
//mysql_query("SET NAMES cp1251");

class mysql
{
         
	var $table;
    var $prefix="";

	function put_error ($msg)
	{
		print "<p><b>$msg</b>";
		exit();
	}

	function sql_error ($msg)
	{
		print "<p><b>$msg</b>";
		print "<p><b>Ошибка базы данных:</b>".mysql_error();
		exit();
	}

    function selectSQL ($select, $from, $where, $order="", $limit="",$groupby="")
	{
        $from = $this->prefix.$from;
		if (!is_array ($where)) $this->put_error ("В метод selectSQL(".$from.") передан НЕ массив");
		if ($select == "") $select = "*";
		$q = "SELECT $select FROM $from WHERE ";
		foreach ($where as $field=>$val)
		{
			$q .= "$field = '$val' AND ";
		}
		$q .= " 1=1 ";
		if ($groupby != "") $q .= "GROUP BY $groupby ";
		if ($order != "") $q .= "ORDER BY $order ";		
		if ($limit != "") $q .= "LIMIT $limit";
		
//print $q." - mysql::SelectSQL<br />"; 
		if (!mysql_query ($q)) $this->sql_error ("Error ".$q." in selectSQL(".$from.")!");
		else
		$raw = mysql_query ($q);
		if (mysql_num_rows($raw) > 0)
		{
			while ($result = mysql_fetch_assoc ($raw))
			{
				$itog[] = $result;
			}
			return $itog;
		}
		else return 0;
	}

    function origSelectSQL ($select, $from, $where, $order="", $limit="", $group="")
    {
        $from = $this->prefix.$from;
        if (!isset ($where)) $this->put_error ("В метод origSelectSQL(".$from.") не передан WHERE");
        if ($select == "") $select = "*";
        $q = "SELECT $select FROM $from WHERE ";
        $q .= $where;
        $q .= " AND 1=1 ";
        if ($group != "") $q .= "GROUP BY $group ";    
        if ($order != "") $q .= "ORDER BY $order ";
        if ($limit != "") $q .= "LIMIT $limit";
        $q .= ";";
//print $q." - mysql::origSelectSQL<br />";
        if (!mysql_query ($q)) $this->sql_error ("Error ".$q." in origSelectSQL(".$from.")!");
        else 
        $raw = mysql_query ($q);
        if (mysql_num_rows($raw) > 0)
        {
            while ($result = mysql_fetch_assoc ($raw))
            {
               $itog[] = $result;
            }
            return $itog;
        }
        else return 0;
    }

	
    function innerSelectSQL($select, $from, $from2, $where, $order="", $limit="", $group="")
    {
        $from = $this->prefix.$from;
        if (!isset ($where)) $this->put_error ("В метод innerSelectSQL(".$from.") не передан WHERE");
        if ($select == "") $select = "*";
        $q = "SELECT $select FROM $from INNER JOIN $from2 ON";
        $q .= $where;
        $q .= " AND 1=1 ";
        if ($group != "") $q .= "GROUP BY $group ";    
        if ($order != "") $q .= "ORDER BY $order ";
        if ($limit != "") $q .= "LIMIT $limit";
        $q .= ";";
//print $q." - mysql::origSelectSQL<br />";
        if (!mysql_query ($q)) $this->sql_error ("Error ".$q." in origSelectSQL(".$from.")!");
        else
        $raw = mysql_query ($q);
        if (mysql_num_rows($raw) > 0)
        {
            while ($result =  ($raw))
            {
                $itog[] = $result;
            }
            return $itog;
        }
        else return 0;
    }


	function insertSQL ($arr_value, $table)
	{ 
		//$table = $this->prefix.$table;
		if (!is_array ($arr_value)) $this->put_error ("В метод insertSQL(".$table.") передан НЕ массив");
		
		$field = ""; $var_field = "";
		foreach ($arr_value as $key=>$val)
		{
			$field .= "$key, ";
			$var_field .= "'$val', ";
		}

		$field = substr_replace($field, "", -2, 1);
		$var_field = substr_replace($var_field, "", -2, 1);
		$q = "INSERT INTO $table ($field) VALUES ($var_field)";
//print $q;
		if (!mysql_query ($q)) $this->sql_error ("Error ".$q." in insertSQL(".$table.")!");
		else
		{
			$id = mysql_insert_id();
			return $id;
		}

	}


	function updateSQL ($from, $arr_val, $where)
	{   
        $from = $this->prefix.$from;
		$q = "UPDATE $from SET ";
		if (!is_array($arr_val)) $this->put_error("В параметрах значений на обновление updateSQL(".$from.") передан НЕ массив!");

		$q_var = "";
		foreach ($arr_val as $field=>$var)
		{
			$q_var .= "$field = '$var', ";
		}
		$q_var = substr_replace($q_var, "", -2, 1);

		if (!is_array($where)) $this->put_error("В параметрах условий на обновление updateSQL(".$from.") передан НЕ массив!");
		$q_where = "";
		foreach ($where as $field=>$val)
		{
			$q_where .= "$field = '$val' AND ";
		}
		$q_where .= " 1=1";

		$q .= "$q_var WHERE $q_where";
//print $q." - mysql::updateSQL<br />";
		if (!mysql_query ($q))
		{
			$this->sql_error ("Error ".$q." in updateSQL(".$from.")!");
			return;
		}
		else return 1;

	}


	function deleteSQL ($from, $where)
	{
		$from = $this->prefix.$from;
		$q = "DELETE FROM $from WHERE ";

		if (!is_array($where)) $this->put_error("В параметрах значений на удаление deleteSQL(".$from.") передан НЕ массив!");
		foreach ($where as $field=>$val)
		{
			$q .= "$field = '$val' AND ";
		}
		$q .= " 1=1";
//print $q;
		if (!mysql_query ($q)) $this->sql_error ("Error ".$q." in deleteSQL(".$from.")!");
		else
		return true;

	}
	
	function origSQL($query)
	{
		if (!mysql_query ($query)) $this->sql_error ("Error ".$query." in origSQL(".$from.")!");
		else
		$raw = mysql_query ($query);
//print $query." - mysql::origSQL<br />";
		if (is_array($raw))
        {
		    if (mysql_num_rows($raw) > 0)
		    {
			    while ($result = mysql_fetch_assoc ($raw))
			    {
				    $itog[] = $result;
			    }
			    return $itog;
		    }
        }
		else return 0;
	}
    
    function simplySQL($query)
    {
        if (!mysql_query ($query)) $this->sql_error ("Error ".$query." in simplySQL(".$from.")!");
        else
        $raw = mysql_query ($query);
//print $query." - mysql::simplySQL<br />";
        if ($raw)
        {
            while ($result = mysql_fetch_row($raw))
             {
                 $itog[] = $result[0];
             }
             return($itog);
        }
        else return 0;
    }
    
    function checkUniqRow($table, $row, $value)
    {
        $query = "SELECT count(*) FROM {$table} WHERE `{$row}`='{$value}'";
//echo $query;
        $res = mysql_query($query);
        if(mysql_result($res,0) > 0)
        {   
            return false;
        }
        else
        {   
            return true;
        }
    }
    
}

 ?>
