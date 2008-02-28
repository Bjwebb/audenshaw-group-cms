<?php
    include "config.php";
    include "header.php"
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
      die('Could not connect: ' . mysql_error());
    }
    if (mysql_select_db($db_db, $con)); else die(mysql_error());
    if ($db_prefix == "aa_") {
        upOther("title","Audy Amnesty");
        upOther("image","logo.png");
    }
    else {
        upOther("title","Audenshaw Fair Trade Committee");
        upOther("image","img/aftlogo.png");
    }
?>
