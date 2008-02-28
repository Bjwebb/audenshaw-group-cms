<?php
function showOther($type) {
    global $con;
    global $db_prefix;
    if ($result = mysql_query("SELECT text FROM `".$db_prefix."other` WHERE type='$type'",$con)); else die(mysql_error());
    $row = mysql_fetch_array($result);
    return $row['text'];
}
function upOther($type, $value) {
    global $con;
    global $db_prefix;
    mysql_query("DELETE FROM `".$db_prefix."other` WHERE type='$type'", $con) or die(mysql_error());
    mysql_query("INSERT INTO `".$db_prefix."other` (type,text) VALUES ('$type','$value')", $con) or die(mysql_error());
}

function formatNews($row) {
    $i = $row['id'];
    echo "<b>".$row['title']."</b><br/>";
    echo "".nl2br($row['text'])."<br/>";
    echo " <button type=\"button\" onClick=\"editNews($i)\">Edit</button>";
    echo "<button type=\"button\" onClick=\"delNews($i)\">Delete</button>";
}
function formatProject($row) {
    $i = $row['id'];
    echo "<b>".$row['title']."</b><br/>";
    echo "".nl2br($row['text'])."<br/>";
    echo " <button type=\"button\" onClick=\"editProj($i)\">Edit</button>";
    echo "<button type=\"button\" onClick=\"delProj($i)\">Delete</button>";
}
function formatMember($row) {
    $i = $row['id'];
    echo $row['firstName']." ".$row['lastName'];
    if ($row['position'])
        echo "<span class=\"job\"> - ".$row['position']."</span>";
    echo " <button type=\"button\" onClick=\"editMem($i)\">Edit</button>";
    echo "<button type=\"button\" onClick=\"delMem($i)\">Delete</button>";
}
function formatOther($row) {
    $i = $row['id'];
    echo "<a href=\"".$row['link']."\">".$row['text']."</a>";
    echo " <button type=\"button\" onClick=\"editOth($i)\">Edit</button>";
    echo "<button type=\"button\" onClick=\"delOth($i)\">Delete</button>";
}


function sqlcon() {
    global $con;
    global $db_prefix;
    include '../config.php';
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
    die('Could not connect: ' . mysql_error());
    }
    if (mysql_select_db($db_db, $con)); else die(mysql_error());
}
function adminAbout() {
    global $con;
    global $db_prefix;
    sqlcon(); ?>
        <h2>About</h2>
        <form name="form1">
            <textarea cols="100" rows="20" id="about"><?php
                $result = mysql_query("SELECT text FROM ".$db_prefix."other WHERE type='about'",$con);
                $row = mysql_fetch_row($result);
                echo $row[0];
            ?></textarea>
            <div id="aboutResult"></div>
            <button type="button" onClick="postAbout()">Submit</button>
        </form>
<?php }
function adminNews() {
    global $con;
    global $db_prefix;
    sqlcon(); ?>
        <h2>News</h2>
        <ul id="newslist"><?php
            $result = mysql_query("SELECT * FROM ".$db_prefix."news",$con);
            while ($row = mysql_fetch_array($result)) {
                $i = $row['id'];
                echo "<li id=\"news".$row['id']."\">";
                formatNews($row);
                echo "</li>\n";
            }
        ?></ul>
        <div id="newsadd"><button type="button" onClick="editNews('add')">Add New Story</button></div>
<?php }
function adminProjects() {
    global $con;
    global $db_prefix;
    sqlcon(); ?>
        <h2>Projects</h2>
        <ul id="projlist"><?php
            $result = mysql_query("SELECT * FROM ".$db_prefix."projects",$con);
            while ($row = mysql_fetch_array($result)) {
                echo "<li id=\"proj".$row['id']."\">";
                formatProject($row);
                echo "</li>\n";
            }
        ?></ul>
        <div id="projadd"><button type="button" onClick="editProj('add')">Add New Project</button></div>
<?php }
function memList() {
    global $con;
    global $db_prefix;
    sqlcon();
    $result = mysql_query("SELECT * FROM ".$db_prefix."members WHERE position!='' ORDER BY id",$con);
    $prevID = '';
    $rowArray;
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $rowArray[$i] = $row;
        $i++;
    }
    for ($j=0; $j<$i; $j++) {
        $ID = $rowArray[$j]['id'];
        $nextID = $rowArray[$j+1]['id'];
        echo "<li id=\"mem$ID\">";
        if ($prevID)
            echo "<a href=\"javascript:void()\" onClick=\"ajax('type=memmove&id1=$prevID&id2=$ID', 'memlist', false)\" style=\"text-decoration: none;\">↑</a>";
        else echo "↑";
        if ($nextID)
            echo "<a href=\"javascript:void()\" onClick=\"ajax('type=memmove&id1=$ID&id2=$nextID', 'memlist', false)\" style=\"text-decoration: none;\">↓</a>";
        else echo "↓";
        echo " ";
        formatMember($rowArray[$j]);
        echo "</li>\n";
        $prevID = $ID;
    }
    $result = mysql_query("SELECT * FROM ".$db_prefix."members WHERE position='' ORDER BY lastName",$con);
    while ($row = mysql_fetch_array($result)) {
        echo "<li id=\"mem".$row['id']."\">";
        formatMember($row);
        echo "</li>\n";
    }
}
function adminMembers() { ?>
        <h2>Members</h2>
        <ul id="memlist">
        <?php memList(); ?>
        </ul>
        <div id="memadd"><button type="button" onClick="editMem('add')">Add New Member</button></div>
<?php }
function adminComments() {
    global $con;
    global $db_prefix;
    sqlcon();?>
        <h2>Delete Comments</h2>
        <?php
            $result = mysql_query("SELECT * FROM ".$db_prefix."comments",$con);
            while ($row = mysql_fetch_array($result)) {
                $i = $row['id'];
                echo "<div id=\"com".$i."\">";
                echo "<b>" . $row['name'] . "</b><br />";
                echo nl2br($row['comment']) . "<br />";
                echo "<form><button type=\"button\" onClick=\"delCom($i)\">Delete</button></form></div>\n";
            }
}
function adminOther() {
    global $con;
    global $db_prefix;
    sqlcon(); ?>
        <h2>Other</h2>
        <?php
            $types = Array('social', 'contact', 'links');
            for ($j=0; $j<count($types); $j++) {
                $type = $types[$j];
                echo "<ul id=\"oth$type"."list\">";
                $result = mysql_query("SELECT * FROM ".$db_prefix."other WHERE type='$type'",$con);
                while ($row = mysql_fetch_array($result)) {
                    echo "<li id=\"oth".$row['id']."\">";
                    formatOther($row);
                    echo "</li>\n";
                }
                echo "</ul>";
                echo "<div id=\"oth$type\"><button type=\"button\" onClick=\"editOth('$type')\">Add New Item</button></div>";
                echo "<br/>";
            }
}

function dirList ($directory) {
    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // keep going until all files in directory have been read
    while ($file = readdir($handler)) {

        // if $file isn't this directory or its parent, 
        // add it to the results array
        if ($file != '.' && $file != '..')
            $results[] = $file;
    }

    // tidy up: close the handler
    closedir($handler);

    // done!
    return $results;
}

?>
