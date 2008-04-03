<?php
    include '../config.php';
    include '../header.php';
    
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
      die('Could not connect: ' . mysql_error());
    }
    if (mysql_select_db($db_db, $con)); else die(mysql_error());


    $id = $_POST['id'];
    $type = $_POST['type'];
//    $sid = $_COOKIE[$db_prefix."choccookie"];
    $page = $_POST['page'];
    
//    if (sha1($sid) == $password) {
    session_start();
    if (auth() && userPos($userID) == 'admin') {
        if ($page != '') {
            switch ($page) {
                case 1:
                    adminAbout();
                    break;
                case 2:
                    adminNews();
                    break;
                case 3:
                    adminProjects();
                    break;
                case 4:
                    adminMembers();
                    break;
                case 5:
                    adminPages();
                    break;
                case 6:
                    adminOther();
                    break;
            }
        }
        else {
            switch ($type) {
                case 'about':
                    $text = $_POST['text'];
                    if (mysql_query("UPDATE ".$db_prefix."other SET text='$text' WHERE type='about'")) echo "Success!!1";
                    break;
                case 'newsform':
                    if ($id == 'add') $row = Array();
                    else {
                        $result = mysql_query("SELECT * FROM ".$db_prefix."news WHERE id=$id",$con);
                        $row = mysql_fetch_array($result);
                    }
                    echo "<form id=\"ajaxform\">";
                    echo "<input type=\"text\" id=\"title\" value=\"".$row['title']."\"><br/>";
                    echo "<textarea type=\"text\" id=\"text\" cols=\"60\" rows=\"10\" >".$row['text']."</textarea><br/>";
                    echo "<button type=\"button\" onClick=\"cancel()\">Cancel</button>";
                    echo "<button type=\"button\" onClick=\"postNews('$id')\">Submit</button>";
                    echo "</form>";
                    break;
                case 'newspost':
                    $text = $_POST['text'];
                    $title = $_POST['title'];
                    if ($id == 'add') {
                        mysql_query("INSERT INTO ".$db_prefix."news
                            (title, text, date)
                            VALUES ('".mysql_real_escape_string($title)."', '".mysql_real_escape_string($text)."', NOW())",$con);
                        $id = mysql_insert_id($con);
                        $add = true;
                    } else {
                        mysql_query("UPDATE ".$db_prefix."news
                            SET title='$title', text='$text'
                            WHERE id=$id",$con);
                    }
                    $result = mysql_query("SELECT * FROM ".$db_prefix."news WHERE id=$id",$con);
                    while ($row = mysql_fetch_array($result)) {
                        if ($add) echo "<li id=\"proj$id\">";
                        formatProject($row);
                        if ($add) echo "</li>";
                    }
                    break;
                case 'newsdel':
                    mysql_query("DELETE FROM ".$db_prefix."news WHERE id=$id");
                    echo "<i>Story Removed</i>";
                    break;
                
                case 'projform':
                    if ($id == 'add') $row = Array();
                    else {
                        $result = mysql_query("SELECT * FROM ".$db_prefix."projects WHERE id=$id",$con);
                        $row = mysql_fetch_array($result);
                    }
                    echo "<form id=\"ajaxform\">";
                    echo "<input type=\"text\" id=\"title\" value=\"".$row['title']."\"><br/>";
                    echo "<textarea type=\"text\" id=\"text\" cols=\"60\" rows=\"10\" >".$row['text']."</textarea><br/>";
                    echo "<button type=\"button\" onClick=\"cancel()\">Cancel</button>";
                    echo "<button type=\"button\" onClick=\"postProj('$id')\">Submit</button>";
                    echo "</form>";
                    break;
                    break;
                case 'projpost':
                    $text = $_POST['text'];
                    $title = $_POST['title'];
                    if ($id == 'add') {
                        mysql_query("INSERT INTO ".$db_prefix."projects
                            (title, text)
                            VALUES ('".mysql_real_escape_string($title)."', '".mysql_real_escape_string($text)."')",$con);
                        $id = mysql_insert_id($con);
                        $add = true;
                    } else {
                        mysql_query("UPDATE ".$db_prefix."projects
                            SET title='$title', text='$text'
                            WHERE id=$id",$con);
                    }
                    $result = mysql_query("SELECT * FROM ".$db_prefix."projects WHERE id=$id",$con);
                    while ($row = mysql_fetch_array($result)) {
                        if ($add) echo "<li id=\"proj$id\">";
                        formatProject($row);
                        if ($add) echo "</li>";
                    }
                    break;
                case 'projdel':
                    mysql_query("DELETE FROM ".$db_prefix."projects WHERE id=$id");
                    echo "<i>Project Removed</i>";
                    break;
                case 'projmove':
                    $id1 = $_POST['id1'];
                    $id2 = $_POST['id2'];
                    mysql_query("UPDATE ".$db_prefix."projects
                            SET id=1000
                            WHERE id=$id1",$con); // If this breaks, then we are amazing
                    mysql_query("UPDATE ".$db_prefix."projects
                            SET id=$id1
                            WHERE id=$id2",$con);
                    mysql_query("UPDATE ".$db_prefix."projects
                            SET id=$id2
                            WHERE id=1000",$con);
                    projList();
                    break;

                case 'pageform':
                    if ($id == 'add') $row = Array();
                    else {
                        $result = mysql_query("SELECT * FROM ".$db_prefix."pages WHERE id=$id",$con);
                        $row = mysql_fetch_array($result);
                    }
                    echo "<form id=\"ajaxform\">";
                    echo "<input type=\"text\" id=\"name\" value=\"".$row['name']."\"><br/>";
                    echo "<input type=\"text\" id=\"title\" value=\"".$row['title']."\"><br/>";
                    echo "<textarea type=\"text\" cols=\"100\" rows=\"20\" id=\"text\" cols=\"60\" rows=\"10\" >".$row['text']."</textarea><br/>";
                    echo "<button type=\"button\" onClick=\"cancel()\">Cancel</button>";
                    echo "<button type=\"button\" onClick=\"postPage('$id')\">Submit</button>";
                    echo "</form>";
                    break;
                    break;
                case 'pagepost':
                    $text = $_POST['text'];
                    $title = $_POST['title'];
                    $name = $_POST['name'];
                    if ($id == 'add') {
                        mysql_query("INSERT INTO ".$db_prefix."pages
                            (name, title, text)
                            VALUES ('".mysql_real_escape_string($name)."', '".mysql_real_escape_string($title)."', '".mysql_real_escape_string($text)."')",$con);
                        $id = mysql_insert_id($con);
                        $add = true;
                    } else {
                        mysql_query("UPDATE ".$db_prefix."pages
                            SET name='".$name."', title='".$title."', text='".$text."'
                            WHERE id=$id",$con);
                    }
                    $result = mysql_query("SELECT * FROM ".$db_prefix."pages WHERE id=$id",$con);
                    while ($row = mysql_fetch_array($result)) {
                        if ($add) echo "<li id=\"pages$id\">";
                        formatPage($row);
                        if ($add) echo "</li>";
                    }
                    break;
                case 'pagedel':
                    mysql_query("DELETE FROM ".$db_prefix."pages WHERE id=$id");
                    echo "<i>Page Removed</i>";
                    break;

                case 'memform':
                    if ($id == 'add') $row = Array();
                    else {
                        $result = mysql_query("SELECT * FROM ".$db_prefix."members WHERE id=$id ORDER BY id",$con);
                        $row = mysql_fetch_array($result);
                    }
                    echo "<form id=\"ajaxform\">";
                    echo "<input type=\"text\" id=\"fn\" value=\"".$row['firstName']."\">";
                    echo "<input type=\"text\" id=\"ln\" value=\"".$row['lastName']."\">";
                    echo " - <input type=\"text\" id=\"pos\" value=\"".$row['position']."\">";
                    echo "<button type=\"button\" onClick=\"cancel()\">Cancel</button>";
                    echo "<button type=\"button\" onClick=\"postMem('$id')\">Submit</button>";
                    echo "</form>";
                    break;
                case 'mempost':
                    $fn = $_POST['fn'];
                    $ln = $_POST['ln'];
                    $pos = $_POST['pos'];
                    if ($id == 'add') {
                        mysql_query("INSERT INTO ".$db_prefix."members
                            (firstName, lastName, position)
                            VALUES ('".mysql_real_escape_string($fn)."', '".mysql_real_escape_string($ln)."', '".mysql_real_escape_string($pos)."')",$con);
                        $id = mysql_insert_id($con);
                        $add = true;
                    } else {
                        mysql_query("UPDATE ".$db_prefix."members
                            SET firstName='$fn', lastName='$ln', position='$pos'
                            WHERE id=$id",$con);
                    }
                    $result = mysql_query("SELECT * FROM ".$db_prefix."members WHERE id=$id",$con);
                    while ($row = mysql_fetch_array($result)) {
                        if ($add) echo "<li id=\"mem$id\">";
                        formatMember($row);
                        if ($add) echo "</li>";
                    }
                    break;
                case 'memdel':
                    mysql_query("DELETE FROM ".$db_prefix."members WHERE id=$id");
                    echo "<i>User Removed</i>";
                    break;
                case 'memmove':
                    $id1 = $_POST['id1'];
                    $id2 = $_POST['id2'];
                    mysql_query("UPDATE ".$db_prefix."members
                            SET id=1000
                            WHERE id=$id1",$con); // If this breaks, then we are amazing
                    mysql_query("UPDATE ".$db_prefix."members
                            SET id=$id1
                            WHERE id=$id2",$con);
                    mysql_query("UPDATE ".$db_prefix."members
                            SET id=$id2
                            WHERE id=1000",$con);
                    memList();
                    break;
                    
                case 'othform':
                    if ($id == 'social' || $id == 'contact' || $id == 'links') $row = Array();
                    else {
                        $result = mysql_query("SELECT * FROM ".$db_prefix."other WHERE id=$id",$con);
                        $row = mysql_fetch_array($result);
                    }
                    echo "<form id=\"ajaxform\">";
                    echo "<input type=\"text\" id=\"text\" value=\"".$row['text']."\">";
                    echo "<input type=\"text\" id=\"link\" value=\"".$row['link']."\">";
                    echo "<button type=\"button\" onClick=\"cancel()\">Cancel</button>";
                    echo "<button type=\"button\" onClick=\"postOth('$id')\">Submit</button>";
                    echo "</form>";
                    break;
                case 'othpost':
                    $text = $_POST['text'];
                    $link = $_POST['link'];
                    if ($id == 'social' || $id == 'contact' || $id == 'links') {
                        mysql_query("INSERT INTO ".$db_prefix."other
                            (link, text, type)
                            VALUES ('".mysql_real_escape_string($link)."', '".mysql_real_escape_string($text)."', '".mysql_real_escape_string($id)."')",$con);
                        $id = mysql_insert_id($con);
                        $add = true;
                    } else {
                        mysql_query("UPDATE ".$db_prefix."other
                            SET link='$link', text='$text'
                            WHERE id=$id",$con);
                    }
                    $result = mysql_query("SELECT * FROM ".$db_prefix."other WHERE id=$id",$con);
                    while ($row = mysql_fetch_array($result)) {
                        if ($add) echo "<li id=\"oth$id\">";
                        formatOther($row);
                        if ($add) echo "</li>";
                    }
                    break;
                case 'othdel':
                    mysql_query("DELETE FROM ".$db_prefix."other WHERE id=$id");
                    echo "<i>Item Removed</i>";
                    break;
                case 'comdel':
                    mysql_query("DELETE FROM ".$db_prefix."comments WHERE id=$id");
                    echo "<i>Item Removed</i>";
                    break;
            }
        }
    } else echo "Access denied. $sid";
?>
