<?php
    include "config.php";
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
      die('Could not connect: ' . mysql_error());
    }
    if ($db_prefix == "aa_") {
        $name = "Audy Amnesty";
    }
    else {
        $name = "Audenshaw Fair Trade Committee";
    }
    if (mysql_select_db($db_db, $con)); else die(mysql_error());
    mysql_query("INSERT INTO `".$db_prefix."other` (type,text) VALUES ('title','$name')", $con) or die(mysql_error());
?>
