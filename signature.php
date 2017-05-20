<?php

// TODO: 加入文件缓存功能

$db_dz = new mysqli('localhost', 'discuz', '6b1dd49556b53b', 'discuz');
$db_dz->set_charset('utf8');

if (isset($_GET['uid'])) {
	$uid = intval($_GET['uid']);
	$bgs = ['blue', 'green', 'red', 'yellow', 'purple'];
	if (!isset($_GET['bg']) || !isset($bgs[$_GET['bg']])) $bg = imagecreatefrompng('images/bg-blue.png');
	else $bg = imagecreatefrompng('images/bg-' . $bgs[$_GET['bg']] . '.png');
	try {
		$avatar = imagecreatefromjpeg('http://' . $_SERVER['HTTP_HOST'] . '/ucenter/avatar.php?uid=' . $uid . '&size=middle');
	}
	catch (Exception $e) {
		$avatar = imagecreatefromgif('http://' . $_SERVER['HTTP_HOST'] . '/ucenter/avatar.php?uid=' . $uid . '&size=middle');
	}
	$avatar_new = imagecreatetruecolor(83, 83);
	imagecopyresampled($avatar_new, $avatar, 0, 0, 0, 0, 83, 83, 120, 120);
	imagecopy($bg, $avatar_new, 10, 10, 0, 0, 83, 83);
	// state为状态位，位1、位2分别表示论坛、商城
	// TODO: 根据state来控制显示信息
	$state = isset($_GET['state']) ? intval($_GET['state']) : 7;
	$userInfo = $db_dz->query("SELECT `username`, `credits` FROM `common_member` WHERE `uid` = {$uid} LIMIT 1");
	$userInfo = $userInfo->fetch_array();
	$username = $userInfo['username'];
	$credits = $userInfo['credits'];
	//
	$userInfo = $db_dz->query("SELECT `posts`, `threads` FROM `common_member_count` WHERE `uid` = {$uid} LIMIT 1");
	$userInfo = $userInfo->fetch_array();
	$posts = $userInfo['posts'];
	$threads = $userInfo['threads'];
	//
	$userInfo = $db_dz->query("SELECT `customstatus` FROM `common_member_field_forum` WHERE `uid` = {$uid} LIMIT 1");
	$userInfo = $userInfo->fetch_array();
	$customstatus = $userInfo['customstatus'];
	if ($customstatus == '') $customstatus = '没有自定义头衔';
	//
	$sql = "SELECT `grouptitle` FROM `common_usergroup` WHERE `groupid` = (SELECT `groupid` FROM `common_member` WHERE `uid` = {$uid} LIMIT 1) LIMIT 1";
	$group = $db_dz->query($sql);
	$group = $group->fetch_array();
	$group = $group['grouptitle'];
	//
	$text_color = imagecolorallocate($bg, 255, 255, 255);
	$font = 'fonts/pingfang.ttf';
	imagettftext($bg, 10, 0, 100, 20, $text_color, $font, $username);
	imagettftext($bg, 10, 0, 100, 50, $text_color, $font, "{$threads} 主题 / {$posts} 帖子 / {$credits} 积分");
	imagettftext($bg, 10, 0, 100, 69, $text_color, $font, $group);
	imagettftext($bg, 10, 0, 100, 88, $text_color, $font, $customstatus);
	//
	header('Content-type: image/png');
	header('Content-disposition: inline; filename=signature_' . strtolower($username) . '.png');
	imagepng($bg);
	imagedestroy($bg);
	exit();
}
