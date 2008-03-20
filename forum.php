<?php
$allowGuest = true;
$pages = true;
$perPage = 10;
include "config.php";
include "header.php";
?>
<?php session_start(); 
$con = mysql_connect($db_host,$db_name,$db_pass);
if (!$con) {
  die('Could not connect: ' . mysql_error());
}
mysql_select_db("$db_db", $con);
?>
<head>
    <title>ADFA Forum</title>
    <link rel="stylesheet" type="text/css" href="forum.css" />
    <link rel="stylesheet" type="text/css" href="style<?php echo $css_suffix ?>.css" />
    <!--[if IE ]>
    <link rel="stylesheet" type="text/css" href="ie_style<?php echo $css_suffix ?>.css" />
    <![endif]-->
</head>
<body>
    <div id="all">
    <div id="header">
        <h1><?php echo showOther("title"); ?></h1>
        <?php $sub = showOther("subtitle"); echo "<h2 id=\"sub\">".$sub."</h2>"; ?>
    </div>
    <div id="forum">
    <div id="content">
        <div id="left">
            <div class="box_left">
<form style="display: none" name="cooke"><textarea name="field"></textarea></form>
<?php
$loggedIn = false;

// **************************************** Functions ****************************************
function countThreads() {
    global $db_prefix_forum;
    $result = mysql_query("SELECT COUNT(*) FROM ".$db_prefix_forum."threads");
    $row = mysql_fetch_array($result);
    return $row['COUNT(*)'];
}
function countPosts() {
    global $db_prefix_forum;
    $result = mysql_query("SELECT COUNT(*) FROM ".$db_prefix_forum."posts");
    $row = mysql_fetch_array($result);
    return $row['COUNT(*)'];
}
function countReplies($thread) {
    global $db_prefix_forum;
    $result = mysql_query("SELECT COUNT(*) FROM ".$db_prefix_forum."posts WHERE threadID=$thread");
    $row = mysql_fetch_array($result);
    return $row['COUNT(*)'] - 1;
}
function countUsers() {
    global $db_prefix_forum;
    $result = mysql_query("SELECT COUNT(*) FROM ".$db_prefix_forum."users");
    $row = mysql_fetch_array($result);
    return $row['COUNT(*)'];
}
function newThread($name, $user, $content) {
    global $db_prefix_forum;
    if ($name == NULL) { $name = "Untitled"; }
    global $con;
    if (!mysql_query("INSERT INTO ".$db_prefix_forum."threads(title, author, time) VALUES('$name', '$user', NOW())",$con)) echo mysql_error();

    $threadCount = countThreads();

    if (!mysql_query("INSERT INTO ".$db_prefix_forum."posts(threadID, content, author, time) VALUES('$threadCount', '$content', '$user', NOW())",$con)) echo mysql_error();
}
function newPost($thread, $user, $content) {
    global $db_prefix_forum;
    global $con;
    if (!mysql_query("INSERT INTO ".$db_prefix_forum."posts(threadID, content, author, time) VALUES('$thread', '$content', '$user', NOW())",$con)) echo mysql_error();
}
function editPost($thread, $post, $content) {

}
function newUser($user, $password) {
    global $db_prefix_forum;
    global $con;
    $password = sha1($password);
    if (!mysql_query("INSERT INTO ".$db_prefix_forum."users(name, password, time) VALUES('$user', '$password', NOW())",$con)) echo mysql_error();
}
function markRead($user, $post) {
    global $db_prefix_forum;
    // Applies to posts
    global $con;
    if (!mysql_query("INSERT INTO ".$db_prefix_forum."hasRead(user, postID) VALUES('$user', '$post')",$con)) echo mysql_error();
}
function hasRead($user, $thread) {
    global $db_prefix_forum;
    // Applies to threads
    global $con;
    $result = mysql_query("SELECT MAX(postID) FROM ".$db_prefix_forum."posts WHERE threadID=$thread",$con);
    $row = mysql_fetch_array($result);
    $result2 = mysql_query("SELECT * FROM ".$db_prefix_forum."hasRead WHERE postID=".$row['MAX(postID)']." AND user='$user'",$con);
    echo mysql_error();
    return mysql_fetch_array($result2);
}
function getID($user) {
    global $db_prefix_forum;
// THis function should not be needed
    global $con;
    sha1($password);
    $result = mysql_query("SELECT * FROM ".$db_prefix_forum."users WHERE name='$user' AND password='$password'",$con);
    return mysql_fetch_array($result);
}

// **************************************** Creation ****************************************
$created = true;
if ($_GET['mode'] == "debug") $created = false;
if (!$created) {
//    mysql_query("DROP DATABASE $db_db",$con);
//    if (!mysql_query("CREATE DATABASE $db_db",$con)) echo mysql_error();
    mysql_select_db($db_db, $con);

    if (!mysql_query("CREATE TABLE ".$db_prefix_forum."threads(
        threadID int NOT NULL AUTO_INCREMENT, 
        PRIMARY KEY(threadID),
        title varchar(50),
        author varchar(15),
        time datetime NOT NULL default '0000-00-00 00:00:00'
        )",$con)) echo mysql_error();

    if (!mysql_query("CREATE TABLE ".$db_prefix_forum."posts(
        postID int NOT NULL AUTO_INCREMENT, 
        PRIMARY KEY(postID),
        threadID int,
        author varchar(15),
        content varchar(500),
        time datetime NOT NULL default '0000-00-00 00:00:00'
        )",$con)) echo mysql_error();

    if (!mysql_query("CREATE TABLE ".$db_prefix_forum."users(
        userID int NOT NULL AUTO_INCREMENT, 
        PRIMARY KEY(userID),
        name varchar(15),
        password varchar(50),
        time datetime NOT NULL default '0000-00-00 00:00:00'
        )",$con)) echo mysql_error();
        
    
    if (!mysql_query("CREATE UNIQUE INDEX ".$db_prefix_forum."name_index
        ON users (name)",$con)) echo mysql_error();

    if (!mysql_query("CREATE TABLE ".$db_prefix_forum."hasRead(
        user varchar(15),
        postID int
        )",$con)) echo mysql_error();
    
    newThread("Welcome", "Webbtest", "Welcome to the BAIComic forum. Feel free to reply to this post and say hi.");
}
mysql_select_db("$db_db", $con);
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

// Get url variables
$thread = $_GET['thread'];
$post = $_GET['post'];
$mode = $_GET['mode'];
$type = $_GET['type'];

// **************************************** Take Post Values ****************************************
if ($type=='post') {
    $type='view';
    $main = $_POST['main'];
    $title = $_POST['title'];
    $name = $_POST['name'];
    if ($name == '') {
        $name = $user;
    }
    else {
        $name = $name."($user)";
    }
    if ($mode=='add') {
        newThread($title, $name, $main);
    }
    else if ($mode=='reply') {
        newPost($thread, $name, $main);
    }
    else if ($mode=='edit') {
        editPost($thread, $post, $main);
    }
    else if ($mode=='logout') {
        $_SESSION['user'] = '';
        $_SESSION['pass'] = '';
        $user = '';
        $pass = '';
        $loggedIn = false;
    }
    else if ($mode=='user') {
        mysql_query("UPDATE ".$db_prefix_forum."users
        SET firstName='".$_POST['firstName']."',
        lastName='".$_POST['lastName']."',
        state='".$_POST['state']."'
        WHERE userID=".$_GET['userid'],$con) or die(mysql_error());
        $pass = $_POST['pass'];
        $pass2 = $_POST['pass2'];
        if ($pass != '' && $pass2 != '') {
            if ($pass == $pass2) {
                mysql_query("UPDATE ".$db_prefix_forum."users
                SET password='".sha1($pass)."'
                WHERE userID=".$_GET['userid'],$con) or die(mysql_error());
            }
            else {
                echo "Passwords do not match.";
                $mode = "edit";
            }
        }
        $type = "user";
    }
    // User login/register
    else {
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        if ($mode=='login') {
            if (checkPass($user, $pass)) {
                $_SESSION['user'] = $user;
                $_SESSION['password'] = $pass;
                $loggedIn = true;
            } else {
                $loginMessage = "Sorry, incorrect username and/or password.";
                $type = login;
            }
        }
        else if ($mode=='register') {
            $pass2 = $_POST['pass2'];
            if ($pass != $pass2) { $loginMessage = "Passwords do not match!"; $type = 'register'; }
            else {
                newUser($user, $pass);
            }
        }
    }

}

// **************************************** Forum Header **************************************** ?>
<h2><a href="forum.php" style="color: white;">FORUM</a></h2>
<?php
// **************************************** Show post form ****************************************
if ($loginMessage) echo "<div style=\"color:red;\">$loginMessage</div>";
if ($type=='edit') {
    if ($loggedIn || $allowGuest) {
?>
<div class="text">
<form name="form" action="<?php echo "forum.php?type=post&mode=$mode"; if ($mode == "reply" || $mode == "edit"){ echo "&thread=$thread"; } if ($mode == "edit") { echo "&$post"; } ?>" method="post">
<?php if ($mode == 'add') { ?>
<div class="formentry"><span class="label">Title: </span> <span class="field"><input name="title" size="50" maxlength="50"></input></span></div>
<?php } ?>
<?php if (!$loggedIn) { ?>
<div class="formentry"><span class="label">Name: </span> <span class="field"><input name="name" size="50" maxlength="50"></input></span></div>
<?php } ?>
    <div class="formentry"><span class="label">Comment: </span> <span class="field"><textarea name="main" cols="80" rows="10" maxlength="500"></textarea></td></div>
    <div class="formentry"><span class="label"></span><span class="field">
        <button type="submit" class="formbutton">Post</button>
        <button type="button" class="formbutton" onClick="javascript:history.go(-1)">Cancel</button>
    </span></div>
</form>
</div>
<?php
    } else echo "Sorry, you are not permitted to do that, please log on or register.";
}

// **************************************** Register & Login ****************************************
else if ($type == 'register') {
?>
<div class="text">
<form name="form" action="forum.php?type=post&mode=register" method="post" class="form">
    <div class="formentry"><span class="label">Username: </span> <span class="field"><input name="user" size="50" maxlength="50"></input></span></div>
    <div class="formentry"><span class="label">Password: </span> <span class="field"><input name="pass" size="50" maxlength="50" type="password"></input></span></div>
    <div class="formentry"><span class="label">Repeat password: </span> <span class="field"><input name="pass2" size="50" maxlength="50" type="password"></input></span></div>
    <div class="formentry"><span class="label"></span><span class="field"><button type="submit" class="formbutton">Register</button></span></div>
        
</form>
</div>
<?php
}
else if ($type == 'login') {
?>
<div class="text">
Here you can log into your forum account. If you do not have an account, the why not <a href="forum.php?type=register">register</a>.
<form name="form" action="forum.php?type=post&mode=login" method="post" class="form">
    <div class="formentry"><span class="label">Username: </span> <span class="field"><input name="user" size="50" maxlength="50"></input></span></div>
    <div class="formentry"><span class="label">Password: </span> <span class="field"><input name="pass" size="50" maxlength="50" type="password"></input></span></div>
    <div class="formentry"><span class="label"></span><span class="field"><button type="submit" class="formbutton">Login</button></span></div>
</form>
</div>
<?php
}

// **************************************** User Functions *************************************

else if ($type == 'user') {
?><div style="margin:10px;"><?php
    $userid = $_GET['userid'];
    $result = mysql_query("SELECT * FROM ".$db_prefix_forum."users WHERE userID=$userid");
    $row = mysql_fetch_array($result);
    if ($mode == 'edit') {
    if ($user == $row['name']) {
            echo "Editing profile for ". $row['name'];
    ?><form name="form" action="forum.php?type=post&mode=user&userid=<?php echo $userid ?>" method="post" class="form">
        <div class="formentry"><span class="label">Change Password: </span> <span class="field"><input name="pass" size="50" maxlength="50" type="password"></input></span></div>
        <div class="formentry"><span class="label">Repeat password: </span> <span class="field"><input name="pass2" size="50" maxlength="50" type="password"></input></span></div>
        <br />
        <div class="formentry"><span class="label">First Name: </span> <span class="field"><input name="firstName" size="50" maxlength="50" value="<?php echo $row['firstName'] ?>"></input></span></div>
        <div class="formentry"><span class="label">First Name: </span> <span class="field"><input name="lastName" size="50" maxlength="50" value="<?php echo $row['lastName'] ?>"></input></span></div>
    <div class="formentry"><span class="label">Status:</span><select name="state">
    <option value="non"<?php if ($row['state'] == "non") echo " selected" ?>>Non Member</option>
    <option value="hat"<?php if ($row['state'] == "hat") echo " selected" ?>>HardHat</option>
    <option value="fox"<?php if ($row['state'] == "fox") echo " selected" ?>>Fox</option>
    <option value="peng"<?php if ($row['state'] == "peng") echo "selected" ?>>Penguin</option>
    <option value="gnu"<?php if ($row['state'] == "gnu") echo " selected" ?>>GNU</option>
    </select></div>
        <div class="formentry"><span class="label"></span><span class="field"><button type="submit" class="formbutton">Change</button></span></div>
    </form>
    <?php }
    else echo "Access denied!";
    }
    else {
         if ($user == $row['name']) echo "<a href=\"forum.php?type=user&mode=edit&userid=$userid\">edit</a>";
         echo "<br/>Username: " . $row['name'];
         echo "<br/>First Name: " . $row['firstName'];
         echo "<br/>Last Name: " . $row['lastName'];
         echo "<br/>Joined: " . $row['time'];
         if ($row['position']) echo "<br/>Position: " . $row['position'];
         echo "<br/>Status: " . $row['state'];
    }
?></div><?php
}

// **************************************** Forum Index ****************************************
else if ($type == '' || $type == 'view') {
    if ($thread == '') { ?>
<table class="table">
<tr class="top-row">
    <td class="read">Read?</td>
    <td class="post_title">Thread name</t$loggedInd>
    <td class="by">First Post</td>
    <td class="replies">Replies</td>
    <td class="last">Last post</td>
</tr>

<?php $result = mysql_query("SELECT * FROM ".$db_prefix_forum."threads");
while ($row = mysql_fetch_array($result)) { 
    if ($loggedIn && hasRead($user, $row['threadID'])) { ?>
<tr class="row-read">
    <td class="read">Read</td>
<?php } else { ?>
<tr class="row">
    <td class="read">Unread</td>
<?php } ?>
    <td class="post_title"><?php echo '<a href="forum.php?thread=' . $row[threadID] . '">' . $row['title']; ?></a></td>
    <td class="by"><?php echo "By " . $row['author'] . " at " . date("d/m/y H:i", strtotime($row['time'])); ?></td>
    <td class="replies"><?php $replies = countReplies($row['threadID']); echo $replies ?></td>
    <td class="last"><?php $row = mysql_fetch_array(mysql_query("SELECT * FROM ".$db_prefix_forum."posts WHERE threadID=" . $row['threadID'] . " ORDER BY postID DESC", $con)); echo "By " . $row['author'] . " at " . date("d/m/y H:i", strtotime($row['time'])); ?></td>
</tr>
<?php } ?>

<tr class="top-row">
    <td class="read"></td>
    <td class="post_title"><a href="forum.php?type=edit&mode=add">Post New Thread</a></td>
    <td class="by"></td>
    <td class="replies"></td>
    <td class="last"></td>
</tr>

</table>

<?php
// **************************************** Show a thread ****************************************
    } else {
        if ($pages) {
            $perPage++; // This is probably a bug, and should be fixed.
            $result = mysql_query("SELECT COUNT(*) FROM ".$db_prefix_forum."posts WHERE threadID=$thread ORDER BY postID", $con);
            $row = mysql_fetch_array($result);
            $page = $_GET['page'];
            if ($page == '') $page = 1;
            $numPages = ceil($row['COUNT(*)']/$perPage);
            if ($page > $numPages) { echo "Sorry, there are not that many pages, showing page one.<br/><br/>"; $page = 1; }
            $buttons = "<ul class=\"post-nav\"><li class=\"nav2\"><a href=\"forum.php?thread=$thread&page=1\">&larr;&mdash;&mdash;</a></li><li class=\"post-nav\">";
            
            if ($page-1 > 0) $buttons .= "<a href=\"forum.php?thread=$thread&page=" . ($page-1) . "\">Previous</a>";
            else $buttons .= "Previous";
            
            $buttons .= "</li><li class=\"post-nav\">";
            
            if ($page < $row['COUNT(*)']/$perPage) $buttons .= "<a href=\"forum.php?thread=$thread&page=" . ($page+1) . "\">Next</a>";
            else $buttons .= "Next";
            
            $buttons .= "</li><li class=\"post-nav\"><a href=\"forum.php?thread=$thread&page=$numPages\">&mdash;&mdash;&rarr;</a></ul>";
            echo $buttons;
        }
        echo "<div class=\"text\" style=\"margin: 20 30;\">";
        $result = mysql_query("SELECT * FROM ".$db_prefix_forum."posts WHERE threadID=$thread ORDER BY postID", $con);
        if ($page) {
            $skip = ($page-1)*$perPage;
            for ($i=1; $i<$skip; $i++) { $row = mysql_fetch_array($result); }
        }
        $i = 1;
        while ($row = mysql_fetch_array($result)) { 
            $i++;
            echo "<b>" . $row['author'] . " &mdash; " . date("d/m/y H:i", strtotime($row['time'])) . " &mdash; " . $row['postID'] . "</b><br/>" . nl2br($row['content']) . "<br/><br/>";
            if ($loggedIn) { markRead($user, $row['postID']); }
            if  ($i == $perPage) break;
        }
?>
<a href="forum.php?<?php echo "type=edit&mode=reply&thread=$thread" ?>">Reply</a>
</div>
<?php if ($pages) { echo $buttons; } } } 

// **************************************** Forum Footer **************************************** ?>
</div>
</div>

<div id="right">
<?php navbox(); ?>
<div class="box_right">
<h2>Stats</h2>
<ul class="forum">
<li class="forum">Threads: <?php echo countThreads(); ?></li>
<li class="forum">Posts: <?php echo countPosts(); ?></li>
<li class="forum">Users: <?php echo countUsers(); ?></li>
</ul>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
