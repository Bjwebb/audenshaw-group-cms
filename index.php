<?php
    include 'config.php';
    include 'header.php';
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
      die('Could not connect: ' . mysql_error());
    }
    if (mysql_select_db($db_db, $con)); else die(mysql_error()); 
session_start(); 
$user = $_SESSION['user'];
$result = mysql_query("SELECT userID FROM ".$db_prefix_forum."users WHERE name='$user'");
$row = mysql_fetch_array($result);
$userID = $row['userID'];
$pass = $_SESSION['password'];
if (!isset($_SESSION['user']) || !checkPass($user, $pass)) {
    $user = "Guest";
    $loggedIn = false;
} else {
    $loggedIn = true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="application/xhtml+xml;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="style<?php echo $css_suffix ?>.css" />
        <!--[if IE ]>
        <link rel="stylesheet" type="text/css" href="ie_style<?php echo $css_suffix ?>.css" />
        <![endif]-->
        <script type="text/javascript" src="main.js"></script>
        <script type="text/javascript" src="<?php echo $img_serv; ?>list.js"></script>
        <script type="text/javascript"><!--
        imgServ = '<?php echo $img_serv; ?>';
        --></script>
        <title><?php echo showOther("title"); ?></title>
    </head>
    <body>
        <div id="all">
            <script type="text/javascript"><!--
            if (screen.width<801) {
                document.getElementById('all').style.margin = '0px';
            }
            --></script>
            <div id="header">
                <h1><?php echo showOther("title"); ?></h1>
                <?php $sub = showOther("subtitle"); echo "<h2 style=\"color:white;\">".$sub."</h2>"; ?>
            </div>
            <div id="content">
                <div id="left">
                    <div id="about" class="box_left">
                    <div id="about2" class="box_left_inner">
                        <h2>About Us</h2>
                            <?php if (showOther("image")) { ?><div style="float: right;"><img src="<?php echo $img_serv . showOther("image"); ?>" alt="Our Temporary Logo" width="150" style="margin:10px" /></div><?php } ?>
<?php
    if ($result = mysql_query("SELECT text FROM `".$db_prefix."other` WHERE type='about'",$con)); else die(mysql_error());
    while ($row = mysql_fetch_array($result)) echo "<p>".nl2br($row['text'])."</p>";
?>
                    </div>
                    </div>
                    <div id="news" class="box_left">
                    <div id="news2" class="box_left_inner">
                        <h2>News</h2>
                        <ul class="ajaxy">
<?php 
if ($result = mysql_query("SELECT title,text,date FROM `".$db_prefix."news` ORDER BY date DESC",$con)); else die(mysql_error());
$link = Array("test");
$text = Array();
$date = Array();
$i=1;
while ($row = mysql_fetch_array($result)) {
    $link[$i] = $row['title'];
    $text[$i] = $row['text'];
    $date[$i] = date_parse($row['date']);
    $i++;
}
$length = count($link) - 1;
echo "<li style=\"list-style-type: none;\">";
echo "<a href=\"javascript:news_expandAll($length)\">Expand All</a>";
echo " - ";
echo "<a href=\"javascript:news_shrinkAll($length)\">Shrink All</a>";
echo "</li>";
for ($i=1; $i<($length+1); $i++) {
    echo "<li class=\"ajaxy\"><a href=\"javascript:news_expand($i)\" id=\"headline$i\">".$link[$i]." - ".$date[$i][day].'/'.$date[$i][month].'/'.$date[$i][year]."</a></li>";
    echo "<li style=\"list-style-type: none;\" id=\"story$i\">".nl2br($text[$i])."<br /><br /></li>";
}
echo "</ul><script type=\"text/javascript\"><!--\nnews_init($length); document.write(\"*Click to expand.\");\n--></script>";
?>
                    </div>
                    </div>
<?php if (showOther("showPictures") == "TRUE") { ?>
                    <div id="pics" class="box_left">
                    <div id="pics2" class="box_left_inner" style="text-align: center;">
                        <h2>Pictures</h2>
                        <div id="pic_box">
                            <span
                            onclick="javascript: picLeft()"
                            onmouseover="javascript: document.getElementById('pic_left').style.backgroundColor = 'blue';"
                            onmouseout="javascript: document.getElementById('pic_left').style.backgroundColor = 'white';"
                            id="pic_left">
                                <a href="javascript: nowt()" onclick="javascript: picLeft()">&laquo;</a>
                            </span>
                            <span id="pic_container">
<script type="text/javascript"><!--
    start = 0;
    width = 4;
    document.write(picCreate());
--></script>
<noscript><div>Sorry, this section does not currently work if you do not have javascript enabled. However, the director housing the pictures can be found <a href="<?php echo $img_serv; ?>img/">here</a>.</div></noscript>
                            </span>
                            <span
                            onclick="javascript: picRight()"
                            onmouseover="javascript: document.getElementById('pic_right').style.backgroundColor = 'blue';"
                            onmouseout="javascript: document.getElementById('pic_right').style.backgroundColor = 'white';"
                            id="pic_right">
                                <a href="javascript: nowt()" onclick="javascript: picRight()">&raquo;</a>
                            </span>
                        </div>
                    </div>
                    </div>
<?php } ?>
                    <div id="projects" class="box_left">
                    <div id="projects2" class="box_left_inner">
                        <h2>Our Current Projects</h2>
                        <div class="text">
                            <ul class="ajaxy">
<?php 
if ($result = mysql_query("SELECT title,text FROM `".$db_prefix."projects`",$con)); else die(mysql_error());
$link = Array("test");
$link[0] = "Oh noes";
$text = Array();
$text[0] = "Oh noes";
$i=1;
while ($row = mysql_fetch_array($result)) {
    $link[$i] = $row['title'];
    $text[$i] = $row['text'];
    $i++;
}
$length = count($link) - 1;
echo "<li style=\"list-style-type: none;\">";
echo "<a href=\"javascript:expandAll($length)\">Expand All</a>";
echo " - ";
echo "<a href=\"javascript:shrinkAll($length)\">Shrink All</a>";
echo "</li>";
for ($i=1; $i<($length+1); $i++) {
    echo "<li class=\"ajaxy\"><a href=\"javascript:expand($i)\" id=\"link$i\">".$link[$i]."</a></li>";
    echo "<li style=\"list-style-type: none;\" id=\"project$i\">".nl2br($text[$i])."<br /><br /></li>";
}
echo "</ul><script type=\"text/javascript\"><!--\ninit($length); document.write(\"*Click to expand.\");\n--></script>";
?>
                        </div>
                    </div>
                    </div>
<?php /* ?>                    
                    <div id="comments" class="box_left">
                    <div id="comments2" class="box_left_inner">
                        <h2>Comments &amp; Ideas</h2>
                        <div style="margin-left: 20px">
                        <?php
                            $comment = $_POST['comment'];
                            $name = $_POST['name'];
                            if ($comment) {
                                if (!$name) $name = "Anonymous";
                                $comment = strip_tags($comment,'<a>');
                                if (mysql_query("INSERT INTO `".$db_prefix."comments` (name, comment) VALUES('$name', '$comment')", $con)); else die(mysql_error());
                            }
                            if ($result = mysql_query("SELECT * FROM `".$db_prefix."comments`",$con)); else die(mysql_error());
                            while ($row = mysql_fetch_array($result)) {
                                echo "<b>" . $row['name'] . "</b><br />";
                                echo nl2br($row['comment']) . "<br /><br />";
                            }
                        ?>
                        <b>Add a comment:</b><br/>
                        <form action="#comments" method="post">
                        <div>Name:<input type="text" name="name" /><br />
                        <textarea name="comment" cols="40" rows="10"></textarea><button>Submit</button></div></form>
                        <br />
                        </div>
                    </div>
                    </div>
<?php */ ?>
                    <div id="footer" class="box_left">
                    <div id="footer2" class="box_left_inner">
<div style="float:right"><p><a href="http://www.freedomain.co.nr/">
<img src="http://crnaa.4u.com.ru/808/2.gif" width="88" height="31" style="border: 0px;" alt="Free Domain Service" /></a></p></div>
<div style="float:left"><p><a href="http://validator.w3.org/check?uri=referer"><img
src="http://www.w3.org/Icons/valid-xhtml11-blue"
alt="Valid XHTML 1.1" height="31" width="88" /></a>
</p></div>
                        <p>
                        Site created by Ben Webb, if you experience any technical issues, please <a href="mailto:audfairtrade@googlemail.com">e-mail</a>.
                          
                        </p>
                        <!--[if IE ]>
                        <p><a href="http://getfirefox.com"><img src="http://two.xthost.info/aft/firefox_button.png" alt="| get Firefox |" height="15" width="80" /></a></p>
                        <![endif]-->
                    </div>
                    </div>
                </div>
                <div id="middle">
                </div>
                <div id="right">
                <?php navbox() ?>
<?php /* ?>
                    <div id="facebook" class="box_right">
                    <div id="facebook2" class="box_right_inner">
                        <h2>Social Networking</h2>
                        <ul>
<?php
    if ($result = mysql_query("SELECT text,link FROM `".$db_prefix."other` WHERE type='social'",$con)); else die(mysql_error());
    while ($row = mysql_fetch_array($result)) echo "<li><a href=\"".$row['link']."\">".$row['text']."</a></li>";
?>
                        </ul>
                        *Account required.
                    </div>
                    </div>
                    <div id="contact" class="box_right">
                    <div id="contact2" class="box_right_inner">
                        <h2>Contact Us</h2>
                        <ul>
<?php
    if ($result = mysql_query("SELECT text,link FROM `".$db_prefix."other` WHERE type='contact'",$con)); else die(mysql_error());
    while ($row = mysql_fetch_array($result)) echo "<li><a href=\"".$row['link']."\">".$row['text']."</a></li>";
?>
                        </ul>
                    </div>
                    </div>
<?php */ ?>
                    <div id="members" class="box_right">
                    <div id="members2" class="box_right_inner">
                        <h2>Members</h2>
                        <ul>
<?php
/*if ($result = mysql_query("SELECT * FROM `".$db_prefix."members` WHERE position!='' ORDER BY id",$con)); else die(mysql_error());
$members = Array ();
$i = 0;
while ($row = mysql_fetch_array($result)) {
    $members[$i][0] = $row['firstName']." ".$row['lastName'];
    $members[$i][1] = $row['position'];
    $i++;
}
if ($result = mysql_query("SELECT * FROM `".$db_prefix."members` WHERE position='' ORDER BY lastName",$con)); else die(mysql_error());
while ($row = mysql_fetch_array($result)) {
    $members[$i][0] = $row['firstName']." ".$row['lastName'];
    $members[$i][1] = $row['position'];
    $i++;
}

for ($i=0; $i<count($members); $i++) {
    echo "<li>" . $members[$i][0];
    if ($members[$i][1])
        echo "<span class=\"job\"> - ".$members[$i][1]."</span>";
    echo "</li>";
}*/

$userid = $_GET['userid'];
$result = mysql_query("SELECT * FROM ".$db_prefix_forum."users WHERE state!='non'");
while ($row = mysql_fetch_array($result)) {
    echo "<li>" . $row['firstName']." ".$row['lastName'];
    if ($members[$i][1])
        echo "<span class=\"job\"> - ".$row['position']."</span>";
}
?>
                        </ul>
                    </div>
                    </div>
                    <div id="links" class="box_right">
                    <div id="links2" class="box_right_inner">
                        <h2>Links</h2>
                        <ul>
<?php
    if ($result = mysql_query("SELECT text,link FROM `".$db_prefix."other` WHERE type='links'",$con)); else die(mysql_error());
    while ($row = mysql_fetch_array($result)) echo "<li><a href=\"".$row['link']."\">".$row['text']."</a></li>";
?>
                        </ul>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="pic_pop" style="
            position: absolute;
            background-color: #6CB6E7;
            top: 0px; left: 0px;
            text-align: center;
            visibility: hidden;
            overflow: auto;
            ">
            <span id="pic_title" style="font-size: 20px; font-weight: bold;"></span>
            <span id="pic_close" style="position: absolute; right: 0px;">
                <a href="javascript: nowt()" onclick="javascript:picClose()">
                    X
                </a>
            </span>
            <div id="pic_pic" style="">
            </div>
        </div>
    </body>
</html>
