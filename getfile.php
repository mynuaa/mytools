<?

if (!defined('IN_MYTOOLS')) {
	header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
	echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL {$_SERVER['PHP_SELF']} was not found on this server.</p>
</body></html>
EOF;
	die();
}

$api = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&url=文件地址";

if (isset($_GET['url'])) {
	// $url = base64_decode($_GET['url']);
	$url = $_GET['url'];
	header('Content-type: image/jpg');
	echo file_get_contents($url);
}
else {

?>
<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<title>纸飞机文件proxy</title>
</head>
<body>
	<h3>使用指南</h3>
	<p>
		<span>API地址：GET</span>
		<a href="<?=$api?>"><?=$api?></a>
	</p>
	<p>
		有时候服务器会判断UA，显示“此图片来自xxx，未经允许不可饮用”，使用本工具可以解决此问题。
	</p>
</body>
</html>
<?

}
