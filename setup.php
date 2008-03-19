<?php
include 'config.php';
include 'header.php';

$title = $_POST['title'];
$subtitle = $_POST['subtitle'];
$image = $_POST['image'];
$pics = $_POST['pics'];

if ($title) {
    $con = mysql_connect($db_host,$db_name,$db_pass);
    if (!$con) {
        die('Could not connect: ' . mysql_error());
    }
    
    // mysql_query("DROP DATABASE $db_db",$con); // Uncomment this to wipe the datase first
    
    mysql_query("CREATE DATABASE IF NOT EXISTS $db_db",$con);
    
    if (mysql_select_db($db_db, $con)); else die(mysql_error());
    
    mysql_query("CREATE TABLE `".$db_prefix."members` (
    `id` int(11) NOT NULL auto_increment,
    `firstName` char(20) default NULL,
    `lastName` char(20) default NULL,
    `position` char(40) default NULL,
    PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;", $con) or die(mysql_error());
    
    mysql_query("CREATE TABLE `".$db_prefix."news` (
    `id` int(11) NOT NULL auto_increment,
    `date` date NOT NULL default '0000-00-00',
    `title` char(50) default NULL,
    `text` text,
    PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;", $con) or die(mysql_error());
    
    mysql_query("CREATE TABLE `".$db_prefix."other` (
    `id` int(11) NOT NULL auto_increment,
    `type` char(20) default NULL,
    `text` text,
    `link` char(100) default NULL,
    PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;", $con) or die(mysql_error());
    
    mysql_query("CREATE TABLE `".$db_prefix."projects` (
    `id` int(11) NOT NULL auto_increment,
    `title` char(50) default NULL,
    `text` text,
    PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;", $con) or die(mysql_error());
    
    mysql_query("CREATE TABLE `".$db_prefix."comments` (
    `id` int(11) NOT NULL auto_increment,
    `name` char(50) default NULL,
    `comment` text,
    PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;", $con) or die(mysql_error());
    
    upOther("about","Insert a short description of your group here."); 
    upOther("title",$title);
    upOther("subtitle",$subtitle);
    upOther("image",$image);
    upOther("showPictures",$pics);
}
else {
    echo "Please at least type in a title.";
}

?>

<form action="setup.php" method="post">
    Title: <input type="text" name="title" /><br />
    Subtitle: <input type="text" name="subtitle" /><br />
    Image URL: <input type="text" name="image" /><br />
    Show Pictures? (TRUE or FALSE) <input type="text" name="pics" /><br />
    <button>Create!</button>
</form>

