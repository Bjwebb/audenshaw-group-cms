<?php
    include "config.php";
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
      die('Could not connect: ' . mysql_error());
    }
    if ($db_prefix == "aa_") {
        $name = "Audy Amnesty";
        $image = "logo.png";
    }
    else {
        $name = "Audenshaw Fair Trade Committee";
        $image = "img/aftlogo.png";
    }
    if (mysql_select_db($db_db, $con)); else die(mysql_error());
    mysql_query("DELETE FROM `".$db_prefix."other` WHERE type='title'", $con) or die(mysql_error());
    mysql_query("DELETE FROM `".$db_prefix."other` WHERE type='image'", $con) or die(mysql_error());
    mysql_query("INSERT INTO `".$db_prefix."other` (type,text) VALUES ('title','$name')", $con) or die(mysql_error());
    mysql_query("INSERT INTO `".$db_prefix."other` (type,text) VALUES ('image','$image')", $con) or die(mysql_error());
?>
