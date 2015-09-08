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

require_once 'include_libs/myqrcode.php';

$api = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&text=你要输入的文字";

if (isset($_GET['text'])) {
	QRcode::png($_GET['text']);
}
else {

?>
<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<title>纸飞机二维码生成器</title>
</head>
<body>
	<h3>使用指南</h3>
	<p>
		<span>API地址：GET</span>
		<a href="<?=$api?>"><?=$api?></a>
	</p>
	<p>
		为了方便校内网用户获取二维码，特添加此接口。没有使用限制，请尽情使用吧！
	</p>
</body>
</html>
<?

}
