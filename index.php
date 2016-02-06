<?

define('IN_MYTOOLS', 1);

function show_404_page() {
	header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
	$php_self = htmlentities($_SERVER['PHP_SELF']);
	echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL {$php_self} was not found on this server.</p>
</body></html>
EOF;
	die();
}

if (!isset($_GET['tool'])) {

?>
<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<title>纸飞机工具集</title>
</head>
<body>
	<h1>纸飞机工具集</h1>
	<h3>
		<a href="http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>?tool=qrcode">二维码生成器</a>
		<a href="http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>?tool=getfile">图片proxy</a>
		<a href="http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>?tool=ded">教务处通知列表</a>
	</h3>
</body>
</html>
<?

	die();
}

// 防止文件包含漏洞
$tool = preg_replace('/[\.\/\x00\x10\x13]/', '', $_GET['tool']);
$file = $tool . '.php';
if (file_exists($file))
	require_once $file;
else
	show_404_page();
