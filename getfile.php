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
	$url = $_GET['url'];
	if (preg_match('/\u00/', $url)) die('No zero truncate.');
	preg_match('/^(http|https|ftp)/', $url, $matches);
	if (!isset($matches[0])) die('There are no file inclusion.');
	$url = preg_replace('/^https*:\/\//', '', $url);
	// 禁止访问本地文件
	if (preg_match('/^(localhost|127\.0\.0\.1|::1|192\.168\.121\.21)/', $url)) die('Not local file!');
	// 只允许访问图片、压缩包等文件
	if (!preg_match('/(jpg|jpeg|png|bmp|tif|tiff|gif|svg|rar|zip|7z|gzip)(\?.*|)$/', $url)) die('Static file only!');
	$url = $matches[1] . '://' . $url;
	echo file_get_contents($url);
	exit();
}

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
