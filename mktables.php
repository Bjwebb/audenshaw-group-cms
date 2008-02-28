<?php 
include 'config.php';

$con = mysql_connect($db_host,$db_name,$db_pass);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

// Uncomment this to wipe the datase first
// mysql_query("DROP DATABASE $db_db",$con);

mysql_query("CREATE DATABASE IF NOT EXISTS $db_db",$con);

if (mysql_select_db($db_db, $con)); else die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `".$db_prefix."members` (
  `id` int(11) NOT NULL auto_increment,
  `firstName` char(20) default NULL,
  `lastName` char(20) default NULL,
  `position` char(40) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;", $con) or die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `".$db_prefix."news` (
  `id` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `title` char(50) default NULL,
  `text` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;", $con) or die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `".$db_prefix."other` (
  `id` int(11) NOT NULL auto_increment,
  `type` char(20) default NULL,
  `text` text,
  `link` char(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;", $con) or die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `".$db_prefix."projects` (
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

?>
