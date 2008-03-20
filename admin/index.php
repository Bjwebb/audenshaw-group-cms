<?php
    function loginform() { /* ?>
<form method="post" action="index.php<?php echo "?id=".$_GET['id'] ?>"><input name="sid" type="password"><button type="submit">Login</submit></form>
    <?php */
        echo "Sorry, access denied - you must login with an admin account (on the forums) before you can use this page.";
    }

    include '../config.php';
    include '../header.php';
/*
    $sid = $_COOKIE[$db_prefix."tempcookie"];
    if (sha1($sid) != $password) {
        $sid = $_POST['sid'];
        if (sha1($sid) == $password) {
            setcookie($db_prefix."choccookie", $sid, time()+(7*24*60*60));
            $_SESSION['sid'] = $sid;
        }
        else echo "Sorry, access denied!<br />";
    }
    if (sha1($sid) == $password) {
*/
    session_start();
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
      die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($db_db, $con) or die(mysql_error());
    if (auth() && userPos($userID) == 'admin') {
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script type="text/javascript"><!--
            <?php if ($_GET['id']) echo "args = \"?id=" . $_GET['id'] . "\";\n";
            else echo "args = \"\";\n"; ?>
        --></script>
        <script type="text/javascript" src="ajax.js">
        </script>
        <script type="text/javascript" src="main.js">
        </script>
    </head>
    <body>
        <div style="magin-bottom: 0px"><a href="..">&larr; Back to site</a></div>
        <h1 style="margin-top: 0px; margin-bottom: 10px">Admin</h1>
        <div>
            <span class="tab"><a href="javascript:tab(1)">About</a></span>
            <span class="tab"><a href="javascript:tab(2)">News</a></span>
            <span class="tab"><a href="javascript:tab(3)">Projects</a></span>
            <span class="tab"><a href="javascript:tab(4)">Members</a></span>
            <span class="tab"><a href="javascript:tab(5)">Comments</a></span>
            <span class="tab"><a href="javascript:tab(6)">Other</a></span>
        </div>
        <div class="content" id="content">
<?php adminAbout(); ?>
        </div>
    </body>
</html>
<?php } else loginform(); ?>
